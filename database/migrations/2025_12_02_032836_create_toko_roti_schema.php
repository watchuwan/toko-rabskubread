<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telepon', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->string('foto_profil')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::table('pelanggan', function (Blueprint $table) {
            $table->index('email', 'idx_pelanggan_email');
            $table->index('telepon', 'idx_pelanggan_telepon');
        });

        // Tabel kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->index('slug', 'idx_kategori_slug');
            $table->index('aktif', 'idx_kategori_aktif');
        });

        // Tabel produk
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('stok')->default(0);
            $table->string('sku', 100)->unique();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->index('kategori_id', 'idx_produk_kategori_id');
            $table->index('slug', 'idx_produk_slug');
            $table->index('sku', 'idx_produk_sku');
            $table->index('aktif', 'idx_produk_aktif');
        });

        // Tabel gambar_produk
        Schema::create('gambar_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('path_gambar');
            $table->boolean('gambar_utama')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('gambar_produk', function (Blueprint $table) {
            $table->index('produk_id', 'idx_gambar_produk_produk_id');
        });

        // Tabel keranjang
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('keranjang', function (Blueprint $table) {
            $table->index('pelanggan_id', 'idx_keranjang_pelanggan_id');
        });

        // Tabel item_keranjang
        Schema::create('item_keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keranjang_id')->constrained('keranjang')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga', 15, 2);
            $table->timestamps();
        });

        Schema::table('item_keranjang', function (Blueprint $table) {
            $table->index('keranjang_id', 'idx_item_keranjang_keranjang_id');
            $table->index('produk_id', 'idx_item_keranjang_produk_id');
        });

        // Tabel alamat
        Schema::create('alamat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->string('label', 100);
            $table->string('nama_penerima');
            $table->string('telepon', 20);
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10);
            $table->boolean('alamat_utama')->default(false);
            $table->timestamps();
        });

        Schema::table('alamat', function (Blueprint $table) {
            $table->index('pelanggan_id', 'idx_alamat_pelanggan_id');
            $table->index('alamat_utama', 'idx_alamat_alamat_utama');
        });

        // Tabel pesanan
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('alamat_id')->constrained('alamat')->onDelete('restrict');
            $table->string('nomor_pesanan', 50)->unique();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('biaya_ongkir', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2);
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->index('pelanggan_id', 'idx_pesanan_pelanggan_id');
            $table->index('nomor_pesanan', 'idx_pesanan_nomor_pesanan');
            $table->index('status', 'idx_pesanan_status');
            $table->index('created_at', 'idx_pesanan_created_at');
        });

        // Tabel item_pesanan
        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('restrict');
            $table->string('nama_produk');
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        Schema::table('item_pesanan', function (Blueprint $table) {
            $table->index('pesanan_id', 'idx_item_pesanan_pesanan_id');
            $table->index('produk_id', 'idx_item_pesanan_produk_id');
        });

        // Tabel metode_pembayaran
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kode', 50)->unique();
            $table->string('penyedia', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->index('kode', 'idx_metode_pembayaran_kode');
            $table->index('aktif', 'idx_metode_pembayaran_aktif');
        });

        // Tabel pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade')->unique();
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayaran')->onDelete('restrict');
            $table->string('nomor_pembayaran', 50)->unique();
            $table->decimal('jumlah', 15, 2);
            $table->enum('status', ['menunggu', 'diproses', 'berhasil', 'gagal', 'kadaluarsa', 'dikembalikan'])->default('menunggu');
            $table->string('id_transaksi_midtrans', 255)->nullable();
            $table->string('snap_token', 255)->nullable();
            $table->text('respon_midtrans')->nullable();
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamps();
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index('pesanan_id', 'idx_pembayaran_pesanan_id');
            $table->index('nomor_pembayaran', 'idx_pembayaran_nomor_pembayaran');
            $table->index('status', 'idx_pembayaran_status');
            $table->index('id_transaksi_midtrans', 'idx_pembayaran_id_transaksi_midtrans');
            $table->index('snap_token', 'idx_pembayaran_snap_token');
        });

        // Sample insert: metode_pembayaran
        DB::table('metode_pembayaran')->insert([
            [
                'nama' => 'Midtrans - All Payment',
                'kode' => 'midtrans',
                'penyedia' => 'midtrans',
                'deskripsi' => 'Pembayaran via Midtrans (Credit Card, Debit Card, E-Wallet, VA Bank, dll)',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'COD (Bayar di Tempat)',
                'kode' => 'cod',
                'penyedia' => 'manual',
                'deskripsi' => 'Bayar tunai saat barang diterima',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Sample insert: kategori
        DB::table('kategori')->insert([
            ['nama' => 'Roti Manis', 'slug' => 'roti-manis', 'deskripsi' => 'Berbagai jenis roti manis dengan topping lezat', 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Roti Tawar', 'slug' => 'roti-tawar', 'deskripsi' => 'Roti tawar untuk sarapan dan sandwich', 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kue Kering', 'slug' => 'kue-kering', 'deskripsi' => 'Aneka kue kering untuk camilan', 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kue Basah', 'slug' => 'kue-basah', 'deskripsi' => 'Kue basah tradisional dan modern', 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Cake', 'slug' => 'cake', 'deskripsi' => 'Kue ulang tahun dan kue tart', 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Sample insert: pelanggan (password bcrypt dari 'password123')
        DB::table('pelanggan')->insert([
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'telepon' => '081234567890',
                'jenis_kelamin' => 'laki-laki',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'telepon' => '081234567891',
                'jenis_kelamin' => 'perempuan',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Andi Wijaya',
                'email' => 'andi@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'telepon' => '081234567892',
                'jenis_kelamin' => 'laki-laki',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample insert: produk
        DB::table('produk')->insert([
            [
                'kategori_id' => 1,
                'nama' => 'Roti Coklat Keju',
                'slug' => 'roti-coklat-keju',
                'deskripsi' => 'Roti manis dengan topping coklat dan keju lumer',
                'harga' => 15000,
                'stok' => 50,
                'sku' => 'RTI-001',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => 1,
                'nama' => 'Roti Sosis',
                'slug' => 'roti-sosis',
                'deskripsi' => 'Roti manis dengan sosis berkualitas',
                'harga' => 12000,
                'stok' => 40,
                'sku' => 'RTI-002',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => 2,
                'nama' => 'Roti Tawar Gandum',
                'slug' => 'roti-tawar-gandum',
                'deskripsi' => 'Roti tawar gandum sehat untuk sarapan',
                'harga' => 18000,
                'stok' => 30,
                'sku' => 'RTI-003',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => 3,
                'nama' => 'Nastar Premium',
                'slug' => 'nastar-premium',
                'deskripsi' => 'Kue kering nastar dengan selai nanas pilihan',
                'harga' => 75000,
                'stok' => 20,
                'sku' => 'KUE-001',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => 5,
                'nama' => 'Blackforest Cake',
                'slug' => 'blackforest-cake',
                'deskripsi' => 'Kue blackforest dengan cherry import',
                'harga' => 250000,
                'stok' => 10,
                'sku' => 'CAKE-001',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Sample insert: alamat
        DB::table('alamat')->insert([
            [
                'pelanggan_id' => 1,
                'label' => 'Rumah',
                'nama_penerima' => 'Budi Santoso',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '10110',
                'alamat_utama' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelanggan_id' => 2,
                'label' => 'Kantor',
                'nama_penerima' => 'Siti Aminah',
                'telepon' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 456',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '12190',
                'alamat_utama' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelanggan_id' => 3,
                'label' => 'Rumah',
                'nama_penerima' => 'Andi Wijaya',
                'telepon' => '081234567892',
                'alamat' => 'Jl. Gatot Subroto No. 789',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40262',
                'alamat_utama' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'pembayaran',
            'metode_pembayaran',
            'item_pesanan',
            'pesanan',
            'alamat',
            'item_keranjang',
            'keranjang',
            'gambar_produk',
            'produk',
            'kategori',
            'pelanggan'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }
};
