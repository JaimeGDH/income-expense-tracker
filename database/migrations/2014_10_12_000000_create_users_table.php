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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Agregar inserción a la tabla users
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Jaime Venegas',
            'email' => 'jaimevenegascancino@gmail.com',
            'email_verified_at' => null,
            'password' => '$2y$10$XJlWYFgsTLjRLTYoyTlPl.u/CCL0srFVY3.J01UIGTWd8cEUhkV6u',
            'remember_token' => null,
            'created_at' => '2023-08-02 15:33:14',
            'updated_at' => '2023-08-02 15:33:14',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
