<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registration Details | MISCON26 Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- Background -->
    <div class="fixed inset-0 bg-mesh -z-10"></div>
    
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-white/60 hover:text-white mb-8 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>

            <!-- Header -->
            <div class="glass rounded-2xl p-6 sm:p-8 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-white/60 text-sm mb-1">Registration Reference</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-miscon-gold font-mono">{{ $registration->reference }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusColors = [
                                'completed' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                'processing' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                'failed' => 'bg-red-500/20 text-red-400 border-red-500/30',
                            ];
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border {{ $statusColors[$registration->payment_status] ?? '' }}">
                            @if($registration->payment_status === 'completed')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($registration->payment_status === 'pending')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($registration->payment_status === 'processing')
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                            {{ ucfirst($registration->payment_status) }}
                        </span>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold
                            {{ $registration->type === 'student' ? 'bg-pcm-blue/20 text-pcm-blue border border-pcm-blue/30' : 'bg-pcm-purple/20 text-pcm-purple border border-pcm-purple/30' }}">
                            {{ ucfirst($registration->type) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="glass rounded-2xl p-6">
                    <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Personal Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pcm-blue via-pcm-purple to-pcm-pink flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr($registration->full_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-lg">{{ $registration->full_name }}</p>
                                <p class="text-sm text-white/60">{{ $registration->email ?? 'No email provided' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-white/5">
                                <p class="text-xs text-white/40 mb-1">Phone</p>
                                <p class="font-medium">{{ $registration->phone }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-white/5">
                                <p class="text-xs text-white/40 mb-1">Gender</p>
                                <p class="font-medium">{{ ucfirst($registration->gender) }}</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">{{ $registration->getIdLabel() }}</p>
                            <p class="font-medium font-mono">{{ $registration->id_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="glass rounded-2xl p-6">
                    <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Academic Information
                    </h2>

                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">University/Institution</p>
                            <p class="font-medium">{{ $registration->university }}</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">{{ $registration->getLevelLabel() }}</p>
                            <p class="font-medium">{{ $registration->level }}</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">Registration Type</p>
                            <div class="flex items-center gap-2 mt-1">
                                @if($registration->type === 'student')
                                    <svg class="w-5 h-5 text-pcm-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-pcm-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                                <span class="font-medium {{ $registration->type === 'student' ? 'text-pcm-blue' : 'text-pcm-purple' }}">
                                    {{ ucfirst($registration->type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="glass rounded-2xl p-6 lg:col-span-2">
                    <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payment Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-miscon-gold/10 border border-miscon-gold/20">
                            <p class="text-xs text-white/40 mb-1">Amount</p>
                            <p class="text-2xl font-bold text-miscon-gold">${{ number_format($registration->amount, 2) }}</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">Payment Method</p>
                            <p class="font-medium">{{ $registration->payment_method ? ucfirst($registration->payment_method) : 'Not specified' }}</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">Payment Phone</p>
                            <p class="font-medium">{{ $registration->payment_phone ?? 'Not specified' }}</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">Paynow Reference</p>
                            <p class="font-medium font-mono text-sm">{{ $registration->paynow_reference ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($registration->paid_at)
                        <div class="mt-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                            <div class="flex items-center gap-2 text-green-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <p class="font-medium">Payment completed on {{ $registration->paid_at->format('F j, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Timestamps -->
                <div class="glass rounded-2xl p-6 lg:col-span-2">
                    <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-miscon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timeline
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">Registered At</p>
                            <p class="font-medium">{{ $registration->created_at->format('F j, Y') }}</p>
                            <p class="text-sm text-white/60">{{ $registration->created_at->format('h:i A') }}</p>
                            <p class="text-xs text-white/40 mt-2">{{ $registration->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex-1 p-4 rounded-xl bg-white/5">
                            <p class="text-xs text-white/40 mb-1">Last Updated</p>
                            <p class="font-medium">{{ $registration->updated_at->format('F j, Y') }}</p>
                            <p class="text-sm text-white/60">{{ $registration->updated_at->format('h:i A') }}</p>
                            <p class="text-xs text-white/40 mt-2">{{ $registration->updated_at->diffForHumans() }}</p>
                        </div>

                        @if($registration->paid_at)
                            <div class="flex-1 p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                                <p class="text-xs text-green-400/60 mb-1">Paid At</p>
                                <p class="font-medium text-green-400">{{ $registration->paid_at->format('F j, Y') }}</p>
                                <p class="text-sm text-green-400/60">{{ $registration->paid_at->format('h:i A') }}</p>
                                <p class="text-xs text-green-400/40 mt-2">{{ $registration->paid_at->diffForHumans() }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admin.dashboard') }}" class="btn-outline text-center">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('admin.export', ['search' => $registration->reference]) }}" class="btn-primary text-center">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Details
                    </span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
