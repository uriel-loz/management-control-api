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
        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('oauth_auth_codes', 'code_challenge')) {
                $table->string('code_challenge', 128)->nullable()->after('expires_at');
            }
            
            if (!Schema::hasColumn('oauth_auth_codes', 'code_challenge_method')) {
                $table->string('code_challenge_method', 10)->nullable()->after('code_challenge');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->dropColumn(['code_challenge', 'code_challenge_method']);
        });
    }
};
