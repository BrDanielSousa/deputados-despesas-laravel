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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deputado_id')->constrained('deputados')->onDelete('cascade');
            $table->integer('ano');
            $table->integer('mes');
            $table->string('cnpj_cpf_fornecedor');
            $table->integer('cod_documento');
            $table->integer('cod_lote')->nullable();
            $table->integer('cod_tipo_documento');
            $table->date('data_documento')->nullable();
            $table->string('nome_fornecedor');
            $table->string('num_documento');
            $table->string('num_ressarcimento')->nullable();
            $table->integer('parcela')->nullable();
            $table->string('tipo_despesa');
            $table->string('tipo_documento');
            $table->string('url_documento')->nullable();
            $table->decimal('valor_documento', 15, 2);
            $table->decimal('valor_glosa', 15, 2);
            $table->decimal('valor_liquido', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
