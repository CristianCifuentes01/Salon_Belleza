<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('cliente')->after('admin');
            $table->string('api_token', 80)->nullable()->unique()->after('token');
        });

        DB::table('users')->orderBy('id')->get()->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'role' => $user->admin ? 'admin' : 'cliente',
                    'api_token' => hash('sha256', Str::random(60) . $user->email),
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['api_token']);
            $table->dropColumn(['role', 'api_token']);
        });
    }
};
