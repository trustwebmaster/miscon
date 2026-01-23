<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | MISCON26</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased" x-data="{ sidebarOpen: false, showFilters: false }">
    <!-- Background -->
    <div class="fixed inset-0 bg-mesh -z-10"></div>
    
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 lg:translate-x-0 lg:static"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-full glass-dark flex flex-col">
                <!-- Logo -->
                <div class="p-6 border-b border-white/10">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="w-10 h-12">
                            <svg viewBox="0 0 100 120" class="w-full h-full">
                                <defs>
                                    <linearGradient id="pcmGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#3b82f6"/>
                                        <stop offset="50%" style="stop-color:#8b5cf6"/>
                                        <stop offset="100%" style="stop-color:#ec4899"/>
                                    </linearGradient>
                                </defs>
                                <path d="M50 5 L95 20 L95 60 Q95 95 50 115 Q5 95 5 60 L5 20 Z" fill="url(#pcmGrad)" stroke="white" stroke-width="3"/>
                                <text x="50" y="45" text-anchor="middle" fill="white" font-family="Outfit" font-weight="bold" font-size="20">PCM</text>
                                <path d="M30 60 L50 55 L70 60 L70 80 L50 75 L30 80 Z" fill="none" stroke="white" stroke-width="2"/>
                                <line x1="50" y1="55" x2="50" y2="75" stroke="white" stroke-width="2"/>
                                <path d="M35 50 L50 42 L65 50 L50 58 Z" fill="white"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold">MISCON</span>
                            <span class="text-xl font-bold text-miscon-gold">26</span>
                            <p class="text-xs text-white/60">Admin Panel</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl bg-miscon-gold/20 text-miscon-gold border border-miscon-gold/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.export', request()->query()) }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium">Export CSV</span>
                    </a>

                    <a href="{{ url('/') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="font-medium">View Website</span>
                    </a>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t border-white/10">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pcm-blue via-pcm-purple to-pcm-pink flex items-center justify-center">
                            <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/60 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-red-400 hover:bg-red-500/10 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-sm font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"
             x-cloak></div>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen">
            <!-- Top Bar -->
            <header class="sticky top-0 z-30 glass-dark border-b border-white/10">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-white/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-bold">
                            <span class="gradient-text">Dashboard</span>
                        </h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-white/60 hidden sm:block">{{ now()->format('l, F j, Y') }}</span>
                        <button @click="showFilters = !showFilters" 
                                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all lg:hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="text-sm font-medium">Filters</span>
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                    <!-- Total Students -->
                    <div class="glass rounded-2xl p-6 hover:border-pcm-blue/50 transition-all group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-white/60 text-sm font-medium">Total Students</p>
                                <p class="text-3xl sm:text-4xl font-bold mt-2 text-pcm-blue">{{ number_format($stats['total_students']) }}</p>
                                <p class="text-xs text-white/40 mt-1">{{ $stats['paid_students'] }} paid</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-pcm-blue/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-pcm-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-pcm-blue rounded-full transition-all" 
                                 style="width: {{ $stats['total_students'] > 0 ? ($stats['paid_students'] / $stats['total_students']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Total Alumni -->
                    <div class="glass rounded-2xl p-6 hover:border-pcm-purple/50 transition-all group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-white/60 text-sm font-medium">Total Alumni</p>
                                <p class="text-3xl sm:text-4xl font-bold mt-2 text-pcm-purple">{{ number_format($stats['total_alumni']) }}</p>
                                <p class="text-xs text-white/40 mt-1">{{ $stats['paid_alumni'] }} paid</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-pcm-purple/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-pcm-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-pcm-purple rounded-full transition-all" 
                                 style="width: {{ $stats['total_alumni'] > 0 ? ($stats['paid_alumni'] / $stats['total_alumni']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="glass rounded-2xl p-6 hover:border-miscon-gold/50 transition-all group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-white/60 text-sm font-medium">Amount Collected</p>
                                <p class="text-3xl sm:text-4xl font-bold mt-2 text-miscon-gold">${{ number_format($stats['total_amount_collected'], 2) }}</p>
                                <p class="text-xs text-white/40 mt-1">${{ number_format($stats['pending_amount'], 2) }} pending</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-miscon-gold/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2 text-xs">
                            <span class="px-2 py-1 rounded-lg bg-pcm-blue/20 text-pcm-blue">Students: ${{ number_format($stats['student_amount_collected'], 2) }}</span>
                            <span class="px-2 py-1 rounded-lg bg-pcm-purple/20 text-pcm-purple">Alumni: ${{ number_format($stats['alumni_amount_collected'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Total Registrations -->
                    <div class="glass rounded-2xl p-6 hover:border-pcm-pink/50 transition-all group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-white/60 text-sm font-medium">Total Registrations</p>
                                <p class="text-3xl sm:text-4xl font-bold mt-2 text-pcm-pink">{{ number_format($stats['total_registrations']) }}</p>
                                <p class="text-xs text-white/40 mt-1">{{ $stats['today_registrations'] }} today</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-pcm-pink/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-pcm-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-4 text-xs">
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                <span class="text-white/60">{{ $stats['male_count'] }} Male</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-pink-400"></span>
                                <span class="text-white/60">{{ $stats['female_count'] }} Female</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Row -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                    <div class="glass rounded-xl p-4 text-center">
                        <p class="text-green-400 text-2xl font-bold">{{ $stats['payment_breakdown']['completed'] ?? 0 }}</p>
                        <p class="text-xs text-white/60 mt-1">Completed</p>
                    </div>
                    <div class="glass rounded-xl p-4 text-center">
                        <p class="text-yellow-400 text-2xl font-bold">{{ $stats['payment_breakdown']['pending'] ?? 0 }}</p>
                        <p class="text-xs text-white/60 mt-1">Pending</p>
                    </div>
                    <div class="glass rounded-xl p-4 text-center">
                        <p class="text-blue-400 text-2xl font-bold">{{ $stats['payment_breakdown']['processing'] ?? 0 }}</p>
                        <p class="text-xs text-white/60 mt-1">Processing</p>
                    </div>
                    <div class="glass rounded-xl p-4 text-center">
                        <p class="text-red-400 text-2xl font-bold">{{ $stats['payment_breakdown']['failed'] ?? 0 }}</p>
                        <p class="text-xs text-white/60 mt-1">Failed</p>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="glass rounded-2xl p-6 mb-8" :class="{ 'hidden lg:block': !showFilters }" x-cloak>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filters
                            </h3>
                            @if(request()->hasAny(['type', 'payment_status', 'search', 'date_from', 'date_to', 'university']))
                                <a href="{{ route('admin.dashboard') }}" class="text-sm text-miscon-gold hover:underline">Clear all</a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                            <!-- Search -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm text-white/60 mb-2">Search</label>
                                <input type="text" name="search" value="{{ $search }}" 
                                       placeholder="Name, phone, email, ref..."
                                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:border-miscon-gold focus:ring-1 focus:ring-miscon-gold transition-all">
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Type</label>
                                <select name="type" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-miscon-gold focus:ring-1 focus:ring-miscon-gold transition-all">
                                    <option value="">All Types</option>
                                    <option value="student" {{ $type === 'student' ? 'selected' : '' }}>Students</option>
                                    <option value="alumni" {{ $type === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </div>

                            <!-- Payment Status Filter -->
                            <div>
                                <label class="block text-sm text-white/60 mb-2">Payment Status</label>
                                <select name="payment_status" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-miscon-gold focus:ring-1 focus:ring-miscon-gold transition-all">
                                    <option value="">All Status</option>
                                    <option value="completed" {{ $paymentStatus === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $paymentStatus === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label class="block text-sm text-white/60 mb-2">From Date</label>
                                <input type="date" name="date_from" value="{{ $dateFrom }}" 
                                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-miscon-gold focus:ring-1 focus:ring-miscon-gold transition-all">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label class="block text-sm text-white/60 mb-2">To Date</label>
                                <input type="date" name="date_to" value="{{ $dateTo }}" 
                                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-miscon-gold focus:ring-1 focus:ring-miscon-gold transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mt-4">
                            <!-- University Filter -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm text-white/60 mb-2">University</label>
                                <select name="university" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-miscon-gold focus:ring-1 focus:ring-miscon-gold transition-all">
                                    <option value="">All Universities</option>
                                    @foreach($universities as $uni)
                                        <option value="{{ $uni }}" {{ $university === $uni ? 'selected' : '' }}>{{ $uni }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="lg:col-span-4 flex items-end gap-3">
                                <button type="submit" class="btn-primary py-3 px-8">
                                    <span class="relative z-10 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        Apply Filters
                                    </span>
                                </button>
                                <a href="{{ route('admin.export', request()->query()) }}" 
                                   class="btn-outline py-3 px-6 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Registrations Table -->
                <div class="glass rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-white/10">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Registrations
                            </h3>
                            <span class="text-sm text-white/60">
                                Showing {{ $registrations->firstItem() ?? 0 }} - {{ $registrations->lastItem() ?? 0 }} of {{ $registrations->total() }}
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10 text-left">
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Reference</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Name</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Type</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">University</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Phone</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Amount</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Status</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Date</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-white/60">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($registrations as $registration)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm text-miscon-gold">{{ $registration->reference }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-medium">{{ $registration->full_name }}</p>
                                                <p class="text-sm text-white/50">{{ $registration->email ?? 'No email' }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                                {{ $registration->type === 'student' ? 'bg-pcm-blue/20 text-pcm-blue' : 'bg-pcm-purple/20 text-pcm-purple' }}">
                                                {{ ucfirst($registration->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-white/80">{{ $registration->university }}</td>
                                        <td class="px-6 py-4 text-sm text-white/80">{{ $registration->phone }}</td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-miscon-gold">${{ number_format($registration->amount, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'completed' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                                    'processing' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                                    'failed' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$registration->payment_status] ?? '' }}">
                                                @if($registration->payment_status === 'completed')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($registration->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-white/60">
                                            <div>
                                                <p>{{ $registration->created_at->format('M d, Y') }}</p>
                                                <p class="text-xs">{{ $registration->created_at->format('h:i A') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.registration.show', $registration) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-sm transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-16 h-16 text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <p class="text-white/60 text-lg">No registrations found</p>
                                                <p class="text-white/40 text-sm mt-1">Try adjusting your filters</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($registrations->hasPages())
                        <div class="px-6 py-4 border-t border-white/10">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-white/60">
                                    Page {{ $registrations->currentPage() }} of {{ $registrations->lastPage() }}
                                </p>
                                <div class="flex gap-2">
                                    @if($registrations->onFirstPage())
                                        <span class="px-4 py-2 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Previous</span>
                                    @else
                                        <a href="{{ $registrations->previousPageUrl() }}" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all">Previous</a>
                                    @endif

                                    @if($registrations->hasMorePages())
                                        <a href="{{ $registrations->nextPageUrl() }}" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all">Next</a>
                                    @else
                                        <span class="px-4 py-2 rounded-lg bg-white/5 text-white/30 cursor-not-allowed">Next</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        select option {
            background-color: #0a1628;
            color: white;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>
</body>
</html>
