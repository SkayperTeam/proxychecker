<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('proxy_group_id')
                ->constrained('proxy_groups');
            $table
                ->string('connection_type')
                ->nullable();
            $table->string('ip');
            $table->string('port');
            $table
                ->string('timeout')
                ->nullable();
            $table
                ->string('country')
                ->nullable();
            $table
                ->string('city')
                ->nullable();
            $table
                ->string('state')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxies');
    }
};
