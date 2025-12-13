<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabFlow - Laboratory Management Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 0.3;
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        .animate-pulse-slow {
            animation: pulse 7s ease-in-out infinite;
        }

        .animate-pulse-slower {
            animation: pulse 9s ease-in-out infinite;
            animation-delay: 2s;
        }

        .animate-pulse-slowest {
            animation: pulse 11s ease-in-out infinite;
            animation-delay: 4s;
        }

        .fade-in-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white overflow-x-hidden">

    <!-- Animated background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse-slow"
            style="top: 10%; left: 10%;"></div>
        <div class="absolute w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse-slower"
            style="top: 60%; right: 10%;"></div>
        <div class="absolute w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse-slowest"
            style="bottom: 10%; left: 30%;"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-10 px-6 py-6 backdrop-blur-sm bg-white/5 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                    </path>
                </svg>
                <span
                    class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                    LabFlow
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#"
                    class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-purple-500 rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition-all duration-300 hover:scale-105">
                    Get Started
                </a>

                <a href="/buy-now"
                    class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-red-500 rounded-full font-semibold hover:shadow-lg hover:shadow-red-500/50 transition-all duration-300 hover:scale-105">
                    Buy Now
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative z-10 px-6 pt-20 pb-32">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-block mb-6 opacity-0" style="animation: fadeIn 1s ease-out forwards;">
                <span
                    class="px-4 py-2 bg-gradient-to-r from-cyan-500/20 to-purple-500/20 border border-cyan-500/30 rounded-full text-sm font-medium backdrop-blur-sm">
                    ✨ Next-Gen Laboratory Management
                </span>
            </div>

            <h1 class="text-6xl md:text-7xl font-bold mb-6 opacity-0"
                style="animation: fadeIn 1s ease-out 0.2s forwards;">
                <span class="bg-gradient-to-r from-cyan-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">
                    Transform Your Lab
                </span>
                <br />
                <span class="text-white">Into a Data Powerhouse</span>
            </h1>

            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto opacity-0"
                style="animation: fadeIn 1s ease-out 0.4s forwards;">
                Seamless role management and dynamic reporting at your fingertips.
                Built for modern laboratories that demand precision and speed.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center opacity-0"
                style="animation: fadeIn 1s ease-out 0.6s forwards;">
                <a href="<?php echo e(route('signup')); ?>"
                    class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-cyan-500 to-purple-500 rounded-full font-bold text-lg hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 hover:scale-105 group">
                    Start Free Trial
                    <svg class="inline-block ml-2 w-5 h-5 group-hover:rotate-12 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                </a>
                <a href="#"
                    class="inline-flex items-center justify-center px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full font-bold text-lg hover:bg-white/20 transition-all duration-300 hover:scale-105">
                    Watch Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="relative z-10 px-6 py-20" id="features">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-5xl font-bold text-center mb-4 fade-in-on-scroll">
                <span class="bg-gradient-to-r from-cyan-300 to-purple-300 bg-clip-text text-transparent">
                    Powerful Features
                </span>
            </h2>
            <p class="text-center text-gray-400 mb-16 text-lg fade-in-on-scroll">
                Everything you need to run your laboratory efficiently
            </p>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- User Role Card -->
                <div
                    class="group relative p-8 rounded-3xl bg-gradient-to-br from-cyan-500/10 to-purple-500/10 backdrop-blur-sm border border-white/10 hover:border-cyan-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-cyan-500/20 fade-in-on-scroll">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-purple-500/0 group-hover:from-cyan-500/5 group-hover:to-purple-500/5 rounded-3xl transition-all duration-500">
                    </div>
                    <div class="relative">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="text-3xl font-bold mb-4 bg-gradient-to-r from-cyan-300 to-cyan-100 bg-clip-text text-transparent">
                            User Access
                        </h3>
                        <p class="text-gray-300 text-lg leading-relaxed mb-6">
                            Streamlined interface for lab technicians to input data, view assigned tasks, and generate
                            standard reports with intuitive controls and real-time validation.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-gray-300">
                                <div class="w-2 h-2 bg-cyan-400 rounded-full"></div>
                                <span>Quick data entry forms</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-300">
                                <div class="w-2 h-2 bg-cyan-400 rounded-full"></div>
                                <span>Personal dashboard</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-300">
                                <div class="w-2 h-2 bg-cyan-400 rounded-full"></div>
                                <span>Standard report access</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Admin Role Card -->
                <div class="group relative p-8 rounded-3xl bg-gradient-to-br from-purple-500/10 to-pink-500/10 backdrop-blur-sm border border-white/10 hover:border-purple-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-purple-500/20 fade-in-on-scroll"
                    style="transition-delay: 0.2s;">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-pink-500/0 group-hover:from-purple-500/5 group-hover:to-pink-500/5 rounded-3xl transition-all duration-500">
                    </div>
                    <div class="relative">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="text-3xl font-bold mb-4 bg-gradient-to-r from-purple-300 to-pink-300 bg-clip-text text-transparent">
                            Admin Control
                        </h3>
                        <p class="text-gray-300 text-lg leading-relaxed mb-6">
                            Complete oversight with advanced user management, system configuration, comprehensive
                            analytics, and full control over all laboratory operations.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-gray-300">
                                <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                                <span>User role management</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-300">
                                <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                                <span>System configuration</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-300">
                                <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                                <span>Full analytics access</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Dynamic Reports Card - Full Width -->
                <div class="group relative md:col-span-2 p-8 rounded-3xl bg-gradient-to-br from-pink-500/10 via-purple-500/10 to-cyan-500/10 backdrop-blur-sm border border-white/10 hover:border-pink-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-pink-500/20 fade-in-on-scroll"
                    style="transition-delay: 0.4s;">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-pink-500/0 via-purple-500/0 to-cyan-500/0 group-hover:from-pink-500/5 group-hover:via-purple-500/5 group-hover:to-cyan-500/5 rounded-3xl transition-all duration-500">
                    </div>
                    <div class="relative">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="text-3xl font-bold mb-4 bg-gradient-to-r from-pink-300 via-purple-300 to-cyan-300 bg-clip-text text-transparent">
                            Dynamic Report Creation
                        </h3>
                        <p class="text-gray-300 text-lg leading-relaxed mb-6">
                            Create custom reports on-the-fly with our intelligent report builder. Filter, sort, and
                            visualize your laboratory data exactly how you need it, with export options for multiple
                            formats.
                        </p>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                                <div class="text-2xl font-bold text-pink-400 mb-1">Custom Filters</div>
                                <div class="text-sm text-gray-400">Advanced query builder</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                                <div class="text-2xl font-bold text-purple-400 mb-1">Visual Charts</div>
                                <div class="text-sm text-gray-400">Interactive data viz</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                                <div class="text-2xl font-bold text-cyan-400 mb-1">Multi-Export</div>
                                <div class="text-sm text-gray-400">PDF, Excel, CSV</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative z-10 px-6 py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div
                class="relative p-12 rounded-3xl bg-gradient-to-br from-cyan-500/20 via-purple-500/20 to-pink-500/20 backdrop-blur-sm border border-white/20 fade-in-on-scroll">
                <h2 class="text-5xl font-bold mb-6">
                    <span
                        class="bg-gradient-to-r from-cyan-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">
                        Ready to Transform Your Lab?
                    </span>
                </h2>
                <p class="text-xl text-gray-300 mb-8">
                    Join laboratories worldwide that trust LabFlow for their data management needs
                </p>
                <a href="<?php echo e(route('signup')); ?>"
                    class="inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-cyan-500 to-purple-500 rounded-full font-bold text-xl hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 hover:scale-105 group">
                    Start Your Free Trial
                    <svg class="inline-block ml-2 w-6 h-6 group-hover:rotate-12 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 px-6 py-8 border-t border-white/10 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto text-center text-gray-400">
            <p>© 2024 LabFlow. Empowering laboratories with intelligent software.</p>
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\resources\views/welcome.blade.php ENDPATH**/ ?>