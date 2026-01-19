<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="MISCON26 - Watchmen On The Wall. A transformative student conference by PCM (Public Campus Ministries) under the North Zimbabwe Conference of the Seventh-day Adventist Church. April 3-6, 2026.">
        
        <title>MISCON26 | Watchmen On The Wall</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased overflow-x-hidden" x-data="{ mobileMenuOpen: false, showScrollTop: false }" 
          @scroll.window="showScrollTop = window.scrollY > 500">
        
        <!-- Animated Background -->
        <div class="fixed inset-0 bg-mesh noise-overlay -z-10"></div>
        
        <!-- Floating Particles -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-5">
            <div class="particle w-2 h-2 bg-miscon-gold top-[10%] left-[10%] animation-delay-100"></div>
            <div class="particle w-3 h-3 bg-pcm-purple top-[20%] right-[15%] animation-delay-300"></div>
            <div class="particle w-2 h-2 bg-pcm-pink top-[40%] left-[5%] animation-delay-500"></div>
            <div class="particle w-4 h-4 bg-miscon-gold/30 top-[60%] right-[10%] animation-delay-200"></div>
            <div class="particle w-2 h-2 bg-pcm-blue top-[80%] left-[20%] animation-delay-400"></div>
            <div class="particle w-3 h-3 bg-miscon-gold/50 top-[70%] right-[25%] animation-delay-600"></div>
        </div>

        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500" 
             :class="window.scrollY > 50 ? 'glass-dark py-3' : 'py-6'" 
             x-data="{ scrolled: false }"
             @scroll.window="scrolled = window.scrollY > 50">
            <div class="container mx-auto px-6">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="#" class="flex items-center gap-3 group">
                        <div class="relative w-12 h-14 transition-transform duration-300 group-hover:scale-110">
                            <!-- PCM Shield Logo -->
                            <svg viewBox="0 0 100 120" class="w-full h-full">
                                <defs>
                                    <linearGradient id="pcmGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#3b82f6"/>
                                        <stop offset="50%" style="stop-color:#8b5cf6"/>
                                        <stop offset="100%" style="stop-color:#ec4899"/>
                                    </linearGradient>
                                </defs>
                                <!-- Shield shape -->
                                <path d="M50 5 L95 20 L95 60 Q95 95 50 115 Q5 95 5 60 L5 20 Z" 
                                      fill="url(#pcmGradient)" stroke="white" stroke-width="3"/>
                                <!-- PCM Text -->
                                <text x="50" y="45" text-anchor="middle" fill="white" font-family="Outfit" font-weight="bold" font-size="20">PCM</text>
                                <!-- Book icon -->
                                <path d="M30 60 L50 55 L70 60 L70 80 L50 75 L30 80 Z" fill="none" stroke="white" stroke-width="2"/>
                                <line x1="50" y1="55" x2="50" y2="75" stroke="white" stroke-width="2"/>
                                <!-- Graduation cap -->
                                <path d="M35 50 L50 42 L65 50 L50 58 Z" fill="white"/>
                                <line x1="62" y1="50" x2="62" y2="60" stroke="white" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-xl font-bold tracking-tight">MISCON</span>
                            <span class="text-xl font-bold text-miscon-gold">26</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="#about" class="nav-link text-sm font-medium uppercase tracking-wider">About</a>
                        <a href="#speakers" class="nav-link text-sm font-medium uppercase tracking-wider">Speakers</a>
                        <a href="#schedule" class="nav-link text-sm font-medium uppercase tracking-wider">Schedule</a>
                        <a href="#registration" class="nav-link text-sm font-medium uppercase tracking-wider">Register</a>
                        <a href="#contact" class="btn-primary text-sm font-semibold uppercase tracking-wider">
                            <span class="relative z-10">Get Tickets</span>
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden relative w-10 h-10 flex items-center justify-center">
                        <div class="w-6 flex flex-col gap-1.5 transition-all duration-300" :class="mobileMenuOpen ? 'gap-0' : ''">
                            <span class="w-full h-0.5 bg-white transition-all duration-300 origin-center" 
                                  :class="mobileMenuOpen ? 'rotate-45 translate-y-0.5' : ''"></span>
                            <span class="w-full h-0.5 bg-white transition-all duration-300" 
                                  :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                            <span class="w-full h-0.5 bg-white transition-all duration-300 origin-center" 
                                  :class="mobileMenuOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="md:hidden glass-dark mt-4 mx-6 rounded-2xl p-6"
                 x-cloak>
                <div class="flex flex-col gap-4">
                    <a href="#about" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10">About</a>
                    <a href="#speakers" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10">Speakers</a>
                    <a href="#schedule" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10">Schedule</a>
                    <a href="#registration" @click="mobileMenuOpen = false" class="text-lg font-medium py-2 border-b border-white/10">Register</a>
                    <a href="#contact" class="btn-primary text-center mt-2">Get Tickets</a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
            <!-- Hero Background Image Overlay -->
            <div class="absolute inset-0 hero-gradient"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-pcm-purple/20 rounded-full blur-[100px] animate-pulse-slow"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-pcm-pink/20 rounded-full blur-[120px] animate-pulse-slow animation-delay-500"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-miscon-gold/10 rounded-full animate-spin-slow"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-pcm-purple/10 rounded-full animate-spin-slow" style="animation-direction: reverse;"></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-5xl mx-auto text-center">
                    <!-- Pre-title -->
                    <div class="animate-slide-down">
                        <span class="inline-block px-6 py-2 rounded-full glass text-sm font-medium tracking-widest uppercase mb-8">
                            North Zimbabwe Conference Presents
                        </span>
                    </div>

                    <!-- Main Title -->
                    <h1 class="animate-scale-in animation-delay-200">
                        <span class="block text-6xl sm:text-7xl md:text-8xl lg:text-9xl font-black tracking-tight">
                            MISCON
                            <span class="gradient-text">26</span>
                        </span>
                    </h1>

                    <!-- Theme -->
                    <div class="mt-8 animate-slide-up animation-delay-400">
                        <h2 class="font-script text-4xl sm:text-5xl md:text-6xl text-miscon-gold text-shadow">
                            Watchmen
                        </h2>
                        <p class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.3em] uppercase mt-2">
                            On The <span class="font-bold text-miscon-gold">Wall</span>
                        </p>
                    </div>

                    <!-- Event Details -->
                    <div class="mt-12 flex flex-wrap justify-center gap-6 animate-fade-in animation-delay-600">
                        <div class="flex items-center gap-2 text-white/80">
                            <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">April 3-6, 2026</span>
                        </div>
                        <div class="flex items-center gap-2 text-white/80">
                            <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">Amai Mugabe Group of Schools</span>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up animation-delay-800">
                        <a href="#registration" class="btn-primary group">
                            <span class="relative z-10 flex items-center gap-2">
                                Register Now
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </a>
                        <a href="#about" class="btn-outline">Learn More</a>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="mt-16 animate-fade-in animation-delay-1000" x-data="countdown()">
                        <p class="text-sm uppercase tracking-widest text-white/60 mb-6">Event Starts In</p>
                        <div class="flex justify-center gap-4 sm:gap-6">
                            <div class="countdown-box">
                                <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="days">00</span>
                                <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Days</p>
                            </div>
                            <div class="countdown-box">
                                <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="hours">00</span>
                                <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Hours</p>
                            </div>
                            <div class="countdown-box">
                                <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="minutes">00</span>
                                <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Minutes</p>
                            </div>
                            <div class="countdown-box">
                                <span class="text-3xl sm:text-4xl font-bold gradient-text" x-text="seconds">00</span>
                                <p class="text-xs uppercase tracking-wider text-white/60 mt-2">Seconds</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 scroll-indicator">
                <a href="#about" class="flex flex-col items-center gap-2 text-white/60 hover:text-white transition-colors">
                    <span class="text-xs uppercase tracking-widest">Scroll</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-24 relative">
            <div class="container mx-auto px-6">
                <div class="max-w-6xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <!-- Left Content -->
                        <div class="reveal">
                            <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">
                                About The Event
                            </span>
                            <h2 class="text-4xl sm:text-5xl font-bold leading-tight mb-6">
                                Rise As 
                                <span class="gradient-text">Watchmen</span>
                                <br>In Your Generation
                            </h2>
                            <p class="text-lg text-white/70 leading-relaxed mb-6">
                                MISCON26 is the premier student conference organized by <strong class="text-white">Public Campus Ministries (PCM)</strong> 
                                under the North Zimbabwe Conference of the Seventh-day Adventist Church.
                            </p>
                            <p class="text-lg text-white/70 leading-relaxed mb-8">
                                This year's theme, <strong class="text-miscon-gold">"Watchmen On The Wall"</strong>, calls upon 
                                young people to stand as spiritual guardians in their campuses, communities, and nations. Join us for 
                                four transformative days of powerful preaching, worship, fellowship, and spiritual renewal.
                            </p>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-6">
                                <div class="text-center">
                                    <span class="block text-4xl font-bold gradient-text">4</span>
                                    <span class="text-sm text-white/60">Days</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-4xl font-bold gradient-text">6+</span>
                                    <span class="text-sm text-white/60">Speakers</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-4xl font-bold gradient-text">1000+</span>
                                    <span class="text-sm text-white/60">Expected</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Content - Featured Image -->
                        <div class="reveal-right relative">
                            <div class="relative rounded-3xl overflow-hidden glow-pcm">
                                <div class="aspect-[4/5] bg-gradient-to-br from-pcm-blue via-pcm-purple to-pcm-pink p-1 rounded-3xl">
                                    <div class="w-full h-full bg-miscon-navy rounded-3xl flex items-center justify-center overflow-hidden">
                                        <img src="{{ asset('images/main-speaker.jpg') }}" 
                                             alt="Dr. Kabani - Main Speaker" 
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="hidden flex-col items-center justify-center text-center p-8">
                                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-pcm-blue via-pcm-purple to-pcm-pink flex items-center justify-center mb-6">
                                                <span class="text-4xl font-bold">DK</span>
                                            </div>
                                            <h3 class="text-2xl font-bold">Dr. Kabani</h3>
                                            <p class="text-miscon-gold">Main Speaker</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Floating Badge -->
                                <div class="absolute -bottom-4 -right-4 glass rounded-2xl p-4 animate-float">
                                    <p class="text-sm font-medium text-miscon-gold">Main Speaker</p>
                                    <p class="text-xl font-bold">Dr. Kabani</p>
                                </div>
                            </div>
                            
                            <!-- Decorative Elements -->
                            <div class="absolute -top-8 -left-8 w-24 h-24 border-2 border-miscon-gold/30 rounded-full float-element-delayed"></div>
                            <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-miscon-gold/20 rounded-full blur-xl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Speakers Section -->
        <section id="speakers" class="py-24 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-pcm-purple/10 rounded-full blur-[150px]"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-pcm-pink/10 rounded-full blur-[150px]"></div>
            </div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center mb-16 reveal">
                    <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">
                        Meet Our Speakers
                    </span>
                    <h2 class="text-4xl sm:text-5xl font-bold">
                        Anointed <span class="gradient-text">Voices</span>
                    </h2>
                    <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">
                        Learn from experienced ministers and scholars who will share powerful messages to equip you for your calling.
                    </p>
                </div>

                <!-- Main Speaker Feature -->
                <div class="max-w-4xl mx-auto mb-16 reveal">
                    <div class="glass rounded-3xl p-8 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-pcm-blue/20 via-pcm-purple/20 to-pcm-pink/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex flex-col md:flex-row items-center gap-8">
                            <div class="w-48 h-48 md:w-56 md:h-56 rounded-2xl overflow-hidden flex-shrink-0 ring-4 ring-miscon-gold/30">
                                <img src="{{ asset('images/dr-kabani.jpg') }}" 
                                     alt="Dr. Kabani" 
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-full h-full bg-gradient-to-br from-pcm-blue via-pcm-purple to-pcm-pink items-center justify-center">
                                    <span class="text-5xl font-bold">DK</span>
                                </div>
                            </div>
                            <div class="text-center md:text-left flex-1">
                                <span class="inline-block px-3 py-1 rounded-full bg-miscon-gold text-miscon-navy text-xs font-bold uppercase tracking-wider mb-3">
                                    Main Speaker
                                </span>
                                <h3 class="text-3xl sm:text-4xl font-bold mb-2">Dr. Kabani</h3>
                                <p class="text-white/60 mb-4">Dynamic preacher and spiritual leader whose messages transform lives and ignite revival in the hearts of young people across the region.</p>
                                <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                                    <span class="px-3 py-1 rounded-full glass text-sm">Keynote Sessions</span>
                                    <span class="px-3 py-1 rounded-full glass text-sm">Evening Devotionals</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Speakers Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Speaker 1 -->
                    <div class="speaker-card glass rounded-2xl p-6 reveal animation-delay-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-pcm-blue to-pcm-purple flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold">NM</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold">Dr. N. Matinhira</h4>
                                <p class="text-sm text-miscon-gold">Guest Speaker</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/60">Bringing wisdom and insight to inspire the next generation of spiritual leaders.</p>
                    </div>

                    <!-- Speaker 2 -->
                    <div class="speaker-card glass rounded-2xl p-6 reveal animation-delay-200">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-pcm-purple to-pcm-pink flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold">CM</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold">Elder Chenge Matondo</h4>
                                <p class="text-sm text-miscon-gold">Guest Speaker</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/60">A passionate minister dedicated to youth empowerment and spiritual development.</p>
                    </div>

                    <!-- Speaker 3 -->
                    <div class="speaker-card glass rounded-2xl p-6 reveal animation-delay-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-pcm-pink to-miscon-gold flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold">MM</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold">Elder M. Machando</h4>
                                <p class="text-sm text-miscon-gold">Guest Speaker</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/60">Equipping young people with practical tools for Christian living on campus.</p>
                    </div>

                    <!-- Speaker 4 -->
                    <div class="speaker-card glass rounded-2xl p-6 reveal animation-delay-400">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-miscon-gold to-pcm-blue flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold">DM</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold">Dr. Matanda</h4>
                                <p class="text-sm text-miscon-gold">Guest Speaker</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/60">Sharing profound biblical insights for modern-day challenges.</p>
                    </div>

                    <!-- Speaker 5 -->
                    <div class="speaker-card glass rounded-2xl p-6 reveal animation-delay-500">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-pcm-blue to-miscon-gold flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold">DM</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold">Dr. Mapondera</h4>
                                <p class="text-sm text-miscon-gold">Guest Speaker</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/60">A voice of encouragement calling youth to their divine purpose.</p>
                    </div>

                    <!-- More Speakers Coming -->
                    <div class="speaker-card glass rounded-2xl p-6 reveal animation-delay-600 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-xl bg-white/5 border-2 border-dashed border-white/20 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <p class="text-white/60 text-sm">More speakers to be announced</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Schedule Section -->
        <section id="schedule" class="py-24 relative">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16 reveal">
                    <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">
                        Event Schedule
                    </span>
                    <h2 class="text-4xl sm:text-5xl font-bold">
                        Four Days of <span class="gradient-text">Transformation</span>
                    </h2>
                </div>

                <!-- Schedule Tabs -->
                <div class="max-w-4xl mx-auto" x-data="{ activeDay: 'day1' }">
                    <!-- Day Selector -->
                    <div class="flex flex-wrap justify-center gap-3 mb-12 reveal">
                        <button @click="activeDay = 'day1'" 
                                class="px-6 py-3 rounded-full font-medium transition-all duration-300"
                                :class="activeDay === 'day1' ? 'bg-miscon-gold text-miscon-navy' : 'glass hover:border-miscon-gold/50'">
                            <span class="block text-xs uppercase tracking-wider opacity-70">Thursday</span>
                            <span class="block font-bold">April 3</span>
                        </button>
                        <button @click="activeDay = 'day2'" 
                                class="px-6 py-3 rounded-full font-medium transition-all duration-300"
                                :class="activeDay === 'day2' ? 'bg-miscon-gold text-miscon-navy' : 'glass hover:border-miscon-gold/50'">
                            <span class="block text-xs uppercase tracking-wider opacity-70">Friday</span>
                            <span class="block font-bold">April 4</span>
                        </button>
                        <button @click="activeDay = 'day3'" 
                                class="px-6 py-3 rounded-full font-medium transition-all duration-300"
                                :class="activeDay === 'day3' ? 'bg-miscon-gold text-miscon-navy' : 'glass hover:border-miscon-gold/50'">
                            <span class="block text-xs uppercase tracking-wider opacity-70">Sabbath</span>
                            <span class="block font-bold">April 5</span>
                        </button>
                        <button @click="activeDay = 'day4'" 
                                class="px-6 py-3 rounded-full font-medium transition-all duration-300"
                                :class="activeDay === 'day4' ? 'bg-miscon-gold text-miscon-navy' : 'glass hover:border-miscon-gold/50'">
                            <span class="block text-xs uppercase tracking-wider opacity-70">Sunday</span>
                            <span class="block font-bold">April 6</span>
                        </button>
                    </div>

                    <!-- Day 1 Schedule -->
                    <div x-show="activeDay === 'day1'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="space-y-4">
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">14:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Registration & Check-in</h4>
                                <p class="text-white/60 text-sm">Welcome delegates and assign accommodations</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Arrival</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">17:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Opening Ceremony</h4>
                                <p class="text-white/60 text-sm">Official opening and welcome address</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Ceremony</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">19:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Evening Devotional</h4>
                                <p class="text-white/60 text-sm">Speaker: Dr. Kabani</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Main Session</span>
                        </div>
                    </div>

                    <!-- Day 2 Schedule -->
                    <div x-show="activeDay === 'day2'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="space-y-4">
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">05:30</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Morning Devotion</h4>
                                <p class="text-white/60 text-sm">Prayer and meditation session</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Devotion</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">09:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Plenary Session</h4>
                                <p class="text-white/60 text-sm">Speaker: Dr. N. Matinhira</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Session</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">14:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Breakout Sessions</h4>
                                <p class="text-white/60 text-sm">Workshops and small group discussions</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Workshop</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">19:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Evening Service</h4>
                                <p class="text-white/60 text-sm">Speaker: Dr. Kabani</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Main Session</span>
                        </div>
                    </div>

                    <!-- Day 3 Schedule (Sabbath) -->
                    <div x-show="activeDay === 'day3'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="space-y-4">
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">05:30</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Early Morning Prayer</h4>
                                <p class="text-white/60 text-sm">Consecration and preparation for Sabbath</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Prayer</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">09:30</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Sabbath School</h4>
                                <p class="text-white/60 text-sm">Interactive Bible study session</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Study</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">11:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Divine Service</h4>
                                <p class="text-white/60 text-sm">Speaker: Dr. Kabani</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Main Session</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">15:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Afternoon Program</h4>
                                <p class="text-white/60 text-sm">Special presentations and testimonies</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-purple/20 text-pcm-purple text-xs font-medium">Program</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">18:30</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Sunset Vespers & AY Program</h4>
                                <p class="text-white/60 text-sm">Welcoming the end of Sabbath</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Worship</span>
                        </div>
                    </div>

                    <!-- Day 4 Schedule -->
                    <div x-show="activeDay === 'day4'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="space-y-4">
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">06:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Morning Devotion</h4>
                                <p class="text-white/60 text-sm">Final morning reflection</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-pink/20 text-pcm-pink text-xs font-medium">Devotion</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">09:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Closing Session</h4>
                                <p class="text-white/60 text-sm">Final charge and commissioning</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-miscon-gold/20 text-miscon-gold text-xs font-medium">Closing</span>
                        </div>
                        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="text-miscon-gold font-mono text-lg font-bold min-w-[100px]">12:00</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg">Departure</h4>
                                <p class="text-white/60 text-sm">Safe travels and blessings</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-pcm-blue/20 text-pcm-blue text-xs font-medium">Departure</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration Section -->
        <section id="registration" class="py-24 relative overflow-hidden">
            <!-- Background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-radial from-pcm-purple/20 via-transparent to-transparent"></div>
            </div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center mb-16 reveal">
                    <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">
                        Registration
                    </span>
                    <h2 class="text-4xl sm:text-5xl font-bold">
                        Secure Your <span class="gradient-text">Spot</span>
                    </h2>
                    <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">
                        Don't miss this life-changing experience. Register now and be part of MISCON26!
                    </p>
                </div>

                <!-- Pricing Cards -->
                <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8">
                    <!-- Student Price -->
                    <div class="reveal animation-delay-100">
                        <div class="price-tag h-full">
                            <div class="price-tag-inner flex flex-col">
                                <div class="text-center mb-8">
                                    <span class="text-sm uppercase tracking-wider text-white/60">Students</span>
                                    <div class="mt-4 flex items-baseline justify-center gap-1">
                                        <span class="text-5xl font-bold gradient-text">$45</span>
                                        <span class="text-white/60">USD</span>
                                    </div>
                                </div>
                                
                                <ul class="space-y-4 mb-8 flex-1">
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Full conference access</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Accommodation (4 nights)</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Meals included</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Conference materials</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Certificate of attendance</span>
                                    </li>
                                </ul>

                                <a href="#contact" class="btn-primary w-full text-center">
                                    <span class="relative z-10">Register as Student</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Alumni Price -->
                    <div class="reveal animation-delay-200">
                        <div class="price-tag h-full">
                            <div class="price-tag-inner flex flex-col">
                                <div class="text-center mb-8">
                                    <span class="text-sm uppercase tracking-wider text-white/60">Alumni</span>
                                    <div class="mt-4 flex items-baseline justify-center gap-1">
                                        <span class="text-5xl font-bold gradient-text">$65</span>
                                        <span class="text-white/60">USD</span>
                                    </div>
                                </div>
                                
                                <ul class="space-y-4 mb-8 flex-1">
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Full conference access</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Accommodation (4 nights)</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Meals included</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Conference materials</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Certificate of attendance</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-miscon-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-white/80">Alumni networking session</span>
                                    </li>
                                </ul>

                                <a href="#contact" class="btn-primary w-full text-center">
                                    <span class="relative z-10">Register as Alumni</span>
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
                        <span class="inline-block px-4 py-1 rounded-full bg-miscon-gold/10 text-miscon-gold text-sm font-medium tracking-wider uppercase mb-6">
                            Get In Touch
                        </span>
                        <h2 class="text-4xl sm:text-5xl font-bold">
                            Contact <span class="gradient-text">Us</span>
                        </h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8 reveal">
                        <!-- Contact Info -->
                        <div class="glass rounded-3xl p-8">
                            <h3 class="text-2xl font-bold mb-6">Event Information</h3>
                            
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-miscon-gold/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Venue</h4>
                                        <p class="text-white/70">Amai Mugabe Group of Schools</p>
                                        <p class="text-white/50 text-sm">North Zimbabwe Conference</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-miscon-gold/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Phone</h4>
                                        <a href="tel:+263782504742" class="text-miscon-gold hover:underline">+263 78 250 4742</a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-miscon-gold/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Date</h4>
                                        <p class="text-white/70">April 3-6, 2026</p>
                                        <p class="text-white/50 text-sm">Thursday to Sunday</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="mt-8 pt-8 border-t border-white/10">
                                <h4 class="font-semibold mb-4">Follow PCM</h4>
                                <div class="flex gap-4">
                                    <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-miscon-gold/50 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-miscon-gold/50 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                    <a href="https://wa.me/263782504742" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-miscon-gold/50 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Registration Form -->
                        <div class="glass rounded-3xl p-8">
                            <h3 class="text-2xl font-bold mb-6">Quick Inquiry</h3>
                            <form class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Full Name</label>
                                    <input type="text" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all" placeholder="Your name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Email Address</label>
                                    <input type="email" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all" placeholder="your@email.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Phone Number</label>
                                    <input type="tel" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all" placeholder="+263...">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">I am registering as</label>
                                    <select class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-miscon-gold/50 focus:outline-none focus:ring-2 focus:ring-miscon-gold/20 transition-all">
                                        <option value="" class="bg-miscon-navy">Select type</option>
                                        <option value="student" class="bg-miscon-navy">Student ($45)</option>
                                        <option value="alumni" class="bg-miscon-navy">Alumni ($65)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-primary w-full">
                                    <span class="relative z-10">Submit Inquiry</span>
                                </button>
                            </form>
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
                        <!-- PCM Logo Small -->
                        <div class="w-10 h-12">
                            <svg viewBox="0 0 100 120" class="w-full h-full">
                                <defs>
                                    <linearGradient id="pcmGradientFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#3b82f6"/>
                                        <stop offset="50%" style="stop-color:#8b5cf6"/>
                                        <stop offset="100%" style="stop-color:#ec4899"/>
                                    </linearGradient>
                                </defs>
                                <path d="M50 5 L95 20 L95 60 Q95 95 50 115 Q5 95 5 60 L5 20 Z" 
                                      fill="url(#pcmGradientFooter)" stroke="white" stroke-width="3"/>
                                <text x="50" y="45" text-anchor="middle" fill="white" font-family="Outfit" font-weight="bold" font-size="20">PCM</text>
                                <path d="M30 60 L50 55 L70 60 L70 80 L50 75 L30 80 Z" fill="none" stroke="white" stroke-width="2"/>
                                <line x1="50" y1="55" x2="50" y2="75" stroke="white" stroke-width="2"/>
                                <path d="M35 50 L50 42 L65 50 L50 58 Z" fill="white"/>
                                <line x1="62" y1="50" x2="62" y2="60" stroke="white" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">MISCON<span class="text-miscon-gold">26</span></p>
                            <p class="text-xs text-white/60">Public Campus Ministries</p>
                        </div>
                    </div>

                    <p class="text-white/60 text-sm text-center">
                        &copy; 2026 North Zimbabwe Conference | Seventh-day Adventist Church
                    </p>

                    <div class="flex items-center gap-6 text-sm text-white/60">
                        <a href="#" class="hover:text-white transition-colors">Privacy</a>
                        <a href="#" class="hover:text-white transition-colors">Terms</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scroll to Top Button -->
        <button x-show="showScrollTop"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="fixed bottom-8 right-8 w-12 h-12 rounded-full bg-miscon-gold text-miscon-navy flex items-center justify-center shadow-lg hover:shadow-[0_0_30px_rgba(212,175,55,0.5)] transition-all duration-300 z-50"
                x-cloak>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <script>
            // Countdown Timer
            function countdown() {
                return {
                    days: '00',
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    init() {
                        this.updateCountdown();
                        setInterval(() => this.updateCountdown(), 1000);
                    },
                    updateCountdown() {
                        const eventDate = new Date('April 3, 2026 00:00:00').getTime();
                        const now = new Date().getTime();
                        const distance = eventDate - now;

                        if (distance < 0) {
                            this.days = '00';
                            this.hours = '00';
                            this.minutes = '00';
                            this.seconds = '00';
                            return;
                        }

                        this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                    }
                }
            }

            // Intersection Observer for reveal animations
            document.addEventListener('DOMContentLoaded', function() {
                const observerOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.1
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
                    observer.observe(el);
                });
            });

            // Smooth scroll for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Parallax effect for floating elements
            document.addEventListener('mousemove', (e) => {
                const particles = document.querySelectorAll('.particle');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                particles.forEach((particle, index) => {
                    const speed = (index + 1) * 0.01;
                    const xOffset = (x - 0.5) * 100 * speed;
                    const yOffset = (y - 0.5) * 100 * speed;
                    particle.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });
            });
        </script>
    </body>
</html>
