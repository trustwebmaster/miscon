<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MISCON26 - Watchmen On The Wall. A transformative student conference by PCM.">
    <title>MISCON26 | Watchmen On The Wall</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased overflow-x-hidden" x-data="mainApp()" @scroll.window="handleScroll()">

    <!-- Animated Background -->
    <div class="fixed inset-0 bg-mesh noise-overlay -z-10"></div>

    <!-- Floating Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-5" id="particles">
        <div class="particle w-2 h-2 bg-miscon-gold top-[10%] left-[10%]"></div>
        <div class="particle w-3 h-3 bg-pcm-purple top-[20%] right-[15%] animation-delay-300"></div>
        <div class="particle w-2 h-2 bg-pcm-pink top-[40%] left-[5%] animation-delay-500"></div>
        <div class="particle w-4 h-4 bg-miscon-gold/30 top-[60%] right-[10%] animation-delay-200"></div>
        <div class="particle w-2 h-2 bg-pcm-blue top-[80%] left-[20%] animation-delay-400"></div>
        <div class="particle w-3 h-3 bg-miscon-gold/50 top-[70%] right-[25%] animation-delay-600"></div>
        <div class="particle w-2 h-2 bg-pcm-purple/50 top-[30%] left-[80%] animation-delay-100"></div>
        <div class="particle w-3 h-3 bg-pcm-pink/40 top-[50%] left-[50%] animation-delay-700"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500" :class="scrolled ? 'glass-dark py-3' : 'py-6'">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <a href="#" class="flex items-center gap-3 group">
                    <div class="relative w-12 h-14 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <img src="{{ asset('images/logo.png') }}" alt="PCM Logo" class="w-full h-full object-contain drop-shadow-lg">
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-xl font-bold tracking-tight">MISCON</span>
                        <span class="text-xl font-bold text-miscon-gold">26</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" class="nav-link text-sm font-medium uppercase tracking-wider">About</a>
                    <a href="#speakers" class="nav-link text-sm font-medium uppercase tracking-wider">Speakers</a>
                    <a href="#gallery" class="nav-link text-sm font-medium uppercase tracking-wider">Gallery</a>
                    <a href="#schedule" class="nav-link text-sm font-medium uppercase tracking-wider">Schedule</a>
                    <button @click="$dispatch('open-status-modal')" class="nav-link text-sm font-medium uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Check Status
                    </button>
                    <a href="#registration" class="btn-primary text-sm font-semibold uppercase tracking-wider magnetic-btn">
                        <span class="relative z-10">Register</span>
                    </a>
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 flex items-center justify-center">
                    <div class="w-6 flex flex-col gap-1.5 transition-all" :class="mobileMenuOpen ? 'gap-0' : ''">
                        <span class="w-full h-0.5 bg-white transition-all origin-center" :class="mobileMenuOpen ? 'rotate-45 translate-y-0.5' : ''"></span>
                        <span class="w-full h-0.5 bg-white transition-all" :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                        <span class="w-full h-0.5 bg-white transition-all origin-center" :class="mobileMenuOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" class="md:hidden glass-dark mt-4 mx-6 rounded-2xl p-6" x-cloak>
            <div class="flex flex-col gap-4">
                <a href="#about" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10 hover:text-miscon-gold transition-colors">About</a>
                <a href="#speakers" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10 hover:text-miscon-gold transition-colors">Speakers</a>
                <a href="#gallery" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10 hover:text-miscon-gold transition-colors">Gallery</a>
                <a href="#schedule" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10 hover:text-miscon-gold transition-colors">Schedule</a>
                <button @click="mobileMenuOpen = false; $dispatch('open-status-modal')" class="text-lg font-medium py-2 border-b border-white/10 hover:text-miscon-gold transition-colors text-left flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Check Status
                </button>
                <a href="#registration" @click="mobileMenuOpen = false" class="btn-primary text-center mt-2">Register Now</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        <div class="absolute inset-0 hero-gradient"></div>

        <!-- Morphing Shapes -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-pcm-purple/20 morph-shape blur-[80px]"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-pcm-pink/20 morph-shape blur-[100px] animation-delay-500"></div>

        <!-- Spinning Circles -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-miscon-gold/10 rounded-full animate-spin-slow"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-pcm-purple/10 rounded-full animate-spin-slow" style="animation-direction: reverse; animation-duration: 25s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-pcm-pink/10 rounded-full animate-spin-slow" style="animation-duration: 30s;"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <div class="animate-slide-down">
                    <span class="inline-block px-6 py-2 rounded-full glass text-sm font-medium tracking-widest uppercase mb-8 hover:border-miscon-gold/50 transition-all cursor-default">
                        ✨ North Zimbabwe Conference Presents ✨
                    </span>
                </div>

                <h1 class="animate-scale-in animation-delay-200">
                    <span class="block text-6xl sm:text-7xl md:text-8xl lg:text-9xl font-black tracking-tight">
                        MISCON<span class="gradient-text inline-block hover:scale-110 transition-transform cursor-default">26</span>
                    </span>
                </h1>

                <div class="mt-8 animate-slide-up animation-delay-400">
                    <h2 class="font-script text-4xl sm:text-5xl md:text-6xl text-miscon-gold text-shadow">
                        Watchmen
                    </h2>
                    <p class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.3em] uppercase mt-2">
                        On The <span class="font-bold text-miscon-gold hover:text-yellow-300 transition-colors">Wall</span>
                    </p>
                </div>

                <div class="mt-12 flex flex-wrap justify-center gap-6 animate-fade-in animation-delay-600">
                    <div class="flex items-center gap-2 text-white/80 hover:text-white transition-colors cursor-default group">
                        <svg class="w-5 h-5 text-miscon-gold group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">April 2-6, 2026</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/80 hover:text-white transition-colors cursor-default group">
                        <svg class="w-5 h-5 text-miscon-gold group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium">Amai Mugabe Group of Schools</span>
                    </div>
                </div>

                <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up animation-delay-800">
                    <a href="#registration" class="btn-primary group ripple-effect">
                        <span class="relative z-10 flex items-center gap-2">
                            Register Now
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </a>
                    <a href="#gallery" class="btn-outline group">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            View Gallery
                        </span>
                    </a>
                </div>

                <!-- Countdown Timer -->
                <div class="mt-16 animate-fade-in animation-delay-1000" x-data="countdown()">
                    <p class="text-sm uppercase tracking-widest text-white/60 mb-6">Event Starts In</p>
                    <div class="flex justify-center gap-4 sm:gap-6">
                        <div class="countdown-box hover-lift">
                            <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="days">00</span>
                            <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Days</p>
                        </div>
                        <div class="countdown-box hover-lift">
                            <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="hours">00</span>
                            <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Hours</p>
                        </div>
                        <div class="countdown-box hover-lift">
                            <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="minutes">00</span>
                            <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Minutes</p>
                        </div>
                        <div class="countdown-box hover-lift">
                            <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="seconds">00</span>
                            <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Seconds</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 scroll-indicator">
            <a href="#about" class="flex flex-col items-center gap-2 text-white/60 hover:text-white transition-colors group">
                <span class="text-xs uppercase tracking-widest">Scroll</span>
                <svg class="w-6 h-6 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Marquee Banner -->
    <div class="py-4 bg-gradient-to-r from-miscon-navy via-pcm-purple/20 to-miscon-navy border-y border-white/10">
        <div class="marquee-container">
            <div class="marquee-content">
                <span class="inline-flex items-center gap-8 text-white/60 text-sm tracking-wider">
                    <span>🙏 WATCHMEN ON THE WALL</span>
                    <span>•</span>
                    <span>📅 APRIL 2-6, 2026</span>
                    <span>•</span>
                    <span>📍 AMAI MUGABE GROUP OF SCHOOLS</span>
                    <span>•</span>
                    <span>🎓 PUBLIC CAMPUS MINISTRIES</span>
                    <span>•</span>
                    <span>🙏 WATCHMEN ON THE WALL</span>
                    <span>•</span>
                    <span>📅 APRIL 2-6, 2026</span>
                    <span>•</span>
                    <span>📍 AMAI MUGABE GROUP OF SCHOOLS</span>
                    <span>•</span>
                    <span>🎓 PUBLIC CAMPUS MINISTRIES</span>
                    <span>•</span>
                </span>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="about" class="py-24 relative">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="reveal">
                        <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">About The Event</span>
                        <h2 class="text-4xl sm:text-5xl font-bold leading-tight mb-6">
                            Rise As <span class="gradient-text">Watchmen</span><br>In Your Generation
                        </h2>
                        <p class="text-lg text-white/70 leading-relaxed mb-6">
                            MISCON26 is the premier  conference organized by <strong class="text-white">Public Campus Ministries (PCM)</strong> under the North Zimbabwe Conference of the Seventh-day Adventist Church.
                        </p>
                        <p class="text-lg text-white/70 leading-relaxed mb-8">
                            This year's theme, <strong class="text-miscon-gold">"Watchmen On The Wall"</strong>, calls upon young people to stand as spiritual guardians in their campuses, communities, and nations.
                        </p>

                        <!-- Animated Stats -->
                        <div class="grid grid-cols-3 gap-6">
                            <div class="text-center hover-lift cursor-default" x-data="{ count: 0 }" x-intersect="animateCounter($el, 4, 0)">
                                <span class="stat-number" x-text="count">0</span>
                                <span class="block text-sm text-white/60">Days</span>
                            </div>
                            <div class="text-center hover-lift cursor-default" x-data="{ count: 0 }" x-intersect="animateCounter($el, 6, 100)">
                                <span class="stat-number" x-text="count + '+'">0+</span>
                                <span class="block text-sm text-white/60">Speakers</span>
                            </div>
                            <div class="text-center hover-lift cursor-default" x-data="{ count: 0 }" x-intersect="animateCounter($el, 1000, 200)">
                                <span class="stat-number" x-text="count + '+'">0+</span>
                                <span class="block text-sm text-white/60">Expected</span>
                            </div>
                        </div>
                    </div>

                    <div class="reveal-right relative perspective-1000">
                        <div class="relative rounded-3xl overflow-hidden glow-pcm tilt-card" @mousemove="tiltCard($event, $el)" @mouseleave="resetTilt($el)">
                            <div class="aspect-[4/5] bg-gradient-to-br from-pcm-blue via-pcm-purple to-pcm-pink p-1 rounded-3xl">
                                <div class="w-full h-full bg-miscon-navy rounded-3xl flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('images/kabani.jpeg') }}" alt="MISCON Event" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 glass rounded-2xl p-4 animate-float z-20">
                            <p class="text-sm font-medium text-miscon-gold">Main Speaker</p>
                            <p class="text-xl font-bold">Dr. Kabani</p>
                        </div>
                        <div class="absolute -top-8 -left-8 w-24 h-24 border-2 border-miscon-gold/30 rounded-full float-element-delayed"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Speakers Section -->
    <section id="speakers" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-pcm-purple/10 rounded-full blur-[150px]"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-pcm-pink/10 rounded-full blur-[150px]"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">Meet Our Speakers</span>
                <h2 class="text-4xl sm:text-5xl font-bold">Anointed <span class="gradient-text">Voices</span></h2>
                <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">Learn from experienced ministers who will share powerful messages.</p>
            </div>

            <!-- Main Speaker -->
            <div class="max-w-4xl mx-auto mb-16 reveal">
                <div class="glass rounded-3xl p-8 relative overflow-hidden hover:border-miscon-gold/30 transition-all duration-500 hover:shadow-[0_0_60px_rgba(212,175,55,0.15)]">
                    <div class="absolute inset-0 bg-gradient-to-r from-pcm-blue/10 via-pcm-purple/10 to-pcm-pink/10 opacity-0 hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative flex flex-col md:flex-row items-center gap-8">
                        <div class="w-48 h-48 md:w-56 md:h-56 rounded-2xl overflow-hidden ring-4 ring-miscon-gold/30 hover:ring-miscon-gold/60 transition-all">
                            <img src="{{ asset('images/kabani.jpeg') }}" alt="Dr. Kabani" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="text-center md:text-left flex-1">
                            <span class="inline-block px-3 py-1 rounded-full bg-miscon-gold text-miscon-navy text-xs font-bold uppercase tracking-wider mb-3 animate-pulse">Main Speaker</span>
                            <h3 class="text-3xl sm:text-4xl font-bold mb-2">Dr. Kabani</h3>
                            <p class="text-white/60 mb-4">Dynamic preacher whose messages transform lives and ignite revival.</p>
                            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                                <span class="px-3 py-1 rounded-full glass text-sm hover:bg-white/10 transition-colors">Keynote Sessions</span>
                                <span class="px-3 py-1 rounded-full glass text-sm hover:bg-white/10 transition-colors">Evening Devotionals</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Speakers - Modern Hexagonal Design -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 stagger-children" id="speakersGrid">
                @foreach([
                    ['name' => 'Dr. N. Matinhira', 'initials' => 'NM', 'gradient' => 'from-pcm-blue via-pcm-purple to-pcm-blue', 'role' => 'Evangelist', 'topic' => 'Faith & Purpose'],
                    ['name' => 'Elder Chenge Matondo', 'initials' => 'CM', 'gradient' => 'from-pcm-purple via-pcm-pink to-pcm-purple', 'role' => 'Pastor', 'topic' => 'Youth Ministry'],
                    ['name' => 'Elder M. Machando', 'initials' => 'MM', 'gradient' => 'from-pcm-pink via-miscon-gold to-pcm-pink', 'role' => 'Teacher', 'topic' => 'Biblical Studies'],
                    ['name' => 'Dr. Matanda', 'initials' => 'DM', 'gradient' => 'from-miscon-gold via-pcm-blue to-miscon-gold', 'role' => 'Minister', 'topic' => 'Spiritual Growth'],
                    ['name' => 'Dr. Mapondera', 'initials' => 'DM', 'gradient' => 'from-pcm-blue via-miscon-gold to-pcm-blue', 'role' => 'Elder', 'topic' => 'Leadership'],
                ] as $index => $speaker)
                <div class="group relative">
                    <!-- Glow Effect -->
                    <div class="absolute -inset-1 bg-gradient-to-r {{ $speaker['gradient'] }} rounded-3xl blur-xl opacity-0 group-hover:opacity-30 transition-all duration-700"></div>

                    <!-- Card -->
                    <div class="relative glass rounded-3xl p-8 border border-white/10 group-hover:border-white/20 transition-all duration-500 overflow-hidden h-full">
                        <!-- Animated Background Pattern -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $speaker['gradient'] }} rounded-full blur-3xl opacity-20 transform translate-x-16 -translate-y-16 group-hover:translate-x-8 group-hover:-translate-y-8 transition-transform duration-700"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr {{ $speaker['gradient'] }} rounded-full blur-2xl opacity-20 transform -translate-x-12 translate-y-12 group-hover:-translate-x-4 group-hover:translate-y-4 transition-transform duration-700"></div>
                        </div>

                        <!-- Content -->
                        <div class="relative z-10">
                            <!-- Avatar with Ring Animation -->
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <!-- Rotating Ring -->
                                    <div class="absolute -inset-2 rounded-full border-2 border-dashed border-white/20 group-hover:border-miscon-gold/40 transition-colors duration-500 group-hover:animate-[spin_20s_linear_infinite]"></div>
                                    <!-- Avatar -->
                                    <div class="relative w-24 h-24 rounded-full bg-gradient-to-br {{ $speaker['gradient'] }} p-1 transform group-hover:scale-110 transition-transform duration-500">
                                        <div class="w-full h-full rounded-full bg-miscon-navy/80 backdrop-blur flex items-center justify-center">
                                            <span class="text-3xl font-bold bg-gradient-to-br {{ $speaker['gradient'] }} bg-clip-text text-transparent">{{ $speaker['initials'] }}</span>
                                        </div>
                                    </div>
                                    <!-- Status Dot -->
                                    <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-miscon-navy shadow-lg shadow-green-500/50 animate-pulse"></div>
                                </div>
                            </div>

                            <!-- Name & Role -->
                            <div class="text-center mb-6">
                                <h4 class="text-xl font-bold mb-2 group-hover:text-miscon-gold transition-colors duration-300">{{ $speaker['name'] }}</h4>
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r {{ $speaker['gradient'] }} bg-opacity-20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    <span class="text-sm font-medium text-white/90">{{ $speaker['role'] }}</span>
                                </div>
                            </div>

                            <!-- Topic Badge -->
                            <div class="flex justify-center mb-6">
                                <div class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 group-hover:bg-white/10 group-hover:border-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span class="text-sm text-white/70">{{ $speaker['topic'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quote/Tagline -->
                            <p class="text-center text-sm text-white/50 italic leading-relaxed">
                                "Bringing wisdom and insight to inspire the next generation."
                            </p>

                            <!-- Bottom Accent Line -->
                            <div class="mt-6 h-1 rounded-full bg-gradient-to-r {{ $speaker['gradient'] }} opacity-30 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- More Speakers Coming Card -->
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-white/10 to-white/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-20 transition-all duration-700"></div>

                    <div class="relative glass rounded-3xl p-8 border-2 border-dashed border-white/20 group-hover:border-miscon-gold/30 transition-all duration-500 h-full flex flex-col items-center justify-center min-h-[320px]">
                        <!-- Animated Plus Icon -->
                        <div class="relative mb-6">
                            <div class="absolute -inset-4 rounded-full bg-miscon-gold/10 animate-ping opacity-20"></div>
                            <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-white/10 to-white/5 border-2 border-dashed border-white/30 group-hover:border-miscon-gold/50 flex items-center justify-center transition-all duration-500 group-hover:scale-110">
                                <svg class="w-10 h-10 text-white/40 group-hover:text-miscon-gold transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                        </div>

                        <h4 class="text-lg font-semibold text-white/70 mb-2 group-hover:text-white transition-colors">More Speakers</h4>
                        <p class="text-sm text-white/40 text-center">Stay tuned for announcements</p>

                        <!-- Decorative Dots -->
                        <div class="flex gap-2 mt-6">
                            <div class="w-2 h-2 rounded-full bg-white/20 animate-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-2 h-2 rounded-full bg-white/20 animate-bounce" style="animation-delay: 150ms;"></div>
                            <div class="w-2 h-2 rounded-full bg-white/20 animate-bounce" style="animation-delay: 300ms;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-24 relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">Previous Events</span>
                <h2 class="text-4xl sm:text-5xl font-bold">Capturing <span class="gradient-text">Moments</span></h2>
                <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">Relive the powerful memories from past MISCON conferences.</p>
            </div>

            <!-- Image Slider -->
            <div class="mb-12 overflow-hidden rounded-3xl reveal">
                <div class="slider-container">
                    <div class="slider-track flex gap-4">
                        @foreach(['IMG_0836.CR2.jpg', 'IMG_0956.CR2.jpg', 'IMG_0995.CR2.jpg', 'IMG_1016.CR2.jpg', 'IMG_2155.CR2.jpg', 'IMG_2156.CR2.jpg', 'IMG_2619.CR2.jpg', 'IMG_0836.CR2.jpg', 'IMG_0956.CR2.jpg', 'IMG_0995.CR2.jpg', 'IMG_1016.CR2.jpg', 'IMG_2155.CR2.jpg'] as $img)
                        <div class="flex-shrink-0 w-72 h-48 rounded-xl overflow-hidden">
                            <img src="{{ asset('images/' . $img) }}" alt="MISCON Event" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 stagger-children" id="galleryGrid">
                @foreach([
                    ['img' => 'IMG_0836.CR2.jpg', 'caption' => 'Worship Session', 'span' => 'md:col-span-2 md:row-span-2'],
                    ['img' => 'IMG_2619.CR2.jpg', 'caption' => 'Fellowship', 'span' => ''],
                    ['img' => 'IMG_0995.CR2.jpg', 'caption' => 'Prayer Time', 'span' => ''],
                    ['img' => 'IMG_2156.CR2.jpg', 'caption' => 'Special Music', 'span' => 'md:col-span-2'],
                    ['img' => 'IMG_1016.CR2.jpg', 'caption' => 'Panel Discussion', 'span' => ''],
                    ['img' => 'IMG_0956.CR2.jpg', 'caption' => 'Main Session', 'span' => ''],
                    ['img' => 'IMG_2155.CR2.jpg', 'caption' => 'Evening Program', 'span' => 'md:col-span-2'],
                ] as $item)
                <div class="gallery-item {{ $item['span'] }} aspect-video cursor-pointer" @click="openLightbox('{{ asset('images/' . $item['img']) }}', '{{ $item['caption'] }}')">
                    <img src="{{ asset('images/' . $item['img']) }}" alt="{{ $item['caption'] }}" class="w-full h-full object-cover" loading="lazy">
                    <div class="overlay">
                        <div>
                            <p class="text-white font-semibold">{{ $item['caption'] }}</p>
                            <p class="text-white/60 text-sm flex items-center gap-1 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                Click to expand
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Lightbox -->
    <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="lightbox" @click.self="lightboxOpen = false" @keydown.escape.window="lightboxOpen = false" x-cloak>
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 w-12 h-12 rounded-full glass flex items-center justify-center hover:bg-white/20 transition-colors z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="max-w-5xl max-h-[85vh] relative">
            <img :src="lightboxImage" alt="Gallery Image" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">
            <p x-text="lightboxCaption" class="text-center mt-4 text-lg font-medium"></p>
        </div>
    </div>

    <!-- Registration Status Check Modal -->
    <div x-data="statusChecker()"
         @open-status-modal.window="openModal()"
         @keydown.escape.window="closeModal()">
        <!-- Modal Backdrop -->
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click.self="closeModal()"
             x-cloak>

            <!-- Modal Content -->
            <div x-show="isOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="w-full max-w-lg glass rounded-3xl overflow-hidden">

                <!-- Modal Header -->
                <div class="relative p-6 border-b border-white/10 bg-gradient-to-r from-pcm-blue/20 via-pcm-purple/20 to-pcm-pink/20">
                    <button @click="closeModal()" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-miscon-gold/20 flex items-center justify-center">
                            <svg class="w-7 h-7 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Check Registration Status</h3>
                            <p class="text-sm text-white/60">Verify your MISCON26 registration</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Search Form -->
                    <div x-show="!result">
                        <p class="text-white/70 text-sm mb-4">Enter your registration number (for students) or national ID (for alumni) to check your registration status.</p>

                        <form @submit.prevent="checkStatus()">
                            <div class="relative mb-4">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                </span>
                                <input type="text"
                                       x-model="idNumber"
                                       class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                       placeholder="Enter Reg Number or National ID"
                                       required>
                            </div>

                            <!-- Error Message -->
                            <div x-show="errorMessage" x-transition class="mb-4 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span x-text="errorMessage"></span>
                            </div>

                            <button type="submit"
                                    class="w-full py-4 bg-miscon-gold hover:bg-miscon-gold/90 text-miscon-navy font-semibold rounded-xl transition-all duration-300 hover:shadow-[0_0_30px_rgba(212,175,55,0.3)] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                    :disabled="isLoading || !idNumber">
                                <span x-show="!isLoading" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Check Status
                                </span>
                                <span x-show="isLoading" class="flex items-center gap-2">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Searching...
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Result Display -->
                    <div x-show="result" x-transition>
                        <!-- Status Badge -->
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full"
                                 :class="result?.is_paid ? 'bg-green-500/20 border border-green-500/30' : 'bg-yellow-500/20 border border-yellow-500/30'">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                     :class="result?.is_paid ? 'bg-green-500' : 'bg-yellow-500'">
                                    <svg x-show="result?.is_paid" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <svg x-show="!result?.is_paid" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold" :class="result?.is_paid ? 'text-green-400' : 'text-yellow-400'" x-text="result?.is_paid ? 'REGISTERED & PAID' : 'PAYMENT PENDING'"></p>
                                    <p class="text-xs text-white/60" x-text="result?.is_paid ? 'You\'re all set for MISCON26!' : 'Please complete your payment'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Details -->
                        <div class="bg-white/5 rounded-2xl p-5 border border-white/10 mb-6">
                            <div class="grid gap-4">
                                <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Full Name</span>
                                    <span class="font-semibold" x-text="result?.full_name"></span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Type</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                          :class="result?.type === 'student' ? 'bg-pcm-blue/20 text-pcm-blue' : 'bg-pcm-purple/20 text-pcm-purple'"
                                          x-text="result?.type === 'student' ? 'Student' : 'Alumni'"></span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Institution</span>
                                    <span class="text-sm text-right max-w-[200px] truncate" x-text="result?.university" :title="result?.university"></span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                    <span class="text-white/60 text-sm" x-text="result?.type === 'student' ? 'Reg Number' : 'National ID'"></span>
                                    <span class="font-mono text-sm" x-text="result?.id_number"></span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Reference</span>
                                    <span class="font-mono text-miscon-gold text-sm" x-text="result?.reference"></span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Amount</span>
                                    <span class="font-semibold" x-text="'$' + result?.amount + ' USD'"></span>
                                </div>
                                <div class="flex justify-between items-center" x-show="result?.is_paid">
                                    <span class="text-white/60 text-sm">Paid On</span>
                                    <span class="text-sm text-green-400" x-text="result?.paid_at"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-white/60 text-sm">Registered On</span>
                                    <span class="text-sm" x-text="result?.registered_at"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button @click="resetSearch()" class="flex-1 py-3 rounded-xl border border-white/20 hover:bg-white/10 transition-colors font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                New Search
                            </button>
                            <button @click="closeModal()" class="flex-1 py-3 rounded-xl bg-miscon-gold text-miscon-navy font-semibold hover:bg-miscon-gold/90 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <section id="schedule" class="py-24 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">Event Schedule</span>
                <h2 class="text-4xl sm:text-5xl font-bold">Five Days of <span class="gradient-text">Transformation</span></h2>
            </div>

            <div class="max-w-4xl mx-auto" x-data="{ activeDay: 'day1' }">
                <div class="flex flex-wrap justify-center gap-3 mb-12 reveal">
                    @foreach([
                        ['id' => 'day1', 'day' => 'Wednesday', 'date' => 'April 2'],
                        ['id' => 'day2', 'day' => 'Thursday', 'date' => 'April 3'],
                        ['id' => 'day3', 'day' => 'Friday', 'date' => 'April 4'],
                        ['id' => 'day4', 'day' => 'Sabbath', 'date' => 'April 5'],
                        ['id' => 'day5', 'day' => 'Sunday', 'date' => 'April 6'],
                    ] as $d)
                    <button @click="activeDay = '{{ $d['id'] }}'" class="px-6 py-3 rounded-full font-medium transition-all duration-300 hover-lift" :class="activeDay === '{{ $d['id'] }}' ? 'bg-miscon-gold text-miscon-navy shadow-lg' : 'glass hover:border-miscon-gold/50'">
                        <span class="block text-xs uppercase tracking-wider opacity-70">{{ $d['day'] }}</span>
                        <span class="block font-bold">{{ $d['date'] }}</span>
                    </button>
                    @endforeach
                </div>

                <!-- Schedule Content -->
                <template x-for="day in ['day1', 'day2', 'day3', 'day4', 'day5']">
                    <div x-show="activeDay === day" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                        <template x-if="day === 'day1'">
                            <div class="space-y-4">
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">14:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Registration & Check-in</h4><p class="text-white/60 text-sm">Welcome delegates</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Arrival</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">18:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Dinner</h4><p class="text-white/60 text-sm">Fellowship meal</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Meal</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">19:30</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Welcome & Orientation</h4><p class="text-white/60 text-sm">Meet your fellow delegates</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Social</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="day === 'day2'">
                            <div class="space-y-4">
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">05:30</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Morning Devotion</h4><p class="text-white/60 text-sm">Prayer session</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Devotion</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">17:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Opening Ceremony</h4><p class="text-white/60 text-sm">Official opening</p></div>
                                    <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Ceremony</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">19:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Evening Devotional</h4><p class="text-white/60 text-sm">Speaker: Dr. Kabani</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Main Session</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="day === 'day3'">
                            <div class="space-y-4">
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">05:30</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Morning Devotion</h4><p class="text-white/60 text-sm">Prayer session</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Devotion</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">09:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Plenary Session</h4><p class="text-white/60 text-sm">Speaker: Dr. N. Matinhira</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Session</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">14:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Breakout Sessions</h4><p class="text-white/60 text-sm">Workshops</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Workshop</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">19:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Evening Service</h4><p class="text-white/60 text-sm">Speaker: Dr. Kabani</p></div>
                                    <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Main Session</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="day === 'day4'">
                            <div class="space-y-4">
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">05:30</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Early Morning Prayer</h4><p class="text-white/60 text-sm">Sabbath preparation</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Prayer</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">09:30</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Sabbath School</h4><p class="text-white/60 text-sm">Bible study</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Study</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">11:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Divine Service</h4><p class="text-white/60 text-sm">Speaker: Dr. Kabani</p></div>
                                    <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Main Session</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">18:30</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Sunset Vespers & AY</h4><p class="text-white/60 text-sm">Closing Sabbath</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Worship</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="day === 'day5'">
                            <div class="space-y-4">
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">06:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Morning Devotion</h4><p class="text-white/60 text-sm">Final reflection</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Devotion</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">09:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Closing Session</h4><p class="text-white/60 text-sm">Commissioning</p></div>
                                    <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Closing</span>
                                </div>
                                <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-miscon-gold/30 transition-all hover-lift">
                                    <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">12:00</div>
                                    <div class="flex-1"><h4 class="font-bold text-lg">Departure</h4><p class="text-white/60 text-sm">Safe travels</p></div>
                                    <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Departure</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="registration" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-radial from-pcm-purple/20 via-transparent to-transparent"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16 reveal">
                <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">Registration</span>
                <h2 class="text-4xl sm:text-5xl font-bold">Secure Your <span class="gradient-text">Spot</span></h2>
                <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">Don't miss this life-changing experience!</p>
            </div>

            <!-- Registration Form -->
            <div class="max-w-3xl mx-auto" x-data="registrationForm()" @institution-selected.window="formData.university = $event.detail">
                <!-- Step Indicator -->
                <div class="flex items-center justify-center gap-4 mb-12">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                             :class="step >= 1 ? 'bg-miscon-gold text-miscon-navy' : 'glass text-white/60'">1</div>
                        <span class="hidden sm:block text-sm font-medium" :class="step >= 1 ? 'text-miscon-gold' : 'text-white/40'">Details</span>
                    </div>
                    <div class="w-12 h-0.5 rounded-full transition-all duration-300" :class="step >= 2 ? 'bg-miscon-gold' : 'bg-white/20'"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                             :class="step >= 2 ? 'bg-miscon-gold text-miscon-navy' : 'glass text-white/60'">2</div>
                        <span class="hidden sm:block text-sm font-medium" :class="step >= 2 ? 'text-miscon-gold' : 'text-white/40'">Payment</span>
                    </div>
                    <div class="w-12 h-0.5 rounded-full transition-all duration-300" :class="step >= 3 ? 'bg-miscon-gold' : 'bg-white/20'"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300"
                             :class="step >= 3 ? 'bg-miscon-gold text-miscon-navy' : 'glass text-white/60'">3</div>
                        <span class="hidden sm:block text-sm font-medium" :class="step >= 3 ? 'text-miscon-gold' : 'text-white/40'">Done</span>
                    </div>
                </div>

                <!-- Step 1: Registration Form -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="glass rounded-3xl p-8 md:p-10">
                        <!-- Type Toggle -->
                        <div class="flex justify-center mb-10">
                            <div class="glass rounded-full p-1.5 inline-flex gap-1">
                                <button @click="formData.type = 'student'"
                                        class="px-8 py-3 rounded-full font-semibold text-sm uppercase tracking-wider transition-all duration-300"
                                        :class="formData.type === 'student' ? 'bg-miscon-gold text-miscon-navy shadow-lg' : 'text-white/60 hover:text-white'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Student
                                    </span>
                                </button>
                                <button @click="formData.type = 'alumni'"
                                        class="px-8 py-3 rounded-full font-semibold text-sm uppercase tracking-wider transition-all duration-300"
                                        :class="formData.type === 'alumni' ? 'bg-miscon-gold text-miscon-navy shadow-lg' : 'text-white/60 hover:text-white'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Alumni
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Price Display -->
                        <div class="text-center mb-10">
                            <p class="text-sm uppercase tracking-wider text-white/60 mb-2">Registration Fee</p>
                            <div class="flex items-baseline justify-center gap-1">
                                <span class="text-5xl font-bold gradient-text" x-text="'$' + (formData.type === 'student' ? '45' : '65')">$45</span>
                                    <span class="text-white/60">USD</span>
                                </div>
                            <p class="text-sm text-white/40 mt-2" x-text="formData.type === 'student' ? 'Current student rate' : 'Alumni rate (includes networking)'"></p>
                            </div>

                        <!-- Form Fields -->
                        <form @submit.prevent="goToPayment()" class="space-y-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-white/80 mb-2">Full Name <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    </span>
                                    <input type="text" x-model="formData.fullName" required
                                           class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                           placeholder="Enter your full name">
                                </div>
                            </div>

                            <!-- University / Former School -->
                            <div x-data="institutionSelect()" class="relative">
                                <label class="block text-sm font-medium text-white/80 mb-2">
                                    <span x-text="$root.formData.type === 'student' ? 'University / College' : 'Former School'"></span>
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40 z-10">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </span>
                                    <input type="text"
                                           x-model="search"
                                           @focus="open = true"
                                           @click="open = true"
                                           @input="$dispatch('institution-selected', '')"
                                           class="w-full pl-12 pr-10 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                           :placeholder="$root.formData.type === 'student' ? 'Search for your institution...' : 'Search for your former school...'"
                                           autocomplete="off">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 pointer-events-none">
                                        <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>

                                <!-- Dropdown -->
                                <div x-show="open"
                                     @click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute z-50 w-full mt-2 max-h-72 overflow-y-auto rounded-xl bg-miscon-navy/95 backdrop-blur-xl border border-white/10 shadow-2xl"
                                     x-cloak>
                                    <template x-for="(institutions, category) in filteredInstitutions" :key="category">
                                        <div x-show="institutions.length > 0">
                                            <div class="sticky top-0 px-4 py-2 bg-miscon-navy/90 backdrop-blur border-b border-white/10">
                                                <span class="text-xs font-semibold uppercase tracking-wider text-miscon-gold" x-text="category"></span>
                                            </div>
                                            <template x-for="inst in institutions" :key="inst">
                                                <button type="button"
                                                        @click="selectInstitution(inst)"
                                                        class="w-full px-4 py-3 text-left hover:bg-white/10 transition-colors flex items-center gap-3 border-b border-white/5 last:border-b-0">
                                                    <svg class="w-4 h-4 text-white/40 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span class="text-sm text-white/80" x-text="inst"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                    <div x-show="Object.values(filteredInstitutions).flat().length === 0" class="px-4 py-6 text-center text-white/40 text-sm">
                                        No institutions found matching your search
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-medium text-white/80 mb-2">Phone Number <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </span>
                                    <input type="tel" x-model="formData.phone" required
                                           class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                           placeholder="e.g., 0771234567">
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="block text-sm font-medium text-white/80 mb-2">Email Address <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <input type="email" x-model="formData.email" required
                                           class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                           placeholder="e.g., yourname@email.com">
                                </div>
                                <p class="mt-2 text-xs text-white/40">We'll send your confirmation and event updates to this email</p>
                            </div>

                            <!-- Registration Number / National ID -->
                            <div>
                                <label class="block text-sm font-medium text-white/80 mb-2">
                                    <span x-text="formData.type === 'student' ? 'Registration Number' : 'National ID'"></span>
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </span>
                                    <input type="text" x-model="formData.idNumber" required
                                           class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                           :placeholder="formData.type === 'student' ? 'e.g., R2012345A' : 'e.g., 63-123456A78'">
                                </div>
                            </div>

                            <!-- Gender and Level -->
                            <div class="grid sm:grid-cols-2 gap-6">
                                <!-- Gender -->
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">Gender <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </span>
                                        <select x-model="formData.gender" required
                                                class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all appearance-none cursor-pointer">
                                            <option value="" disabled selected class="bg-miscon-navy">Select gender</option>
                                            <option value="male" class="bg-miscon-navy">Male</option>
                                            <option value="female" class="bg-miscon-navy">Female</option>
                                        </select>
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Level -->
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">
                                        <span x-text="formData.type === 'student' ? 'Level' : 'Graduation Year'"></span>
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </span>
                                        <template x-if="formData.type === 'student'">
                                            <select x-model="formData.level" required
                                                    class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all appearance-none cursor-pointer">
                                                <option value="" disabled selected class="bg-miscon-navy">Select level</option>
                                                <option value="1.1" class="bg-miscon-navy">Level 1.1</option>
                                                <option value="1.2" class="bg-miscon-navy">Level 1.2</option>
                                                <option value="2.1" class="bg-miscon-navy">Level 2.1</option>
                                                <option value="2.2" class="bg-miscon-navy">Level 2.2</option>
                                                <option value="3.1" class="bg-miscon-navy">Level 3.1</option>
                                                <option value="3.2" class="bg-miscon-navy">Level 3.2</option>
                                                <option value="4.1" class="bg-miscon-navy">Level 4.1</option>
                                                <option value="4.2" class="bg-miscon-navy">Level 4.2</option>
                                                <option value="5+" class="bg-miscon-navy">Level 5+</option>
                                            </select>
                                        </template>
                                        <template x-if="formData.type === 'alumni'">
                                            <input type="text" x-model="formData.level" required
                                                   class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all placeholder:text-white/30"
                                                   placeholder="e.g., 2020">
                                        </template>
                                        <span x-show="formData.type === 'student'" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Features Preview -->
                            <div class="mt-8 p-6 rounded-2xl bg-white/5 border border-white/10">
                                <p class="text-sm font-medium text-miscon-gold mb-4">What's Included:</p>
                                <div class="grid sm:grid-cols-2 gap-3">
                                    <div class="flex items-center gap-2 text-sm text-white/70">
                                        <svg class="w-4 h-4 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Full conference access
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-white/70">
                                        <svg class="w-4 h-4 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Accommodation (4 nights)
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-white/70">
                                        <svg class="w-4 h-4 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Meals included
                                    </div>

                                    <div x-show="formData.type === 'alumni'" class="flex items-center gap-2 text-sm text-white/70">
                                        <svg class="w-4 h-4 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Alumni networking
                                    </div>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <div x-show="errorMessage" x-transition class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span x-text="errorMessage"></span>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-primary w-full py-4 text-lg font-semibold ripple-effect group disabled:opacity-50 disabled:cursor-not-allowed" :disabled="isSubmitting">
                                <span class="relative z-10 flex items-center justify-center gap-2" x-show="!isSubmitting">
                                    Proceed to Payment
                                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </span>
                                <span class="relative z-10 flex items-center justify-center gap-2" x-show="isSubmitting">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Creating Registration...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Step 2: Payment (Paynow Simulation) -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="glass rounded-3xl p-8 md:p-10">
                        <!-- Paynow Header -->
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-[#00a651] rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <h3 class="text-2xl font-bold">Paynow</h3>
                                    <p class="text-sm text-white/60">Secure Payment Gateway</p>
                                </div>
                            </div>
                            <p class="text-white/60 text-sm">Complete your MISCON26 registration payment</p>
                        </div>

                        <!-- Payment Summary -->
                        <div class="bg-white/5 rounded-2xl p-6 mb-8 border border-white/10">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <span class="text-white/60">Registrant</span>
                                <span class="font-medium" x-text="formData.fullName"></span>
                            </div>
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <span class="text-white/60">Type</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium"
                                      :class="formData.type === 'student' ? 'bg-pcm-blue/20 text-pcm-blue' : 'bg-pcm-purple/20 text-pcm-purple'"
                                      x-text="formData.type === 'student' ? 'Student' : 'Alumni'"></span>
                            </div>
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <span class="text-white/60">Phone</span>
                                <span class="font-medium" x-text="formData.phone"></span>
                            </div>
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <span class="text-white/60" x-text="formData.type === 'student' ? 'Reg Number' : 'National ID'"></span>
                                <span class="font-medium font-mono" x-text="formData.idNumber"></span>
                            </div>
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-semibold">Total Amount</span>
                                <span class="text-2xl font-bold text-miscon-gold" x-text="'$' + (formData.type === 'student' ? '45' : '65') + ' USD'"></span>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-8">
                            <p class="text-sm font-medium text-white/80 mb-4">Select Payment Method</p>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <button @click="paymentMethod = 'ecocash'"
                                        class="p-4 rounded-xl border-2 transition-all duration-300 flex items-center gap-3"
                                        :class="paymentMethod === 'ecocash' ? 'border-[#00a651] bg-[#00a651]/10' : 'border-white/10 hover:border-white/30'">
                                    <div class="w-12 h-12 rounded-lg bg-[#ffc72c] flex items-center justify-center">
                                        <span class="text-black font-bold text-sm">ECO</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold">EcoCash</p>
                                        <p class="text-xs text-white/60">Mobile Money</p>
                                    </div>
                                    <div x-show="paymentMethod === 'ecocash'" class="ml-auto">
                                        <svg class="w-6 h-6 text-[#00a651]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    </div>
                                </button>
                                <button @click="paymentMethod = 'innbucks'"
                                        class="p-4 rounded-xl border-2 transition-all duration-300 flex items-center gap-3"
                                        :class="paymentMethod === 'innbucks' ? 'border-[#00a651] bg-[#00a651]/10' : 'border-white/10 hover:border-white/30'">
                                    <div class="w-12 h-12 rounded-lg bg-[#ff6b00] flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">INN</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold">InnBucks</p>
                                        <p class="text-xs text-white/60">Digital Wallet</p>
                                    </div>
                                    <div x-show="paymentMethod === 'innbucks'" class="ml-auto">
                                        <svg class="w-6 h-6 text-[#00a651]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Number for Payment -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-white/80 mb-2">
                                <span x-text="paymentMethod === 'ecocash' ? 'EcoCash Number' : 'InnBucks Number'"></span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="tel" x-model="paymentPhone"
                                       class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/5 border border-white/10 focus:border-[#00a651]/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#00a651]/20 transition-all placeholder:text-white/30"
                                       :placeholder="paymentMethod === 'ecocash' ? '07XX XXX XXX' : '07XX XXX XXX'">
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div x-show="errorMessage" x-transition class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-text="errorMessage"></span>
                        </div>

                        <!-- Waiting for Payment State -->
                        <div x-show="isPolling" x-transition class="mb-8">
                            <div class="bg-[#00a651]/10 border border-[#00a651]/30 rounded-2xl p-6 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 relative">
                                    <div class="absolute inset-0 border-4 border-[#00a651]/30 rounded-full"></div>
                                    <div class="absolute inset-0 border-4 border-[#00a651] rounded-full border-t-transparent animate-spin"></div>
                                    <div class="absolute inset-3 bg-[#00a651]/20 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-[#00a651]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-[#00a651] mb-2">Waiting for Payment</h4>
                                <p class="text-white/70 text-sm mb-4" x-text="paymentInstructions"></p>
                                <p class="text-white/50 text-xs">Please complete the payment on your phone. This page will update automatically.</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4" x-show="!isPolling">
                            <button @click="step = 1; errorMessage = ''" class="btn-outline flex-1 py-4" :disabled="isProcessing">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Back
                                </span>
                            </button>
                            <button @click="processPayment()" class="flex-1 py-4 bg-[#00a651] hover:bg-[#00a651]/90 text-white font-semibold rounded-xl transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,166,81,0.3)] disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!paymentPhone || isProcessing">
                                <span class="flex items-center justify-center gap-2" x-show="!isProcessing">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pay Now
                                </span>
                                <span class="flex items-center justify-center gap-2" x-show="isProcessing && !isPolling">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Initiating Payment...
                                </span>
                            </button>
                        </div>

                        <!-- Cancel Polling Button -->
                        <div x-show="isPolling" class="mt-4">
                            <button @click="cancelPayment()" class="w-full py-3 text-white/60 hover:text-white text-sm font-medium transition-colors">
                                Cancel and try again
                            </button>
                        </div>

                        <!-- Security Note -->
                        <p class="text-center text-xs text-white/40 mt-6 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Secure payment powered by Paynow Zimbabwe
                        </p>
                    </div>
                </div>

                <!-- Step 3: Success -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-cloak>
                    <div class="glass rounded-3xl p-8 md:p-12 text-center">
                        <!-- Success Animation -->
                        <div class="w-24 h-24 mx-auto mb-8 relative">
                            <div class="absolute inset-0 bg-[#00a651]/20 rounded-full animate-ping"></div>
                            <div class="absolute inset-0 bg-[#00a651] rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-3xl font-bold mb-4">Registration Successful!</h3>
                        <p class="text-white/60 mb-8 max-w-md mx-auto">
                            Thank you, <span class="text-miscon-gold font-semibold" x-text="formData.fullName"></span>! Your registration for MISCON26 has been confirmed.
                        </p>

                        <!-- Confirmation Details -->
                        <div class="bg-white/5 rounded-2xl p-6 mb-8 text-left max-w-md mx-auto border border-white/10">
                            <p class="text-sm text-white/60 mb-4">Confirmation Details:</p>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-white/60">Reference</span>
                                    <span class="font-mono font-semibold text-miscon-gold" x-text="reference"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Paynow Ref</span>
                                    <span class="font-mono text-sm" x-text="paynowReference"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Type</span>
                                    <span x-text="formData.type === 'student' ? 'Student' : 'Alumni'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Amount Paid</span>
                                    <span class="font-semibold" x-text="'$' + (formData.type === 'student' ? '45' : '65') + ' USD'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Event Date</span>
                                    <span>April 2-6, 2026</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-white/40 mb-8">
                            A confirmation email has been sent to <span class="text-white/60" x-text="formData.email"></span>
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button @click="resetForm()" class="btn-outline px-8 py-3">
                                Register Another Person
                            </button>
                            <a href="https://chat.whatsapp.com/BKROFqIVsdE3vWccklOKGU" target="_blank" class="btn-primary px-8 py-3">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Join WhatsApp Group
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 relative">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16 reveal">
                    <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">Get In Touch</span>
                    <h2 class="text-4xl sm:text-5xl font-bold">Contact <span class="gradient-text">Us</span></h2>
                </div>

                <div class="glass rounded-3xl p-8 reveal">
                    <div class="grid md:grid-cols-3 gap-8 text-center">
                        <a href="tel:+263782504742" class="flex flex-col items-center gap-4 p-6 rounded-2xl hover:bg-white/5 transition-colors group">
                            <div class="w-16 h-16 rounded-full bg-miscon-gold/10 flex items-center justify-center group-hover:scale-110 group-hover:bg-miscon-gold/20 transition-all">
                                <svg class="w-8 h-8 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Phone</h4>
                                <p class="text-miscon-gold">+263 78 250 4742</p>
                            </div>
                        </a>
                        <a href="https://wa.me/263782504742" target="_blank" class="flex flex-col items-center gap-4 p-6 rounded-2xl hover:bg-white/5 transition-colors group">
                            <div class="w-16 h-16 rounded-full bg-green-500/10 flex items-center justify-center group-hover:scale-110 group-hover:bg-green-500/20 transition-all">
                                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">WhatsApp</h4>
                                <p class="text-green-500">Message Us</p>
                            </div>
                        </a>
                        <div class="flex flex-col items-center gap-4 p-6 rounded-2xl">
                            <div class="w-16 h-16 rounded-full bg-pcm-purple/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-pcm-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Venue</h4>
                                <p class="text-white/60">Amai Mugabe Group of Schools</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-12">
                        <img src="{{ asset('images/logo.png') }}" alt="PCM Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="font-bold">MISCON<span class="text-miscon-gold">26</span></p>
                        <p class="text-xs text-white/60">Public Campus Ministries</p>
                    </div>
                </div>
                <p class="text-white/60 text-sm text-center">&copy; 2026 North Zimbabwe Conference | SDA Church</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button x-show="scrolled" x-transition @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="fixed bottom-8 right-8 w-12 h-12 rounded-full bg-miscon-gold text-miscon-navy flex items-center justify-center shadow-lg hover:shadow-[0_0_30px_rgba(212,175,55,0.5)] hover:scale-110 transition-all z-50" x-cloak>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function mainApp() {
            return {
                scrolled: false,
                mobileMenuOpen: false,
                lightboxOpen: false,
                lightboxImage: '',
                lightboxCaption: '',
                handleScroll() {
                    this.scrolled = window.scrollY > 50;
                },
                openLightbox(img, caption) {
                    this.lightboxImage = img;
                    this.lightboxCaption = caption;
                    this.lightboxOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                closeLightbox() {
                    this.lightboxOpen = false;
                    document.body.style.overflow = '';
                },
                tiltCard(e, el) {
                    const rect = el.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = (y - centerY) / 20;
                    const rotateY = (centerX - x) / 20;
                    el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
                },
                resetTilt(el) {
                    el.style.transform = '';
                }
            }
        }

        function countdown() {
            return {
                days: '00', hours: '00', minutes: '00', seconds: '00',
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const eventDate = new Date('April 2, 2026 00:00:00').getTime();
                    const now = new Date().getTime();
                    const distance = eventDate - now;
                    if (distance < 0) return;
                    this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                    this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                }
            }
        }

        function registrationForm() {
            return {
                step: 1,
                paymentMethod: 'ecocash',
                paymentPhone: '',
                isProcessing: false,
                isSubmitting: false,
                isPolling: false,
                registrationId: null,
                reference: '',
                paynowReference: '',
                errorMessage: '',
                paymentInstructions: '',
                pollInterval: null,
                pollAttempts: 0,
                maxPollAttempts: 60, // 5 minutes with 5-second intervals
                formData: {
                    type: 'student',
                    fullName: '',
                    university: '',
                    phone: '',
                    email: '',
                    idNumber: '',
                    gender: '',
                    level: ''
                },
                goToPayment() {
                    this.errorMessage = '';

                    // Validate all required fields
                    if (!this.formData.fullName) {
                        this.errorMessage = 'Please enter your full name.';
                        return;
                    }
                    if (!this.formData.university) {
                        this.errorMessage = 'Please select your university/institution.';
                        return;
                    }
                    if (!this.formData.phone) {
                        this.errorMessage = 'Please enter your phone number.';
                        return;
                    }
                    if (!this.formData.email) {
                        this.errorMessage = 'Please enter your email address.';
                        return;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) {
                        this.errorMessage = 'Please enter a valid email address.';
                        return;
                    }
                    if (!this.formData.idNumber) {
                        this.errorMessage = this.formData.type === 'student' ? 'Please enter your registration number.' : 'Please enter your national ID.';
                        return;
                    }
                    if (!this.formData.gender) {
                        this.errorMessage = 'Please select your gender.';
                        return;
                    }
                    if (!this.formData.level) {
                        this.errorMessage = this.formData.type === 'student' ? 'Please select your level.' : 'Please enter your graduation year.';
                        return;
                    }

                    // All validations passed - proceed to payment step (don't save yet)
                    this.paymentPhone = this.formData.phone;
                    this.step = 2;
                    window.scrollTo({ top: document.getElementById('registration').offsetTop - 100, behavior: 'smooth' });
                },
                async processPayment() {
                    if (!this.paymentPhone) {
                        this.errorMessage = 'Please enter your payment phone number.';
                        return;
                    }

                    this.isProcessing = true;
                    this.errorMessage = '';
                    this.paymentInstructions = '';

                    try {
                        // Send registration + payment data together
                        const response = await fetch('/api/registration/pay', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                // Registration data
                                type: this.formData.type,
                                full_name: this.formData.fullName,
                                university: this.formData.university,
                                phone: this.formData.phone,
                                email: this.formData.email,
                                id_number: this.formData.idNumber,
                                gender: this.formData.gender,
                                level: this.formData.level,
                                // Payment data
                                payment_method: this.paymentMethod,
                                payment_phone: this.paymentPhone,
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            this.errorMessage = data.message || 'Payment failed. Please try again.';
                            this.isProcessing = false;
                            return;
                        }

                        // Store registration ID for polling
                        this.registrationId = data.data.registration_id;
                        this.reference = data.data.reference;

                        // Show instructions from Paynow
                        this.paymentInstructions = data.data.instructions || 'Please check your phone and enter your PIN to complete the payment.';

                        // Start polling for payment status
                        this.isPolling = true;
                        this.pollAttempts = 0;
                        this.startPolling();
                    } catch (error) {
                        console.error('Payment error:', error);
                        this.errorMessage = 'Network error. Please check your connection and try again.';
                        this.isProcessing = false;
                    }
                },
                startPolling() {
                    // Clear any existing interval
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                    }

                    // Poll every 5 seconds
                    this.pollInterval = setInterval(() => {
                        this.pollPaymentStatus();
                    }, 5000);

                    // Also poll immediately
                    this.pollPaymentStatus();
                },
                stopPolling() {
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                        this.pollInterval = null;
                    }
                    this.isPolling = false;
                    this.isProcessing = false;
                },
                async pollPaymentStatus() {
                    this.pollAttempts++;

                    // Stop polling after max attempts
                    if (this.pollAttempts > this.maxPollAttempts) {
                        this.stopPolling();
                        this.errorMessage = 'Payment verification timed out. If you completed the payment, please check your status using the "Check Status" button.';
                        return;
                    }

                    try {
                        const response = await fetch('/api/registration/payment/poll', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                registration_id: this.registrationId,
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            console.log('Poll error:', data.message);
                            return;
                        }

                        // Check if payment was successful
                        if (data.data.paid) {
                            this.stopPolling();
                            this.paynowReference = data.data.paynow_reference;
                            this.step = 3;
                            window.scrollTo({ top: document.getElementById('registration').offsetTop - 100, behavior: 'smooth' });
                            return;
                        }

                        // Check if payment failed
                        if (data.data.payment_status === 'failed') {
                            this.stopPolling();
                            this.errorMessage = 'Payment was not successful. Please try again.';
                            return;
                        }

                        // Still processing - continue polling
                        console.log('Payment status:', data.data.status);
                    } catch (error) {
                        console.error('Poll error:', error);
                    }
                },
                cancelPayment() {
                    this.stopPolling();
                    this.errorMessage = '';
                    this.paymentInstructions = '';
                },
                retryPayment() {
                    this.errorMessage = '';
                    this.paymentInstructions = '';
                    this.isProcessing = false;
                    this.isPolling = false;
                },
                resetForm() {
                    this.stopPolling();
                    this.step = 1;
                    this.paymentMethod = 'ecocash';
                    this.paymentPhone = '';
                    this.isProcessing = false;
                    this.isSubmitting = false;
                    this.isPolling = false;
                    this.registrationId = null;
                    this.reference = '';
                    this.paynowReference = '';
                    this.errorMessage = '';
                    this.paymentInstructions = '';
                    this.pollAttempts = 0;
                    this.formData = {
                        type: 'student',
                        fullName: '',
                        university: '',
                        phone: '',
                        email: '',
                        idNumber: '',
                        gender: '',
                        level: ''
                    };
                    window.scrollTo({ top: document.getElementById('registration').offsetTop - 100, behavior: 'smooth' });
                }
            }
        }

        function institutionSelect() {
            return {
                open: false,
                search: '',
                institutions: {
                    'Public / State Universities': [
                        'Bindura University of Science Education (BUSE)',
                        'Chinhoyi University of Technology (CUT)',
                        'Great Zimbabwe University (GZU)',
                        'Gwanda State University (GSU)',
                        'Harare Institute of Technology (HIT)',
                        'Lupane State University (LSU)',
                        'Manicaland State University of Applied Sciences',
                        'Marondera University of Agricultural Sciences and Technology',
                        'Midlands State University (MSU)',
                        'National University of Science and Technology (NUST)',
                        'University of Zimbabwe (UZ)',
                        'Zimbabwe National Defence University',
                        'Zimbabwe Open University (ZOU)'
                    ],
                    'Private Universities': [
                        'Africa University (AU)',
                        'Arrupe Jesuit University',
                        'Catholic University in Zimbabwe (CUZ)',
                        'Reformed Church University',
                        'Solusi University',
                        'Women\'s University in Africa (WUA)',
                        'Zimbabwe Ezekiel Guti University (ZEGU)'
                    ],
                    'Polytechnics & Technical Colleges': [
                        'Harare Polytechnic',
                        'Bulawayo Polytechnic',
                        'Gweru Polytechnic',
                        'Kwekwe Polytechnic',
                        'Mutare Polytechnic',
                        'J. M. Nkomo Polytechnic',
                        'Kushinga Phikelela Polytechnic',
                        'Masvingo Polytechnic',
                        'Zimbabwe College of Music',
                        'Redcliff Technical College',
                        'Nyagura Technical College',
                        'Rusape Technical College'
                    ],
                    'Teachers\' Colleges': [
                        'Belvedere Technical Teachers\' College',
                        'Bondolfi Teachers College',
                        'Hillside Teachers College',
                        'Madziwa Teachers College',
                        'Marymount Teachers College',
                        'Masvingo Teachers\' College',
                        'Mkoba Teachers College',
                        'Morgan Zintec Teachers College',
                        'Morgenster Teachers College',
                        'Mutare Teachers\' College',
                        'Nyadire Teachers College',
                        'Seke Teachers College',
                        'United College of Education',
                        'Chinhoyi Technical Teachers College'
                    ],
                    'Other Colleges': [
                        'Speciss College',
                        'Zimbabwe Institute of Legal Studies',
                        'Mutare College',
                        'Trust Academy',
                        'Herentials College',
                        'Lighthouse College',
                        'ILSA College',
                        'Zimbabwe Women Empowerment Institution'
                    ]
                },
                get filteredInstitutions() {
                    const searchLower = this.search.toLowerCase();
                    const filtered = {};
                    for (const [category, insts] of Object.entries(this.institutions)) {
                        filtered[category] = insts.filter(inst =>
                            inst.toLowerCase().includes(searchLower)
                        );
                    }
                    return filtered;
                },
                selectInstitution(inst) {
                    this.search = inst;
                    this.$dispatch('institution-selected', inst);
                    this.open = false;
                }
            }
        }

        function statusChecker() {
            return {
                isOpen: false,
                idNumber: '',
                isLoading: false,
                errorMessage: '',
                result: null,
                openModal() {
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                    // Reset after close animation
                    setTimeout(() => {
                        this.resetSearch();
                    }, 300);
                },
                resetSearch() {
                    this.idNumber = '';
                    this.errorMessage = '';
                    this.result = null;
                },
                async checkStatus() {
                    if (!this.idNumber.trim()) {
                        this.errorMessage = 'Please enter your registration number or national ID';
                        return;
                    }

                    this.isLoading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('/api/registration/check', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                id_number: this.idNumber.trim(),
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            this.errorMessage = data.message || 'Registration not found. Please check your ID and try again.';
                            return;
                        }

                        this.result = data.data;
                    } catch (error) {
                        console.error('Status check error:', error);
                        this.errorMessage = 'Network error. Please check your connection and try again.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }

        function animateCounter(el, target, delay) {
            setTimeout(() => {
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.querySelector('.stat-number').textContent = Math.floor(current) + (target > 10 ? '+' : '');
                }, 30);
            }, delay);
        }

        // Intersection Observer for animations
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger-children').forEach(el => observer.observe(el));

            // Parallax on mouse move
            document.addEventListener('mousemove', (e) => {
                const particles = document.querySelectorAll('.particle');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                particles.forEach((p, i) => {
                    const speed = (i + 1) * 0.02;
                    p.style.transform = `translate(${(x - 0.5) * 100 * speed}px, ${(y - 0.5) * 100 * speed}px)`;
                });
            });
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>
