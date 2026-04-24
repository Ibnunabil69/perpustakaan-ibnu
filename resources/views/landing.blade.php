<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PerpusKita - Sistem Perpustakaan Digital Modern</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons: Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Alpine.js for Mobile Menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .mesh-bg {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 40% 20%, hsla(38,100%,74%,0.15) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(25,100%,56%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355,100%,93%,0.1) 0px, transparent 50%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .nav-link {
            position: relative;
            padding-bottom: 0.25rem;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f59e0b; /* amber-500 */
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        .nav-link:hover::after,
        .nav-link.active-nav::after {
            width: 100%;
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased selection:bg-amber-200 selection:text-amber-900 overflow-x-hidden bg-slate-50">

    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false }" id="navbar" class="fixed w-full z-50 transition-all duration-300 glass-nav">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <a href="#" class="flex-shrink-0 flex items-center gap-2 sm:gap-3 cursor-pointer">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <i class="ri-book-3-line text-white text-base sm:text-xl"></i>
                    </div>
                    <span class="text-xl sm:text-2xl font-black tracking-tight text-dark-900">Perpus<span class="text-amber-500">Kita</span></span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#beranda" class="nav-link text-sm font-semibold text-slate-600 hover:text-amber-500 transition-colors">Beranda</a>
                    <a href="#fitur" class="nav-link text-sm font-semibold text-slate-600 hover:text-amber-500 transition-colors">Fitur</a>
                    <a href="#katalog" class="nav-link text-sm font-semibold text-slate-600 hover:text-amber-500 transition-colors">Katalog</a>
                    <a href="#panduan" class="nav-link text-sm font-semibold text-slate-600 hover:text-amber-500 transition-colors">Panduan</a>
                </div>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center">
                    <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-5 py-2 sm:px-6 sm:py-2.5 text-sm font-bold text-white transition-all duration-200 bg-dark-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-900 hover:bg-amber-500 hover:shadow-lg hover:shadow-amber-500/30">
                        Login
                        <i class="ri-arrow-right-line ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-dark-900 hover:text-amber-500 focus:outline-none p-2 rounded-lg bg-slate-100/50">
                        <i :class="mobileMenuOpen ? 'ri-close-line' : 'ri-menu-4-line'" class="text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-white border-b border-slate-200 shadow-xl absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a @click="mobileMenuOpen = false" href="#beranda" class="mobile-nav-link block px-3 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">Beranda</a>
                <a @click="mobileMenuOpen = false" href="#fitur" class="mobile-nav-link block px-3 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">Fitur</a>
                <a @click="mobileMenuOpen = false" href="#katalog" class="mobile-nav-link block px-3 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">Katalog</a>
                <a @click="mobileMenuOpen = false" href="#panduan" class="mobile-nav-link block px-3 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">Panduan</a>
                <div class="pt-4 mt-2 border-t border-slate-100">
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition-colors">
                        <i class="ri-login-box-line"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-24 pb-12 sm:pt-28 sm:pb-16 lg:pt-32 lg:pb-20 overflow-hidden mesh-bg min-h-[80vh] flex items-center">
        <!-- Animated Blobs -->
        <div class="absolute top-0 -left-4 w-48 sm:w-64 h-48 sm:h-64 bg-amber-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-48 sm:w-64 h-48 sm:h-64 bg-orange-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob" style="animation-delay: 2s"></div>
        <div class="absolute -bottom-8 left-20 w-48 sm:w-64 h-48 sm:h-64 bg-yellow-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob" style="animation-delay: 4s"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 relative z-10 w-full">
            <div class="text-center max-w-3xl mx-auto animate-fade-in-up mt-4 sm:mt-8">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-white/80 border border-amber-100 backdrop-blur-sm shadow-sm mb-5 sm:mb-6 mx-auto">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[10px] sm:text-xs font-bold text-amber-700 uppercase tracking-wider">Transformasi Digital Perpustakaan</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-dark-900 tracking-tight leading-[1.2] mb-4 sm:mb-6">
                    Eksplorasi Dunia Tanpa Batas Melalui <span class="bg-gradient-to-r from-amber-500 to-orange-500 text-gradient">Literasi Digital.</span>
                </h1>
                
                <p class="text-sm sm:text-base text-slate-600 mb-6 sm:mb-8 max-w-2xl mx-auto leading-relaxed px-4 sm:px-0">
                    PerpusKita hadir mengintegrasikan ekosistem perpustakaan fisik ke dalam platform digital interaktif. Cari, pinjam, dan kelola buku dengan sentuhan jari.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 px-4 sm:px-0">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-5 py-2.5 sm:px-6 sm:py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm sm:text-base transition-all duration-300 shadow-lg shadow-amber-500/30 hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="ri-rocket-line"></i> Mulai Membaca
                    </a>
                    <a href="#fitur" class="w-full sm:w-auto px-5 py-2.5 sm:px-6 sm:py-3 bg-white hover:bg-slate-50 text-dark-900 border border-slate-200 rounded-xl font-bold text-sm sm:text-base transition-all duration-300 shadow-sm flex items-center justify-center gap-2 group">
                        Pelajari Sistem <i class="ri-arrow-down-line group-hover:translate-y-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-8 sm:py-10 border-y border-slate-200 bg-white relative z-20">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 divide-x-0 sm:divide-x divide-y sm:divide-y-0 divide-slate-100">
                <div class="text-center px-4 py-2 sm:py-0">
                    <p class="text-3xl sm:text-4xl font-black text-dark-900 mb-1">5K+</p>
                    <p class="text-[10px] sm:text-sm font-semibold text-slate-500 uppercase tracking-wider">Koleksi Buku</p>
                </div>
                <div class="text-center px-4 py-2 sm:py-0 border-l border-slate-100 sm:border-none">
                    <p class="text-3xl sm:text-4xl font-black text-dark-900 mb-1">1.2K</p>
                    <p class="text-[10px] sm:text-sm font-semibold text-slate-500 uppercase tracking-wider">Siswa Aktif</p>
                </div>
                <div class="text-center px-4 py-4 sm:py-0 border-t border-slate-100 sm:border-none">
                    <p class="text-3xl sm:text-4xl font-black text-dark-900 mb-1">300+</p>
                    <p class="text-[10px] sm:text-sm font-semibold text-slate-500 uppercase tracking-wider">Peminjaman/Bulan</p>
                </div>
                <div class="text-center px-4 py-4 sm:py-0 border-t border-l border-slate-100 sm:border-none">
                    <p class="text-3xl sm:text-4xl font-black text-dark-900 mb-1">24/7</p>
                    <p class="text-[10px] sm:text-sm font-semibold text-slate-500 uppercase tracking-wider">Akses Digital</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-16 sm:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24">
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <h2 class="text-xs sm:text-sm font-bold text-amber-500 uppercase tracking-[0.2em] mb-2 sm:mb-3">Keunggulan Sistem</h2>
                <h3 class="text-2xl sm:text-3xl md:text-4xl font-black text-dark-900 mb-4 sm:mb-6">Mengapa Memilih PerpusKita?</h3>
                <p class="text-base sm:text-lg text-slate-600">Dirancang khusus untuk memenuhi kebutuhan literasi sekolah modern dengan fitur yang terintegrasi penuh.</p>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 card-hover relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-amber-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform"></div>
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-amber-100 text-amber-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-5 sm:mb-6">
                        <i class="ri-search-eye-line"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-dark-900 mb-2 sm:mb-3">Pencarian Cerdas</h4>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Temukan buku yang Anda butuhkan dalam hitungan detik dengan filter kategori dan pencarian real-time.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 card-hover relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-orange-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform"></div>
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-orange-100 text-orange-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-5 sm:mb-6">
                        <i class="ri-smartphone-line"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-dark-900 mb-2 sm:mb-3">Akses Multi-Device</h4>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Desain responsif yang sempurna diakses melalui smartphone, tablet, maupun komputer desktop.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 card-hover relative overflow-hidden group sm:col-span-2 md:col-span-1">
                    <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-yellow-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform"></div>
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-yellow-100 text-yellow-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-5 sm:mb-6">
                        <i class="ri-history-line"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-dark-900 mb-2 sm:mb-3">Tracking Real-time</h4>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Pantau status peminjaman, riwayat baca, dan denda keterlambatan langsung dari dashboard Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section id="katalog" class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24">
            <div class="flex flex-col md:flex-row justify-between items-center sm:items-end mb-10 sm:mb-12 gap-4 text-center sm:text-left">
                <div class="max-w-2xl">
                    <h2 class="text-xs sm:text-sm font-bold text-amber-500 uppercase tracking-[0.2em] mb-2 sm:mb-3">Katalog Terbaru</h2>
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-black text-dark-900">Jelajahi Koleksi Kami</h3>
                </div>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm sm:text-base text-amber-600 font-bold hover:text-amber-800 transition-colors group">
                    Lihat Semua Koleksi 
                    <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <!-- Dynamic Books Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @if(isset($books) && count($books) > 0)
                    @foreach($books as $book)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full cursor-pointer">
                        <!-- Cover Image -->
                        <div class="relative aspect-[3/4] sm:aspect-[4/5] overflow-hidden bg-slate-50">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-4">
                                    <i class="ri-book-2-line text-4xl mb-2"></i>
                                    <span class="text-[10px] sm:text-xs font-semibold text-center line-clamp-2">{{ $book->judul }}</span>
                                </div>
                            @endif
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-dark-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                <a href="{{ route('login') }}" class="bg-amber-500 hover:bg-amber-600 text-white text-[10px] sm:text-xs font-bold px-4 py-2 rounded-full transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-md uppercase tracking-wider mb-2 w-max">{{ $book->category->name ?? 'Kategori' }}</span>
                            <h4 class="text-sm sm:text-base font-bold text-dark-900 line-clamp-2 leading-snug mb-1 group-hover:text-amber-600 transition-colors">{{ $book->judul }}</h4>
                            <p class="text-[10px] sm:text-xs text-slate-500 line-clamp-1 mt-auto pt-3 border-t border-slate-100 flex items-center gap-1">
                                <i class="ri-user-line"></i> {{ $book->penulis ?? 'Penulis Tidak Diketahui' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Fallback if no books -->
                    @for($i=1; $i<=4; $i++)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm group flex flex-col h-full {{ $i > 2 ? 'hidden md:flex' : '' }}">
                        <div class="relative aspect-[3/4] sm:aspect-[4/5] bg-slate-50 flex flex-col items-center justify-center p-4 border-b border-slate-100">
                            <i class="ri-book-2-line text-3xl sm:text-4xl text-slate-300 mb-2"></i>
                        </div>
                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <div class="h-4 w-16 bg-slate-200 rounded-md mb-3"></div>
                            <div class="h-4 w-full bg-slate-200 rounded-md mb-2"></div>
                            <div class="h-4 w-2/3 bg-slate-200 rounded-md mb-4"></div>
                            <div class="mt-auto pt-3 border-t border-slate-100">
                                <div class="h-3 w-1/2 bg-slate-200 rounded-md"></div>
                            </div>
                        </div>
                    </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="panduan" class="py-16 sm:py-24 bg-dark-900 text-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-amber-500/10 rounded-full filter blur-[80px] sm:blur-[100px] -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-orange-500/10 rounded-full filter blur-[80px] sm:blur-[100px] translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <h2 class="text-xs sm:text-sm font-bold text-amber-400 uppercase tracking-[0.2em] mb-2 sm:mb-3">Panduan Sistem</h2>
                <h3 class="text-2xl sm:text-3xl md:text-4xl font-black mb-4 sm:mb-6">Alur Peminjaman Digital</h3>
                <p class="text-slate-400 text-sm sm:text-base">Proses peminjaman buku kini lebih cepat, transparan, dan dapat dipantau secara real-time.</p>
            </div>

            <div class="relative grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <!-- Garis Penghubung Global (Hanya Desktop) -->
                <div class="hidden md:block absolute top-14 left-[12.5%] right-[12.5%] border-t-2 border-dashed border-amber-500/40 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10">
                    <div class="bg-dark-800 border border-slate-700/50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 text-center h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-amber-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-black mx-auto mb-4 sm:mb-6 shadow-lg shadow-amber-500/20"><i class="ri-login-box-line"></i></div>
                        <h4 class="text-base sm:text-lg font-bold mb-2">Login Portal</h4>
                        <p class="text-xs sm:text-sm text-slate-400">Masuk menggunakan NISN & Password yang telah terdaftar di sekolah.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10">
                    <div class="bg-dark-800 border border-slate-700/50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 text-center h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-amber-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-black mx-auto mb-4 sm:mb-6 shadow-lg shadow-amber-500/20"><i class="ri-search-eye-line"></i></div>
                        <h4 class="text-base sm:text-lg font-bold mb-2">Pilih Buku</h4>
                        <p class="text-xs sm:text-sm text-slate-400">Cari buku melalui katalog digital dan pastikan stok tersedia.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10">
                    <div class="bg-dark-800 border border-slate-700/50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 text-center h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-amber-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-black mx-auto mb-4 sm:mb-6 shadow-lg shadow-amber-500/20"><i class="ri-hand-coin-line"></i></div>
                        <h4 class="text-base sm:text-lg font-bold mb-2">Ajukan Pinjam</h4>
                        <p class="text-xs sm:text-sm text-slate-400">Klik tombol pinjam, sistem akan memproses pengajuan Anda.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10">
                    <div class="bg-dark-800 border border-slate-700/50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 text-center h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-amber-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-black mx-auto mb-4 sm:mb-6 shadow-lg shadow-amber-500/20"><i class="ri-book-read-line"></i></div>
                        <h4 class="text-base sm:text-lg font-bold mb-2">Ambil & Baca</h4>
                        <p class="text-xs sm:text-sm text-slate-400">Tunjukkan bukti ke petugas dan bawa pulang buku Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 bg-amber-500 relative overflow-hidden">
        <div class="absolute inset-0" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px; opacity: 0.15;"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center text-white">
            <h2 class="text-2xl sm:text-3xl md:text-5xl font-black mb-4 sm:mb-6">Siap Memulai Perjalanan Literasi?</h2>
            <p class="text-amber-50 text-sm sm:text-lg mb-8 sm:mb-10 max-w-2xl mx-auto">Bergabunglah dengan ribuan siswa lainnya yang telah memanfaatkan kemudahan akses digital perpustakaan kami.</p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 text-base sm:text-lg font-bold text-amber-600 transition-all duration-200 bg-white rounded-xl sm:rounded-2xl hover:bg-slate-50 hover:shadow-xl hover:shadow-dark-900/10 active:scale-95 gap-2">
                Masuk ke Dashboard <i class="ri-arrow-right-circle-fill text-xl"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-12 sm:pt-16 pb-6 sm:pb-8 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 xl:px-24">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-10 sm:mb-12">
                <div class="text-center md:text-left">
                    <div class="flex items-center gap-2 sm:gap-3 justify-center md:justify-start mb-3 sm:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <i class="ri-book-3-line text-white text-base sm:text-xl"></i>
                        </div>
                        <span class="text-xl sm:text-2xl font-black tracking-tight text-dark-900">Perpus<span class="text-amber-500">Kita</span></span>
                    </div>
                    <p class="text-sm text-slate-500 max-w-xs mx-auto md:mx-0">Membangun ekosistem pendidikan digital melalui sistem manajemen perpustakaan yang terintegrasi dan modern.</p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-4 sm:gap-8">
                    <a href="#beranda" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-amber-500 transition-colors uppercase tracking-wider">Beranda</a>
                    <a href="#fitur" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-amber-500 transition-colors uppercase tracking-wider">Fitur</a>
                    <a href="#katalog" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-amber-500 transition-colors uppercase tracking-wider">Katalog</a>
                    <a href="#panduan" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-amber-500 transition-colors uppercase tracking-wider">Panduan</a>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-6 sm:pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs sm:text-sm text-slate-500 font-medium">&copy; {{ date('Y') }} PerpusKita Ecosystem. All rights reserved.</p>
                <div class="flex gap-3 sm:gap-4">
                    <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-amber-50 hover:text-amber-500 transition-colors">
                        <i class="ri-instagram-line text-lg sm:text-xl"></i>
                    </a>
                    <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-amber-50 hover:text-amber-500 transition-colors">
                        <i class="ri-facebook-circle-fill text-lg sm:text-xl"></i>
                    </a>
                    <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-amber-50 hover:text-amber-500 transition-colors">
                        <i class="ri-twitter-x-line text-lg sm:text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script for Navbar scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbar = document.getElementById('navbar');
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                
                // Navbar shadow
                if (window.scrollY > 20) {
                    navbar.classList.add('shadow-sm');
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                } else {
                    navbar.classList.remove('shadow-sm');
                    navbar.style.background = 'rgba(255, 255, 255, 0.85)';
                }

                // Active section highlight
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    // Detect if scroll position is within section boundaries
                    if (scrollY >= (sectionTop - sectionHeight / 3)) {
                        current = section.getAttribute('id');
                    }
                });

                // Update desktop nav links
                navLinks.forEach(link => {
                    link.classList.remove('text-amber-500', 'active-nav');
                    link.classList.add('text-slate-600');
                    if (link.getAttribute('href').substring(1) === current) {
                        link.classList.add('text-amber-500', 'active-nav');
                        link.classList.remove('text-slate-600');
                    }
                });

                // Update mobile nav links
                mobileNavLinks.forEach(link => {
                    link.classList.remove('bg-amber-50', 'text-amber-600');
                    link.classList.add('text-slate-700');
                    if (link.getAttribute('href').substring(1) === current) {
                        link.classList.add('bg-amber-50', 'text-amber-600');
                        link.classList.remove('text-slate-700');
                    }
                });
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>
