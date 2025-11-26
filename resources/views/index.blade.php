<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rabskubread - Toko Roti Online</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Alpine.js untuk interaktivitas (Keranjang, Menu & Modal) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Konfigurasi Tailwind untuk menggunakan font Inter dan palet warna -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary-brown': '#5D4037',
                        'secondary-cream': '#F8F5E9',
                        'accent-orange': '#FF7043',
                    }
                }
            }
        }
    </script>
    <!-- Menggunakan Font Inter dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Gaya kustom untuk memastikan background hero terlihat bagus */
        .hero-bg {
            background-image: url('https://placehold.co/1920x800/EFEBE9/5D4037?text=Rabskubread+Roti+Segar+Setiap+Hari');
            background-size: cover;
            background-position: center;
        }
        /* Efek hover untuk tombol */
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(255, 112, 67, 0.5);
        }
        /* Menghilangkan scrollbar di body saat modal terbuka */
        [x-cloak].hidden { display: none; }
    </style>
</head>

<!-- Alpine.js State dan Logika Keranjang -->
<body x-data="{ 
    open: false, 
    isCartOpen: false,
    phoneNumber: '6281234567890', // Nomor WhatsApp tujuan
    cart: [],
    products: [
        { id: 1, name: 'Sourdough Artisan', price: 45000, desc: 'Roti fermentasi alami dengan kulit renyah dan bagian dalam yang lembut. Wajib dicoba!', tag: 'Vegan Friendly', img: 'https://placehold.co/600x400/FF7043/FFFFFF?text=Sourdough+Artisan' },
        { id: 2, name: 'Butter Croissant', price: 20000, desc: 'Lapis demi lapis mentega Prancis, dipanggang hingga keemasan dan *flaky*.', tag: 'Best Seller', img: 'https://placehold.co/600x400/FF7043/FFFFFF?text=Croissant+Mentega' },
        { id: 3, name: 'Donat Gula Klasik', price: 12000, desc: 'Donat empuk dan lembut dengan taburan gula halus, rasa nostalgia yang tak lekang waktu.', tag: 'Manis Gurih', img: 'https://placehold.co/600x400/FF7043/FFFFFF?text=Donat+Gula' },
        { id: 4, name: 'Roti Tawar Gandum', price: 30000, desc: 'Roti tawar sehat dari biji gandum utuh, pilihan tepat untuk pola hidup sehat.', tag: 'Healthy Choice', img: 'https://placehold.co/600x400/FF7043/FFFFFF?text=Roti+Tawar' },
    ],

    // LOGIKA KERANJANG
    addToCart(product) {
        const existing = this.cart.find(item => item.id === product.id);
        if (existing) {
            existing.quantity++;
        } else {
            this.cart.push({ ...product, quantity: 1 });
        }
    },
    removeFromCart(productId) {
        this.cart = this.cart.filter(item => item.id !== productId);
    },
    incrementQuantity(productId) {
        const item = this.cart.find(item => item.id === productId);
        if (item) item.quantity++;
    },
    decrementQuantity(productId) {
        const item = this.cart.find(item => item.id === productId);
        if (item && item.quantity > 1) {
            item.quantity--;
        } else if (item && item.quantity === 1) {
            this.removeFromCart(productId);
        }
    },
    cartTotal() {
        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    cartCount() {
        return this.cart.reduce((count, item) => count + item.quantity, 0);
    },
    formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    },

    // LOGIKA CHECKOUT
    checkoutWhatsApp() {
        if (this.cart.length === 0) {
            // Gunakan pesan singkat di mobile
            alert('Keranjang belanja Anda kosong!');
            return;
        }

        let message = 'Halo Rabskubread! Saya ingin memesan produk berikut:\n\n';
        this.cart.forEach((item, index) => {
            message += `${index + 1}. ${item.name} (${item.quantity} pcs) - ${this.formatRupiah(item.price * item.quantity)}\n`;
        });
        message += `\n*Total Belanja:* ${this.formatRupiah(this.cartTotal())}`;
        message += `\n\nMohon konfirmasi pesanan saya. Terima kasih!`;

        const encodedMessage = encodeURIComponent(message);
        const waLink = `https://wa.me/${this.phoneNumber}?text=${encodedMessage}`;
        
        window.open(waLink, '_blank');
        this.isCartOpen = false;
    },

    // SIMULASI MIDTRANS 
    checkoutMidtrans() {
        if (this.cart.length === 0) {
            alert('Keranjang belanja Anda kosong!');
            return;
        }
        
        // Simulasikan Midtrans Payment Gateway Call
        // Di aplikasi nyata, ini akan memanggil API Midtrans
        alert('Simulasi Midtrans: Anda akan diarahkan ke halaman pembayaran.');

        // Setelah pembayaran sukses (simulasi):
        setTimeout(() => {
            if (confirm('Simulasi: Apakah pembayaran Midtrans berhasil?')) {
                alert('Pembayaran Berhasil! Pesanan Anda sedang diproses.');
                this.cart = [];
                this.isCartOpen = false;
            } else {
                 alert('Pembayaran Gagal. Silakan coba lagi atau gunakan WhatsApp.');
            }
        }, 1000);
    }

}" class="font-sans text-primary-brown bg-white min-h-screen">

    <!-- Header & Navigasi -->
    <header class="sticky top-0 z-50 shadow-lg bg-white/95 backdrop-blur-sm border-b border-gray-100">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo (Responsif: text-xl di mobile, text-3xl di desktop) -->
                <a href="#" class="text-xl sm:text-3xl font-extrabold text-primary-brown tracking-wider">
                    Rabsku<span class="text-accent-orange">bread</span>
                </a>

                <!-- Navigasi Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#produk" class="text-primary-brown hover:text-accent-orange font-semibold transition duration-150 ease-in-out">Belanja</a>
                    <a href="#unggulan" class="text-primary-brown hover:text-accent-orange font-semibold transition duration-150 ease-in-out">Tentang Kami</a>
                    <a href="#testimoni" class="text-primary-brown hover:text-accent-orange font-semibold transition duration-150 ease-in-out">Testimoni</a>
                    
                    <!-- Tombol Keranjang (Desktop) -->
                    <button @click="isCartOpen = true" class="relative p-2 rounded-full text-primary-brown hover:bg-secondary-cream transition duration-300">
                        <!-- Icon Keranjang -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <!-- Counter Keranjang -->
                        <span x-show="cartCount() > 0" x-text="cartCount()" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-accent-orange rounded-full"></span>
                    </button>
                    
                    <a href="#kontak" class="text-white bg-accent-orange px-5 py-2 rounded-xl font-bold shadow-md hover:bg-opacity-90 transition duration-300">Pesan</a>
                </div>

                <!-- Tombol Mobile Menu & Keranjang (Mobile) -->
                <div class="md:hidden flex items-center space-x-4">
                    <!-- Tombol Keranjang Mobile -->
                    <button @click="isCartOpen = true" class="relative p-2 rounded-full text-primary-brown hover:bg-secondary-cream transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span x-show="cartCount() > 0" x-text="cartCount()" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-accent-orange rounded-full"></span>
                    </button>
                    
                    <!-- Tombol Mobile Menu -->
                    <button @click="open = !open" type="button" class="p-2 rounded-md text-primary-brown hover:bg-secondary-cream focus:outline-none focus:ring-2 focus:ring-accent-orange" aria-controls="mobile-menu" aria-expanded="false">
                        <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="open" @click.outside="open = false" x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 flex flex-col">
                    <a @click="open = false" href="#produk" class="text-primary-brown hover:bg-secondary-cream block px-3 py-2 rounded-md font-semibold">Belanja</a>
                    <a @click="open = false" href="#unggulan" class="text-primary-brown hover:bg-secondary-cream block px-3 py-2 rounded-md font-semibold">Tentang Kami</a>
                    <a @click="open = false" href="#testimoni" class="text-primary-brown hover:bg-secondary-cream block px-3 py-2 rounded-md font-semibold">Testimoni</a>
                    <a @click="open = false" href="#kontak" class="text-white bg-accent-orange block px-3 py-2 rounded-md font-bold mt-2 text-center">Pesan Sekarang</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <!-- Bagian Hero -->
        <section class="hero-bg py-24 sm:py-32 md:py-40 text-center flex items-center justify-center border-b border-secondary-cream">
            <div class="bg-white/90 backdrop-blur-sm p-6 sm:p-8 md:p-14 max-w-3xl mx-4 rounded-3xl shadow-2xl border-2 border-accent-orange/50">
                <!-- Ukuran Teks Header Responsif -->
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold text-primary-brown mb-4 leading-tight">
                    Kehangatan <span class="text-accent-orange">Roti</span> <br class="hidden sm:inline">Setiap Hari
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-primary-brown/90 mb-8 max-w-xl mx-auto">
                    Temukan Croissant, Sourdough, dan Donat terbaik yang dibuat dengan resep otentik dan bahan premium.
                </p>
                <button @click="isCartOpen = true" class="btn-primary inline-block bg-accent-orange text-white font-bold py-3 px-8 sm:px-10 rounded-full shadow-xl transition duration-300 ease-in-out text-sm sm:text-base">
                    Lihat Keranjang (<span x-text="cartCount()">0</span>)
                </button>
            </div>
        </section>

        <!-- Bagian Produk E-commerce (Inti) -->
        <section id="produk" class="py-16 md:py-24 bg-secondary-cream">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-center mb-10 sm:mb-16 text-primary-brown">Jelajahi Semua Pilihan Roti Kami</h2>

                <!-- Grid Responsif: 1 kolom di mobile, 2 kolom di tablet, 4 kolom di desktop -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    
                    <!-- Loop Produk menggunakan x-for -->
                    <template x-for="product in products" :key="product.id">
                        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <img class="w-full h-40 sm:h-48 object-cover rounded-xl mb-4" :src="product.img" :alt="product.name" onerror="this.onerror=null; this.src='https://placehold.co/600x400/FF7043/FFFFFF?text=Roti'">
                            <h3 class="text-lg sm:text-xl font-bold mb-1 text-primary-brown" x-text="product.name"></h3>
                            <p class="text-xs sm:text-sm text-primary-brown/70 mb-3 line-clamp-2" x-text="product.desc"></p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xl sm:text-2xl font-extrabold text-accent-orange" x-text="formatRupiah(product.price)"></span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full" x-text="product.tag"></span>
                            </div>
                            <button @click="addToCart(product)" class="w-full mt-2 bg-primary-brown text-white py-2 sm:py-3 rounded-full font-semibold hover:bg-primary-brown/90 transition duration-300 shadow-md text-sm sm:text-base">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Tambah Keranjang
                                </div>
                            </button>
                        </div>
                    </template>
                    <!-- Akhir Loop Produk -->

                </div>
            </div>
        </section>

        <!-- Bagian Mengapa Memilih Kami -->
        <section id="unggulan" class="bg-primary-brown text-white py-16 md:py-24 border-t border-accent-orange">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Mengapa Rabskubread?</h2>

                <!-- Grid Responsif: 1 kolom di mobile, 3 kolom di tablet/desktop -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-10">
                    <!-- Keunggulan 1 -->
                    <div class="text-center p-6 bg-primary-brown/90 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                        <div class="mb-4 text-accent-orange">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 6.343a8 8 0 0111.314 12.314zM12 21v-2m0-18V3m-7 8h2m14 0h2m-4.586-4.586l-1.414 1.414m5.656 5.656l-1.414-1.414M12 12V6m0 6h4m-4 0h-4"></path></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold mb-2">Dipanggang Segar</h3>
                        <p class="text-sm text-white/80">Kami menjamin kesegaran maksimal. Roti dipanggang dan dikirim di hari yang sama.</p>
                    </div>

                    <!-- Keunggulan 2 -->
                    <div class="text-center p-6 bg-primary-brown/90 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                        <div class="mb-4 text-accent-orange">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 114 0v6m-4 0v-2a2 2 0 014 0v2m-4 0h4m-4 0v2a2 2 0 002 2h2a2 2 0 002-2v-2"></path></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold mb-2">Bahan Premium</h3>
                        <p class="text-sm text-white/80">Hanya menggunakan tepung gandum, mentega, dan cokelat terbaik di kelasnya.</p>
                    </div>

                    <!-- Keunggulan 3 -->
                    <div class="text-center p-6 bg-primary-brown/90 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                        <div class="mb-4 text-accent-orange">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold mb-2">Resep Otentik</h3>
                        <p class="text-sm text-white/80">Menghadirkan cita rasa klasik yang sempurna, diwariskan dari generasi ke generasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bagian Testimoni -->
        <section id="testimoni" class="py-16 md:py-24 container mx-auto px-4 sm:px-6 lg:px-8 bg-white">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-primary-brown">Ulasan Pelanggan</h2>

            <!-- Grid Responsif: 1 kolom di mobile, 3 kolom di tablet/desktop -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-secondary-cream p-6 rounded-xl shadow-lg border-l-4 border-accent-orange hover:shadow-xl">
                    <p class="italic text-base text-primary-brown mb-4">"Croissant-nya sangat ringan dan *buttery*! Anak-anak saya selalu minta lagi. Pelayanan cepat dan rotinya selalu fresh."</p>
                    <p class="font-semibold text-primary-brown text-sm">- Siti Aisyah, Jakarta</p>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-secondary-cream p-6 rounded-xl shadow-lg border-l-4 border-accent-orange hover:shadow-xl">
                    <p class="italic text-base text-primary-brown mb-4">"Sourdough-nya punya rasa asam yang pas, cocok untuk roti panggang dan sup. Kualitasnya konsisten, selalu puas!"</p>
                    <p class="font-semibold text-primary-brown text-sm">- Budi Santoso, Bandung</p>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-secondary-cream p-6 rounded-xl shadow-lg border-l-4 border-accent-orange hover:shadow-xl">
                    <p class="italic text-base text-primary-brown mb-4">"Penggemar donat klasik harus coba. Empuk banget, gulanya lumer. Rabskubread memang jagonya donat jadul."</p>
                    <p class="font-semibold text-primary-brown text-sm">- Maria Fernanda, Surabaya</p>
                </div>
            </div>
        </section>

        <!-- Bagian Call to Action / Kontak -->
        <section id="kontak" class="py-16 bg-accent-orange/10 border-t border-gray-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary-brown mb-4">Siap Memesan Roti Segar Anda?</h2>
                <p class="text-base sm:text-lg text-primary-brown/90 mb-8 max-w-xl mx-auto">
                    Hubungi kami sekarang untuk pemesanan dalam jumlah besar atau pertanyaan lainnya.
                </p>
                <a :href="`https://wa.me/${phoneNumber}`" target="_blank" class="btn-primary inline-flex items-center bg-accent-orange text-white font-bold py-3 px-8 sm:px-10 rounded-full shadow-xl transition duration-300 ease-in-out text-sm sm:text-base">
                    <!-- Icon WhatsApp -->
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.001 2.001C6.48 2.001 2 6.481 2 12.001c0 3.321 1.62 6.276 4.133 8.016l-1.002 3.999 4.167-1.12c1.688.46 3.496.71 5.003.71 5.52 0 10.001-4.48 10.001-10.001C22.002 6.481 17.522 2.001 12.001 2.001zm3.992 14.232c-.105.176-.38.214-.658.115-.815-.306-4.832-1.89-5.59-4.89-.092-.357.067-.577.223-.743.14-.15.286-.184.385-.297.098-.113.125-.262.247-.393.122-.132.245-.3.355-.453.111-.153.22-.395.035-.556-.184-.16-.484-.457-.663-.678-.18-.22-.387-.21-.617-.21-.24 0-.498.083-.757.083-.258 0-.64-.093-.974.908-.335 1-.418 2.016-.013 3.097.406 1.082 1.054 2.146 1.996 3.064 1.144 1.107 2.296 1.685 3.518 1.957 1.222.27 2.164.208 2.836.13.673-.078 1.143-.464 1.32-.741.178-.277.178-.512.124-.652-.054-.14-.187-.222-.375-.315z"></path></svg>
                    Pesan via WhatsApp
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-primary-brown py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center text-white/80">
            <div class="mb-4">
                <p class="text-lg font-semibold mb-2">Rabskubread</p>
                <p class="text-sm">Jaminan Roti Fresh, Kualitas Premium</p>
            </div>
            <div class="flex justify-center space-x-6 mb-4 text-sm sm:text-base">
                <a href="#" class="hover:text-accent-orange transition duration-300">Instagram</a>
                <a href="#" class="hover:text-accent-orange transition duration-300">Facebook</a>
                <a href="#kontak" class="hover:text-accent-orange transition duration-300">Kontak</a>
            </div>
            <p class="text-xs mt-6 text-white/60">&copy; 2025 Rabskubread. Dibuat dengan cinta di Indonesia.</p>
        </div>
    </footer>

    <!-- MODAL KERANJANG (Cart Modal) -->
    <div x-cloak x-show="isCartOpen" @click.outside="isCartOpen = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed inset-0 z-[100] flex justify-end">

        <!-- Overlay -->
        <div x-show="isCartOpen" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="isCartOpen = false"></div>
        
        <!-- Sidebar Keranjang (Responsif: max-w-xs di mobile, max-w-sm di tablet/desktop) -->
        <div class="relative w-full max-w-xs sm:max-w-sm bg-secondary-cream h-full shadow-2xl overflow-y-auto">
            
            <div class="sticky top-0 p-4 sm:p-6 bg-secondary-cream/95 backdrop-blur-sm border-b border-gray-200 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl sm:text-2xl font-bold text-primary-brown">Keranjang (<span x-text="cartCount()"></span>)</h2>
                    <button @click="isCartOpen = false" class="text-primary-brown hover:text-accent-orange p-1 rounded-full hover:bg-white transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Konten Keranjang -->
            <div class="p-4 sm:p-6">
                <template x-if="cart.length === 0">
                    <div class="text-center py-10 text-gray-500">
                        <svg class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-semibold text-base">Keranjang kosong. Yuk, belanja roti!</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <!-- Item Keranjang -->
                    <div class="flex items-start space-x-3 mb-4 p-3 sm:p-4 bg-white rounded-xl shadow-md border border-gray-100">
                        
                        <img :src="item.img" alt="item.name" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-lg flex-shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/100x100/CCCCCC/000000?text=Roti'">
                        
                        <div class="flex-grow">
                            <p class="font-bold text-sm sm:text-base text-primary-brown" x-text="item.name"></p>
                            <p class="text-sm text-accent-orange font-semibold" x-text="formatRupiah(item.price * item.quantity)"></p>
                            <p class="text-xs text-gray-500" x-text="formatRupiah(item.price) + ' / pcs'"></p>
                        </div>
                        
                        <!-- Kontrol Kuantitas dan Hapus -->
                        <div class="flex flex-col items-end space-y-2">
                            <button @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700 p-1 rounded-full transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <button @click="decrementQuantity(item.id)" class="text-gray-600 hover:text-primary-brown p-1 rounded-full bg-gray-100 hover:bg-gray-200">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </button>
                                <span x-text="item.quantity" class="font-medium w-3 sm:w-4 text-center text-sm"></span>
                                <button @click="incrementQuantity(item.id)" class="text-gray-600 hover:text-primary-brown p-1 rounded-full bg-gray-100 hover:bg-gray-200">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                    <!-- End Item Keranjang -->
                </template>
            </div>
            <!-- End Konten Keranjang -->

            <!-- Checkout Section (Sticky di bawah) -->
            <div x-show="cart.length > 0" class="sticky bottom-0 p-4 sm:p-6 bg-white shadow-top border-t-4 border-accent-orange/50">
                <div class="flex justify-between items-center mb-4 font-bold text-lg sm:text-xl">
                    <span>Total:</span>
                    <span class="text-accent-orange" x-text="formatRupiah(cartTotal())"></span>
                </div>
                
                <p class="text-xs sm:text-sm text-gray-500 mb-3 text-center">Pilih metode pembayaran:</p>
                
                <!-- Opsi 1: Checkout WhatsApp -->
                <button @click="checkoutWhatsApp()" class="w-full mb-3 bg-green-500 text-white font-bold py-3 rounded-full hover:bg-green-600 transition duration-300 flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.001 2.001C6.48 2.001 2 6.481 2 12.001c0 3.321 1.62 6.276 4.133 8.016l-1.002 3.999 4.167-1.12c1.688.46 3.496.71 5.003.71 5.52 0 10.001-4.48 10.001-10.001C22.002 6.481 17.522 2.001 12.001 2.001zM16.945 14.545c-.066.11-.205.13-.348.077-.492-.185-2.919-1.134-3.568-2.95-.058-.22-.058-.41 0-.61.056-.205.152-.35.25-.45.094-.094.195-.125.293-.242.097-.117.124-.22.18-.33.056-.11.026-.204-.03-.314-.056-.11-.37-.417-.506-.57-.136-.154-.234-.18-.33-.18s-.21.03-.32.03c-.11 0-.285-.02-.457.06-.172.083-.41.22-.59.41s-.295.4-.41.65-.21.56-.21.895c0 .334.205.67.205.67s.41.97.94 1.93c1.02 1.87 1.95 2.62 2.92 3.01.97.39 1.57.34 2.11.23.53-.11.66-.54.71-.85.05-.31.05-.56-.005-.65z" /></svg>
                    Checkout via WhatsApp
                </button>

                <!-- Opsi 2: Checkout Midtrans (Simulasi) -->
                <button @click="checkoutMidtrans()" class="w-full bg-primary-brown text-white font-bold py-3 rounded-full hover:bg-primary-brown/90 transition duration-300 flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Bayar via Midtrans (Simulasi)
                </button>
            </div>
            <!-- End Checkout Section -->

        </div>
    </div>
    <!-- END MODAL KERANJANG -->

</body>
</html>