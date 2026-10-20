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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('cover_url')->nullable()->after('logo_url'); // Imagen de portada / modelo del login
            $table->string('cover_title')->nullable()->after('cover_url'); // Título sobre la portada (Ej: Marely Piscoya / Tu Nombre)
            $table->string('cover_subtitle')->nullable()->after('cover_title'); // Subtítulo (Ej: Pasión, Baile y Estilo de Vida Saludable)
            $table->string('phone')->nullable()->after('owner_phone'); // Teléfono comercial de atención
            $table->string('email')->nullable()->after('owner_email'); // Email comercial
            $table->string('address')->nullable()->after('email'); // Dirección física / Sede principal
            $table->string('yape_number')->nullable()->after('address'); // Número de Yape / Plin
            $table->string('yape_holder')->nullable()->after('yape_number'); // Titular del Yape
            $table->string('yape_qr')->nullable()->after('yape_holder'); // Imagen QR de Yape / Plin opcional
            $table->text('bank_accounts')->nullable()->after('yape_qr'); // Cuentas bancarias (BCP, BBVA, Interbank, etc.)
            $table->string('primary_color')->nullable()->default('#c084fc')->after('bank_accounts'); // Color primario personalizado
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'cover_url',
                'cover_title',
                'cover_subtitle',
                'phone',
                'email',
                'address',
                'yape_number',
                'yape_holder',
                'yape_qr',
                'bank_accounts',
                'primary_color',
            ]);
        });
    }
};
