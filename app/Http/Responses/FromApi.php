<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;



class FromApi extends JsonResponse
{
    public const STATUS_OK = 'ok';
    public const STATUS_ERROR = 'error';

    public function __construct(int $httpCode, string $description, $payload = null)
    {
        $isOk = $httpCode >= self::HTTP_OK && $httpCode <= 299;

        $data = [
            'is_ok' => $isOk,
            'status' => $isOk ? static::STATUS_OK : static::STATUS_ERROR,
            'description' => $description,
            'payload' => $payload,
        ];

        parent::__construct($data, $httpCode, $this->headers(), $this->jsonOptions());
    }

    public static function success($payload = null): self
    {
        if ($payload instanceof Arrayable) {
            $payload = $payload->toArray();
        }

        return new static(self::HTTP_OK, 'OK', $payload);
    }

    public static function error(
        string $description = 'Server Error',
        int $code = self::HTTP_INTERNAL_SERVER_ERROR,
    ): self {
        return (new static($code, $description));
    }

    public static function notFound(?string $description = 'Not Found'): self
    {
        return new static(static::HTTP_NOT_FOUND, $description);
    }

    public static function accessDenied(?string $description = 'Access Denied'): self
    {
        return new static(static::HTTP_FORBIDDEN, $description);
    }

    public static function validationFailed(?string $description = 'Validation Failed'): self
    {
        return new static(static::HTTP_UNPROCESSABLE_ENTITY, $description);
    }

    public static function badRequest(?string $description = 'Bad Request'): self
    {
        return new static(static::HTTP_BAD_REQUEST, $description);
    }

    public static function unauthenticated(?string $description = 'Unauthenticated'): self
    {
        return new static(static::HTTP_UNAUTHORIZED, $description);
    }

    public static function exception(Throwable $throwable): self
    {
        if ($throwable instanceof NotFoundHttpException) {
            return self::notFound();
        }

        $detailed = is_dev_env();

        $code = method_exists($throwable, 'getStatusCode')
            ? $throwable->getStatusCode()
            : static::HTTP_INTERNAL_SERVER_ERROR;

        $message = $throwable->getMessage();

        if ($throwable instanceof ValidationException) {
            $code = $throwable->status;
            $message = self::getValidationMessage($throwable);
        }

        if ($throwable instanceof QueryException && is_production()) {
            $message = $throwable->getPrevious()->getMessage();
        }

        return new static($code, $message, [
            'message' => $message,
            'line' => $detailed ? $throwable->getLine() : 0,
            'trace' => $detailed ? explode(PHP_EOL, $throwable->getTraceAsString()) : [],
        ]);
    }

    public function getPayload()
    {
        return Arr::get($this->getData(true), 'payload');
    }

    public function getPayloadAttribute($key, $default = null)
    {
        return Arr::get($this->getPayload(), $key, $default);
    }

    public function getMessage()
    {
        return Arr::get($this->getData(true), 'message');
    }

    public function getDescription()
    {
        return Arr::get($this->getData(true), 'description');
    }

    public function setPayload($payload = null): void
    {
        $data = $this->getData(true);

        $data['payload'] = $payload;

        $this->setData($data);
    }

    private function headers(): array
    {
        $headers = [];

        // Стандартный ответ не устанавливает кодировку и она иногда получается кривой
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        $headers['X-Response-Type'] = 'api';

        return $headers;
    }

    private function jsonOptions(): int
    {
        if (is_dev_env() || request('pretty', 'n') === 'y') {
            $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
        } else {
            $jsonOptions = 0;
        }

        return $jsonOptions | JsonResponse::DEFAULT_ENCODING_OPTIONS;
    }

    private static function getValidationMessage(ValidationException $validationException): string
    {
        return implode('<br/>', $validationException->validator->getMessageBag()->unique());
    }
}
