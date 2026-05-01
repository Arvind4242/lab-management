<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — LabMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif'] } } } }</script>
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen flex">

<div class="flex flex-1">

    {{-- Left brand panel (hidden on mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 flex-col items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-300 rounded-full filter blur-3xl"></div>
        </div>
        <div class="relative z-10 text-center max-w-md">
            <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white mb-4">LabMS</h1>
            <p class="text-indigo-200 text-lg leading-relaxed">Complete laboratory management platform for modern diagnostic labs</p>
            <div class="mt-10 grid grid-cols-2 gap-4">
                <div class="bg-white/10 rounded-2xl p-4 text-left">
                    <div class="text-2xl font-bold text-white">500+</div>
                    <div class="text-indigo-300 text-sm mt-1">Labs using LabMS</div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 text-left">
                    <div class="text-2xl font-bold text-white">50K+</div>
                    <div class="text-indigo-300 text-sm mt-1">Reports generated</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <span class="text-xl font-bold text-gray-900">LabMS</span>
            </div>
            @yield('form')
        </div>
    </div>
</div>
</body>
</html>
