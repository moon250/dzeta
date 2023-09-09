<?php

use App\Enums\OauthService;
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
        Schema::create('oauth_credentials', function (Blueprint $table) {
            $services = OauthService::cases();
            $services = array_map(fn (OauthService $service) => $service->value, $services);

            $table->id();
            $table->enum('service', $services);
            $table->string('user_id');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_credentials');
    }
};
