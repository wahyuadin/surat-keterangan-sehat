<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Nayaka Husada</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Confetti Library for Celebration Effect -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
                    , }
                    , colors: {
                        brand: {
                            green: '#28b531'
                            , greenLight: '#ecfdf0'
                            , yellow: '#f3ab01'
                            , blue: '#070782'
                            , blueDark: '#040455'
                        , }
                    }
                    , animation: {
                        'bounce-slow': 'bounce 3s infinite'
                        , 'fade-in-up': 'fadeInUp 0.8s ease-out forwards'
                        , 'scale-in': 'scaleIn 0.5s ease-out forwards'
                    , }
                    , keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: '0'
                                , transform: 'translateY(20px)'
                            }
                            , '100%': {
                                opacity: '1'
                                , transform: 'translateY(0)'
                            }
                        , }
                        , scaleIn: {
                            '0%': {
                                transform: 'scale(0.8)'
                                , opacity: '0'
                            }
                            , '100%': {
                                transform: 'scale(1)'
                                , opacity: '1'
                            }
                        , }
                    }
                }
            }
        }

    </script>

    <style>
        body {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Custom Checkmark Animation */
        .checkmark-circle {
            width: 80px;
            height: 80px;
            position: relative;
            display: inline-block;
            vertical-align: top;
            border-radius: 50%;
            background-color: #dcfce7;
            /* green-100 */
        }

        .checkmark-circle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #28b531;
            opacity: 0.2;
            animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }

    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 selection:bg-brand-green selection:text-white relative overflow-hidden">
    <!-- Decorative Blobs Background -->
    <div class="fixed top-0 left-0 w-64 h-64 bg-brand-green/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 bg-brand-blue/10 rounded-full blur-3xl translate-x-1/3 translate-y-1/3 pointer-events-none"></div>

    <!-- Main Content -->
    <main class="w-full max-w-lg relative z-10">

        <!-- Top Branding (Centered) -->
        <div class="text-center mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg shadow-brand-blue/10 border border-slate-100 mb-4">
                <img src="{{ asset('assets/img/icons/brands/default.png') }}" alt="Nayaka Husada Logo" class="w-10 h-10 object-contain">
            </div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">KLINIK NAYAKA HUSADA</h2>
            <p class="text-brand-green font-medium text-sm tracking-widest uppercase mt-1">PT. Nayaka Era Husada</p>
        </div>

        <!-- Success Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-10 text-center animate-fade-in-up relative overflow-hidden" style="animation-delay: 0.2s;">
            <!-- Top Accent Bar -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-brand-blue via-brand-green to-brand-yellow"></div>

            <!-- Animated Checkmark Icon -->
            <div class="mb-6 flex justify-center">
                <div class="checkmark-circle flex items-center justify-center">
                    <i data-lucide="check" class="w-10 h-10 text-brand-green stroke-[3px]"></i>
                </div>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-slate-500 text-lg leading-relaxed mb-8">
                Data pemeriksaan Anda telah berhasil tersimpan dalam sistem kami.
            </p>

            <!-- Action Box / Next Steps -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8 text-left transition-transform hover:scale-[1.02] duration-300">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-blue/10 flex items-center justify-center mt-1">
                        <i data-lucide="stethoscope" class="w-5 h-5 text-brand-blue"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-1">Langkah Selanjutnya</h3>
                        <p class="text-sm text-slate-600 leading-snug">
                            Silakan langsung menuju ke bagian <span class="font-semibold text-brand-blue">Pelayanan / Perawat</span> untuk pemeriksaan lebih lanjut.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Additional Details Grid (Optional Context) -->
            <div class="grid grid-cols-1 gap-3 text-xs text-slate-400 mb-8 border-t border-slate-100 pt-6">
                <div class="flex items-center justify-center gap-2">
                    <i data-lucide="mail" class="w-3 h-3"></i>
                    <span>pusat@nayakaerahusada.com</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <a href={{ url('/form') . '/' . $slug }} class="w-full py-3.5 px-6 rounded-xl bg-brand-blue text-white font-semibold shadow-lg shadow-brand-blue/20 hover:bg-brand-blueDark hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                    <span>Buat Pendaftaran Baru</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center animate-fade-in-up" style="animation-delay: 0.3s;">
            <p class="text-xs text-slate-400 font-medium">
                &copy; <script>
                    document.write(new Date().getFullYear())

                </script> Nayaka Era Husada. All rights reserved.
            </p>
        </footer>

    </main>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Confetti Celebration Logic
        window.addEventListener('load', () => {
            const duration = 3000;
            const animationEnd = Date.now() + duration;
            const defaults = {
                startVelocity: 30
                , spread: 360
                , ticks: 60
                , zIndex: 0
            };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);

                // Confetti from left and right corners
                confetti(Object.assign({}, defaults, {
                    particleCount
                    , origin: {
                        x: randomInRange(0.1, 0.3)
                        , y: Math.random() - 0.2
                    }
                    , colors: ['#28b531', '#f3ab01', '#070782'] // Brand colors
                }));
                confetti(Object.assign({}, defaults, {
                    particleCount
                    , origin: {
                        x: randomInRange(0.7, 0.9)
                        , y: Math.random() - 0.2
                    }
                    , colors: ['#28b531', '#f3ab01', '#070782']
                }));
            }, 250);
        });

    </script>
</body>
</html>
