<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('nama_pelanggan');
            $table->string('email_pelanggan');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telepon')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->string('foto_profil')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('email_pelanggan');
            $table->index('telepon');
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable(); 
            $table->string('slug')->unique();
            $table->boolean('is_aktif')->default(true);

            $table->timestamps();
        });
                Schema::create('alamat_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
                Schema::create('alamat_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
                Schema::create('alamat_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
                Schema::create('alamat_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
                Schema::create('alamat_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
