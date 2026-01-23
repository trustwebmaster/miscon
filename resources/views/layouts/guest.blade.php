<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MISCON26') }} - Admin</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Animated Background -->
        <div class="fixed inset-0 bg-mesh -z-10"></div>
        
        <!-- Floating Particles -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-5">
            <div class="particle w-2 h-2 bg-miscon-gold top-[10%] left-[10%]"></div>
            <div class="particle w-3 h-3 bg-pcm-purple top-[20%] right-[15%] animation-delay-300"></div>
            <div class="particle w-2 h-2 bg-pcm-pink top-[40%] left-[5%] animation-delay-500"></div>
            <div class="particle w-4 h-4 bg-miscon-gold/30 top-[60%] right-[10%] animation-delay-200"></div>
            <div class="particle w-2 h-2 bg-pcm-blue top-[80%] left-[20%] animation-delay-400"></div>
            <div class="particle w-3 h-3 bg-miscon-gold/50 top-[70%] right-[25%] animation-delay-600"></div>
        </div>

        <!-- Morphing Shapes -->
        <div class="fixed top-20 left-10 w-72 h-72 bg-pcm-purple/20 morph-shape blur-[80px] -z-5"></div>
        <div class="fixed bottom-20 right-10 w-96 h-96 bg-pcm-pink/20 morph-shape blur-[100px] animation-delay-500 -z-5"></div>
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-miscon-gold/5 morph-shape blur-[120px] -z-5"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 relative">
            <!-- Logo -->
            <a href="/" wire:navigate class="group mb-8 animate-slide-down">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <svg viewBox="0 0 100 120" class="w-full h-full drop-shadow-lg">
                            <defs>
                                <linearGradient id="pcmGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6"/>
                                    <stop offset="50%" style="stop-color:#8b5cf6"/>
                                    <stop offset="100%" style="stop-color:#ec4899"/>
                                </linearGradient>
                            </defs>
                            <path d="M50 5 L95 20 L95 60 Q95 95 50 115 Q5 95 5 60 L5 20 Z" fill="url(#pcmGradient)" stroke="white" stroke-width="3"/>
                            <text x="50" y="45" text-anchor="middle" fill="white" font-family="Outfit" font-weight="bold" font-size="20">PCM</text>
                            <path d="M30 60 L50 55 L70 60 L70 80 L50 75 L30 80 Z" fill="none" stroke="white" stroke-width="2"/>
                            <line x1="50" y1="55" x2="50" y2="75" stroke="white" stroke-width="2"/>
                            <path d="M35 50 L50 42 L65 50 L50 58 Z" fill="white"/>
                        </svg>
                    </div>
                    <div class="text-center sm:text-left">
                        <span class="text-3xl font-bold tracking-tight">MISCON</span>
                        <span class="text-3xl font-bold text-miscon-gold">26</span>
                        <p class="text-sm text-white/60 mt-1">Admin Portal</p>
                    </div>
                </div>
            </a>

            <!-- Auth Card -->
            <div class="w-full sm:max-w-md animate-scale-in animation-delay-200">
                <div class="glass rounded-3xl p-8 sm:p-10 shadow-2xl border border-white/20 hover:border-miscon-gold/30 transition-all duration-500">
                    {{ $slot }}
                </div>
                
                <!-- Back to site link -->
                <div class="mt-6 text-center animate-fade-in animation-delay-400">
                    <a href="/" class="inline-flex items-center gap-2 text-white/60 hover:text-miscon-gold transition-colors group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="text-sm">Back to MISCON26</span>
                    </a>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-center text-white/30 text-xs animate-fade-in animation-delay-600">
                <p>&copy; {{ date('Y') }} PCM North Zimbabwe Conference</p>
            </div>
        </div>
    </body>
</html>
