
-- Database: toko_roti
-- PostgreSQL Version untuk Laravel
-- Payment Gateway: Midtrans & COD Only

-- Tabel: pengguna
CREATE TABLE pengguna (
    id BIGSERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP,
    password VARCHAR(255) NOT NULL,
    telepon VARCHAR(20),
    tanggal_lahir DATE,
    jenis_kelamin VARCHAR(20) CHECK (jenis_kelamin IN ('laki-laki', 'perempuan', 'lainnya')),
    foto_profil VARCHAR(255),
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_pengguna_email ON pengguna(email);
CREATE INDEX idx_pengguna_telepon ON pengguna(telepon);

-- Tabel: kategori
CREATE TABLE kategori (
    id BIGSERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    deskripsi TEXT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_kategori_slug ON kategori(slug);
CREATE INDEX idx_kategori_aktif ON kategori(aktif);

-- Tabel: produk
CREATE TABLE produk (
    id BIGSERIAL PRIMARY KEY,
    kategori_id BIGINT NOT NULL,
    nama VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    deskripsi TEXT,
    harga DECIMAL(15, 2) NOT NULL,
    stok INTEGER NOT NULL DEFAULT 0,
    sku VARCHAR(100) NOT NULL UNIQUE,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
);

CREATE INDEX idx_produk_kategori_id ON produk(kategori_id);
CREATE INDEX idx_produk_slug ON produk(slug);
CREATE INDEX idx_produk_sku ON produk(sku);
CREATE INDEX idx_produk_aktif ON produk(aktif);

-- Tabel: gambar_produk
CREATE TABLE gambar_produk (
    id BIGSERIAL PRIMARY KEY,
    produk_id BIGINT NOT NULL,
    path_gambar VARCHAR(255) NOT NULL,
    gambar_utama BOOLEAN DEFAULT FALSE,
    urutan INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

CREATE INDEX idx_gambar_produk_produk_id ON gambar_produk(produk_id);

-- Tabel: keranjang
CREATE TABLE keranjang (
    id BIGSERIAL PRIMARY KEY,
    pengguna_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

CREATE INDEX idx_keranjang_pengguna_id ON keranjang(pengguna_id);

-- Tabel: item_keranjang
CREATE TABLE item_keranjang (
    id BIGSERIAL PRIMARY KEY,
    keranjang_id BIGINT NOT NULL,
    produk_id BIGINT NOT NULL,
    jumlah INTEGER NOT NULL DEFAULT 1,
    harga DECIMAL(15, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (keranjang_id) REFERENCES keranjang(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

CREATE INDEX idx_item_keranjang_keranjang_id ON item_keranjang(keranjang_id);
CREATE INDEX idx_item_keranjang_produk_id ON item_keranjang(produk_id);

-- Tabel: alamat
CREATE TABLE alamat (
    id BIGSERIAL PRIMARY KEY,
    pengguna_id BIGINT NOT NULL,
    label VARCHAR(100) NOT NULL,
    nama_penerima VARCHAR(255) NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    kota VARCHAR(100) NOT NULL,
    provinsi VARCHAR(100) NOT NULL,
    kode_pos VARCHAR(10) NOT NULL,
    alamat_utama BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

CREATE INDEX idx_alamat_pengguna_id ON alamat(pengguna_id);
CREATE INDEX idx_alamat_alamat_utama ON alamat(alamat_utama);

COMMENT ON COLUMN alamat.label IS 'Contoh: Rumah, Kantor, Kos';

-- Tabel: pesanan
CREATE TABLE pesanan (
    id BIGSERIAL PRIMARY KEY,
    pengguna_id BIGINT NOT NULL,
    alamat_id BIGINT NOT NULL,
    nomor_pesanan VARCHAR(50) NOT NULL UNIQUE,
    subtotal DECIMAL(15, 2) NOT NULL,
    biaya_ongkir DECIMAL(15, 2) DEFAULT 0,
    total_bayar DECIMAL(15, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'menunggu' CHECK (status IN ('menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan')),
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE,
    FOREIGN KEY (alamat_id) REFERENCES alamat(id) ON DELETE RESTRICT
);

CREATE INDEX idx_pesanan_pengguna_id ON pesanan(pengguna_id);
CREATE INDEX idx_pesanan_nomor_pesanan ON pesanan(nomor_pesanan);
CREATE INDEX idx_pesanan_status ON pesanan(status);
CREATE INDEX idx_pesanan_created_at ON pesanan(created_at);

-- Tabel: item_pesanan
CREATE TABLE item_pesanan (
    id BIGSERIAL PRIMARY KEY,
    pesanan_id BIGINT NOT NULL,
    produk_id BIGINT NOT NULL,
    nama_produk VARCHAR(255) NOT NULL,
    jumlah INTEGER NOT NULL,
    harga DECIMAL(15, 2) NOT NULL,
    subtotal DECIMAL(15, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE RESTRICT
);

CREATE INDEX idx_item_pesanan_pesanan_id ON item_pesanan(pesanan_id);
CREATE INDEX idx_item_pesanan_produk_id ON item_pesanan(produk_id);

-- Tabel: metode_pembayaran (Hanya Midtrans & COD)
CREATE TABLE metode_pembayaran (
    id BIGSERIAL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kode VARCHAR(50) NOT NULL UNIQUE,
    penyedia VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_metode_pembayaran_kode ON metode_pembayaran(kode);
CREATE INDEX idx_metode_pembayaran_aktif ON metode_pembayaran(aktif);

COMMENT ON COLUMN metode_pembayaran.penyedia IS 'midtrans atau manual';

-- Tabel: pembayaran
CREATE TABLE pembayaran (
    id BIGSERIAL PRIMARY KEY,
    pesanan_id BIGINT NOT NULL UNIQUE,
    metode_pembayaran_id BIGINT NOT NULL,
    nomor_pembayaran VARCHAR(50) NOT NULL UNIQUE,
    jumlah DECIMAL(15, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'menunggu' CHECK (status IN ('menunggu', 'diproses', 'berhasil', 'gagal', 'kadaluarsa', 'dikembalikan')),
    id_transaksi_midtrans VARCHAR(255),
    snap_token VARCHAR(255),
    respon_midtrans TEXT,
    dibayar_pada TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (metode_pembayaran_id) REFERENCES metode_pembayaran(id) ON DELETE RESTRICT
);

CREATE INDEX idx_pembayaran_pesanan_id ON pembayaran(pesanan_id);
CREATE INDEX idx_pembayaran_nomor_pembayaran ON pembayaran(nomor_pembayaran);
CREATE INDEX idx_pembayaran_status ON pembayaran(status);
CREATE INDEX idx_pembayaran_id_transaksi_midtrans ON pembayaran(id_transaksi_midtrans);
CREATE INDEX idx_pembayaran_snap_token ON pembayaran(snap_token);

COMMENT ON COLUMN pembayaran.id_transaksi_midtrans IS 'Order ID dari Midtrans';
COMMENT ON COLUMN pembayaran.snap_token IS 'Snap Token untuk Midtrans Payment Page';
COMMENT ON COLUMN pembayaran.respon_midtrans IS 'Respon JSON dari Midtrans API';

-- Fungsi untuk auto update timestamp updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger untuk semua tabel
CREATE TRIGGER update_pengguna_updated_at BEFORE UPDATE ON pengguna FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_kategori_updated_at BEFORE UPDATE ON kategori FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_produk_updated_at BEFORE UPDATE ON produk FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_gambar_produk_updated_at BEFORE UPDATE ON gambar_produk FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_keranjang_updated_at BEFORE UPDATE ON keranjang FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_item_keranjang_updated_at BEFORE UPDATE ON item_keranjang FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_alamat_updated_at BEFORE UPDATE ON alamat FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_pesanan_updated_at BEFORE UPDATE ON pesanan FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_item_pesanan_updated_at BEFORE UPDATE ON item_pesanan FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_metode_pembayaran_updated_at BEFORE UPDATE ON metode_pembayaran FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_pembayaran_updated_at BEFORE UPDATE ON pembayaran FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Insert data metode pembayaran (Midtrans & COD)
INSERT INTO metode_pembayaran (nama, kode, penyedia, deskripsi, aktif) VALUES
('Midtrans - All Payment', 'midtrans', 'midtrans', 'Pembayaran via Midtrans (Credit Card, Debit Card, E-Wallet, VA Bank, dll)', TRUE),
('COD (Bayar di Tempat)', 'cod', 'manual', 'Bayar tunai saat barang diterima', TRUE);

-- Insert data contoh kategori
INSERT INTO kategori (nama, slug, deskripsi, aktif) VALUES
('Roti Manis', 'roti-manis', 'Berbagai jenis roti manis dengan topping lezat', TRUE),
('Roti Tawar', 'roti-tawar', 'Roti tawar untuk sarapan dan sandwich', TRUE),
('Kue Kering', 'kue-kering', 'Aneka kue kering untuk camilan', TRUE),
('Kue Basah', 'kue-basah', 'Kue basah tradisional dan modern', TRUE),
('Cake', 'cake', 'Kue ulang tahun dan kue tart', TRUE);

-- Insert data contoh pengguna (password: password123)
-- Hash dibuat dengan bcrypt
INSERT INTO pengguna (nama, email, password, telepon, jenis_kelamin, email_verified_at) VALUES
('Budi Santoso', 'budi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'laki-laki', CURRENT_TIMESTAMP),
('Siti Aminah', 'siti@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'perempuan', CURRENT_TIMESTAMP),
('Andi Wijaya', 'andi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'laki-laki', CURRENT_TIMESTAMP);

-- Insert data contoh produk
INSERT INTO produk (kategori_id, nama, slug, deskripsi, harga, stok, sku, aktif) VALUES
(1, 'Roti Coklat Keju', 'roti-coklat-keju', 'Roti manis dengan topping coklat dan keju lumer', 15000, 50, 'RTI-001', TRUE),
(1, 'Roti Sosis', 'roti-sosis', 'Roti manis dengan sosis berkualitas', 12000, 40, 'RTI-002', TRUE),
(2, 'Roti Tawar Gandum', 'roti-tawar-gandum', 'Roti tawar gandum sehat untuk sarapan', 18000, 30, 'RTI-003', TRUE),
(3, 'Nastar Premium', 'nastar-premium', 'Kue kering nastar dengan selai nanas pilihan', 75000, 20, 'KUE-001', TRUE),
(5, 'Blackforest Cake', 'blackforest-cake', 'Kue blackforest dengan cherry import', 250000, 10, 'CAKE-001', TRUE);

-- Insert data contoh alamat
INSERT INTO alamat (pengguna_id, label, nama_penerima, telepon, alamat, kota, provinsi, kode_pos, alamat_utama) VALUES
(1, 'Rumah', 'Budi Santoso', '081234567890', 'Jl. Merdeka No. 123', 'Jakarta Pusat', 'DKI Jakarta', '10110', TRUE),
(2, 'Kantor', 'Siti Aminah', '081234567891', 'Jl. Sudirman No. 456', 'Jakarta Selatan', 'DKI Jakarta', '12190', TRUE),
(3, 'Rumah', 'Andi Wijaya', '081234567892', 'Jl. Gatot Subroto No. 789', 'Bandung', 'Jawa Barat', '40262', TRUE);