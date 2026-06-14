<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>66 Garage — Bengkel Motor Profesional</title>
    <meta name="description" content="66 Garage adalah bengkel motor profesional dengan teknisi berpengalaman, sistem antrian digital, dan estimasi waktu yang akurat. Servis terpercaya, tepat waktu.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Gradient background */
        .hero-bg {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 40%, #1f1205 70%, #0f0f0f 100%);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(90deg, #ef4444, #f97316, #ef4444);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }

        @keyframes shine {
            to { background-position: 200% center; }
        }

        /* Glow button */
        .btn-glow {
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 35px rgba(239, 68, 68, 0.7);
            transform: translateY(-2px);
        }

        /* Card hover */
        .service-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.07);
        }
        .service-card:hover {
            transform: translateY(-5px);
            border-color: rgba(239, 68, 68, 0.4);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* Step card */
        .step-card {
            position: relative;
        }
        .step-number {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 20px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }

        /* Animated stat */
        .stat-item {
            border-left: 3px solid #ef4444;
        }

        /* Noise texture overlay */
        .noise::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            opacity: 0.03;
        }

        .nav-blur {
            backdrop-filter: blur(12px);
            background: rgba(15, 15, 15, 0.85);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        /* Fade in up animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeInUp 0.8s ease forwards;
        }
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        .delay-500 { animation-delay: 0.5s; opacity: 0; }
        .delay-600 { animation-delay: 0.6s; opacity: 0; }
    </style>
</head>
<body class="antialiased bg-gray-950 text-white">

<!-- ==================== NAVBAR ==================== -->
<nav class="nav-blur fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 group">
            <div class="w-9 h-9 bg-red-600 rounded-lg flex items-center justify-center font-black text-white text-base shadow-lg group-hover:bg-red-500 transition">
                66
            </div>
            <span class="font-black text-lg tracking-tight text-white">
                66 <span class="text-red-500">Garage</span>
            </span>
        </a>

        <!-- Nav Links -->
        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-400">
            <a href="#layanan" class="hover:text-white transition">Layanan</a>
            <a href="#cara-kerja" class="hover:text-white transition">Cara Kerja</a>
            <a href="#kontak" class="hover:text-white transition">Kontak</a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition btn-glow">
                    Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition">
                    Masuk
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition btn-glow">
                        Daftar
                    </a>
                @endif
            @endauth
        </div>
    </div>
</nav>

<!-- ==================== HERO ==================== -->
<section class="hero-bg noise relative min-h-screen flex items-center justify-center overflow-hidden pt-16">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-orange-600/8 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-red-900/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <!-- Badge -->
        <div class="animate-fade-up delay-100 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-950/60 border border-red-800/50 text-red-400 text-xs font-semibold tracking-widest uppercase mb-8">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            Bengkel Motor Profesional — Sejak 2015
        </div>

        <!-- Heading -->
        <h1 class="animate-fade-up delay-200 text-5xl md:text-7xl font-black leading-tight mb-6 tracking-tight">
            Servis Motor di<br>
            <span class="gradient-text">66 Garage</span>
        </h1>

        <!-- Subheading -->
        <p class="animate-fade-up delay-300 text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
            Teknisi berpengalaman, sistem antrian digital, estimasi waktu akurat.
            <strong class="text-gray-200">Motor selesai tepat waktu, dijamin.</strong>
        </p>

        <!-- CTA Buttons -->
        <div class="animate-fade-up delay-400 flex flex-col sm:flex-row gap-4 justify-center mb-16">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black text-base rounded-xl transition btn-glow">
                    Buka Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black text-base rounded-xl transition btn-glow">
                    Booking Sekarang →
                </a>
                <a href="#layanan"
                   class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-base rounded-xl transition">
                    Lihat Layanan
                </a>
            @endauth
        </div>

        <!-- Stats -->
        <div class="animate-fade-up delay-500 grid grid-cols-3 gap-6 max-w-lg mx-auto">
            <div class="stat-item pl-4">
                <div class="text-2xl font-black text-white">500+</div>
                <div class="text-xs text-gray-500 mt-0.5">Motor / Bulan</div>
            </div>
            <div class="stat-item pl-4">
                <div class="text-2xl font-black text-white">95%</div>
                <div class="text-xs text-gray-500 mt-0.5">Tepat Waktu</div>
            </div>
            <div class="stat-item pl-4">
                <div class="text-2xl font-black text-white">10+</div>
                <div class="text-xs text-gray-500 mt-0.5">Jenis Servis</div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-gray-600 animate-bounce">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

<!-- ==================== LAYANAN ==================== -->
<section id="layanan" class="py-24 bg-gray-950">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <p class="text-red-500 text-xs font-bold uppercase tracking-widest mb-3">Apa yang Kami Tawarkan</p>
            <h2 class="text-4xl font-black text-white mb-4">Daftar Layanan Kami</h2>
            <p class="text-gray-400 max-w-xl mx-auto">Semua layanan dikerjakan oleh teknisi bersertifikat dengan peralatan modern.</p>
        </div>

        <!-- Service Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $icons = ['🔧', '⚙️', '💉', '🛞', '🔩', '🛠️', '💡', '🔋', '🚿'];
                $i = 0;
            @endphp
            @foreach (\App\Models\Service::all() as $service)
                <div class="service-card bg-gray-900 rounded-2xl p-6 cursor-default">
                    <div class="text-3xl mb-4">{{ $icons[$i % count($icons)] }}</div>
                    <h3 class="font-bold text-white text-lg mb-2">{{ $service->name }}</h3>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/5">
                        <span class="text-xs text-gray-500 bg-gray-800 px-3 py-1 rounded-full">
                            ⏱ {{ $service->estimated_duration }} menit
                        </span>
                        <span class="text-green-400 font-black text-base">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @php $i++; @endphp
            @endforeach
        </div>

        @if (\App\Models\Service::count() === 0)
            <div class="text-center py-12 text-gray-600 italic">
                Layanan sedang dipersiapkan. Hubungi kami untuk informasi lebih lanjut.
            </div>
        @endif
    </div>
</section>

<!-- ==================== CARA KERJA ==================== -->
<section id="cara-kerja" class="py-24 bg-gray-900/40">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-red-500 text-xs font-bold uppercase tracking-widest mb-3">Mudah & Cepat</p>
            <h2 class="text-4xl font-black text-white mb-4">Cara Kerja Kami</h2>
            <p class="text-gray-400 max-w-lg mx-auto">Proses booking dan servis yang transparan dari awal hingga selesai.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="step-card text-center">
                <div class="step-number mx-auto mb-5">1</div>
                <h3 class="font-bold text-white text-xl mb-3">Datang & Daftar</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Datang ke bengkel, kasir mendaftarkan motor kamu ke sistem antrian digital. Langsung tahu estimasi waktu selesai.
                </p>
            </div>

            <!-- Divider -->
            <div class="hidden md:flex items-center justify-center opacity-20">
                <div class="w-full h-px bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
            </div>

            <!-- Step 2 -->
            <div class="step-card text-center">
                <div class="step-number mx-auto mb-5">2</div>
                <h3 class="font-bold text-white text-xl mb-3">Mekanik Bekerja</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Mekanik mengerjakan motor sesuai antrian. Setiap tahap dicatat secara real-time di sistem untuk ketepatan waktu.
                </p>
            </div>

            <!-- Divider -->
            <div class="hidden md:flex items-center justify-center opacity-20">
                <div class="w-full h-px bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
            </div>

            <!-- Step 3 -->
            <div class="step-card text-center">
                <div class="step-number mx-auto mb-5">3</div>
                <h3 class="font-bold text-white text-xl mb-3">Ambil Motor</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Motor selesai sesuai estimasi. Bayar dan bawa pulang motor kamu yang sudah prima!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA BANNER ==================== -->
<section class="py-20 bg-gradient-to-r from-red-900 via-red-800 to-red-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/30"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-4">
            Siap Servis Motor Kamu?
        </h2>
        <p class="text-red-200 text-lg mb-8 max-w-xl mx-auto">
            Jangan biarkan motor bermasalah mengganggu aktivitasmu. Kunjungi 66 Garage sekarang!
        </p>
        @auth
            <a href="{{ url('/dashboard') }}"
               class="inline-block px-10 py-4 bg-white text-red-700 font-black text-base rounded-xl hover:bg-gray-100 transition shadow-lg">
                Buka Dashboard →
            </a>
        @else
            <a href="{{ route('login') }}"
               class="inline-block px-10 py-4 bg-white text-red-700 font-black text-base rounded-xl hover:bg-gray-100 transition shadow-lg">
                Login Sekarang →
            </a>
        @endauth
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer id="kontak" class="bg-gray-950 border-t border-white/5 py-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
            <!-- Brand -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 bg-red-600 rounded-lg flex items-center justify-center font-black text-white text-base">
                        66
                    </div>
                    <span class="font-black text-lg text-white">66 <span class="text-red-500">Garage</span></span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Bengkel motor profesional dengan teknisi berpengalaman dan sistem manajemen digital.
                </p>
            </div>

            <!-- Layanan -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wide mb-4">Layanan Utama</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li>🔧 Ganti Oli & Cek Rutin</li>
                    <li>⚙️ Servis CVT & Mesin</li>
                    <li>💉 Servis Injeksi / Karburator</li>
                    <li>🛞 Ganti Ban & Rantai</li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wide mb-4">Kontak Kami</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li>📍 Jl. Raya Bengkel No. 66</li>
                    <li>📞 (021) 6666-6666</li>
                    <li>🕐 Senin–Sabtu: 08.00–17.00 WIB</li>
                    <li>📧 info@66garage.com</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/5 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-600">
            <span>© {{ date('Y') }} 66 Garage. All rights reserved.</span>
            <span>Built with Laravel & Livewire</span>
        </div>
    </div>
</footer>

</body>
</html>
