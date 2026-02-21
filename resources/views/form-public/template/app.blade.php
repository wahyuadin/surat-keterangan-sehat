<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucwords(config('app.name')) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @stack('style-form')
</head>
<body class="pb-20 bg-[#f0f2f5]">

    <div class="h-3 w-full fixed top-0 z-50 bg-brand-green shadow-sm"></div>

    <header class="pt-8 pb-4 px-4 max-w-2xl mx-auto">
        <div class="flex items-center gap-4 pl-1">
            <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center p-1 shrink-0">
                <img src="{{ asset('assets/img/icons/brands/default.png') }}" alt="https://www.nayakaerahusada.com/assets/icon.png" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-brand-blue tracking-tight leading-tight">KLINIK {{ strtoupper(trim($__env->yieldContent('nama-klinik'))) }}</h2>
                <span class="text-xs font-medium text-brand-green tracking-wider uppercase">PT. Nayaka Era Husada</span>
            </div>
        </div>
    </header>
    <div class="max-w-2xl mx-auto px-4">

        <div class="question-card bg-white rounded-xl shadow-card border-t-[12px] border-brand-blue mb-5 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-brand-yellow/30 to-transparent rounded-bl-full -mr-6 -mt-6 z-0 pointer-events-none"></div>

            <div class="p-6 sm:p-8 relative z-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-3 tracking-tight">@yield('header-form')</h1>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                    @yield('description-form')
                </p>
                <div class="mt-5 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 font-medium">
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-brand-green">
                            <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                            <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>
                        @yield('email-form')
                    </span>
                </div>
            </div>
        </div>
        @yield('content-form')

        <div class="mt-12 text-center pb-8 border-t border-gray-200/70 pt-6">
            <p class="text-sm text-gray-500 font-medium">
                © {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.
            </p>
        </div>
    </div>

    @stack('scripts-form')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

</body>
</html>
