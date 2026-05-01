<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — LabMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif'] } } } }</script>
    <style>[x-cloak]{display:none!important}</style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 font-sans antialiased">

<?php $user = auth()->user(); ?>

<div x-data="{ mobileMenu: false }" class="flex h-screen overflow-hidden">

    
    <div x-show="mobileMenu" @click="mobileMenu=false" x-cloak class="fixed inset-0 bg-black/60 z-40 md:hidden"></div>

    
    <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'" class="fixed md:relative md:translate-x-0 inset-y-0 left-0 z-50 w-64 flex flex-col bg-indigo-900 transition-transform duration-300 ease-in-out flex-shrink-0">

        
        <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-sm">LabMS</h1>
                <p class="text-indigo-300 text-xs truncate max-w-[140px]"><?php echo e($user->lab?->name ?? $user->name); ?></p>
            </div>
        </div>

        
        <nav class="flex-1 px-2 py-4 overflow-y-auto space-y-0.5">

            <?php
                $navLink = fn($href,$label,$icon,$match) => ['href'=>$href,'label'=>$label,'icon'=>$icon,'active'=>request()->routeIs($match)];
            ?>

            <a href="<?php echo e(route('user.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('user.dashboard') ? 'bg-white/15 text-white' : 'text-indigo-200 hover:bg-white/10 hover:text-white'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            
            <div x-data="{ open: <?php echo e(request()->routeIs('user.reports*') ? 'true' : 'false'); ?> }">
                <button @click="open=!open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('user.reports*') ? 'bg-white/15 text-white' : 'text-indigo-200 hover:bg-white/10 hover:text-white'); ?>">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Reports
                    </span>
                    <svg :class="open?'rotate-180':''" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="ml-4 mt-1 space-y-0.5 border-l border-white/20 pl-3">
                    <a href="<?php echo e(route('user.reports.index')); ?>" class="block px-3 py-2 rounded-lg text-xs font-medium <?php echo e(request()->routeIs('user.reports.index') ? 'text-white bg-white/15' : 'text-indigo-300 hover:text-white hover:bg-white/10'); ?>">All Reports</a>
                    <a href="<?php echo e(route('user.reports.create')); ?>" class="block px-3 py-2 rounded-lg text-xs font-medium <?php echo e(request()->routeIs('user.reports.create') ? 'text-white bg-white/15' : 'text-indigo-300 hover:text-white hover:bg-white/10'); ?>">New Report</a>
                </div>
            </div>

            <a href="<?php echo e(route('user.packages.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('user.packages*') ? 'bg-white/15 text-white' : 'text-indigo-200 hover:bg-white/10 hover:text-white'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Test Packages
            </a>

            <a href="<?php echo e(route('user.subscription')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('user.subscription*') ? 'bg-white/15 text-white' : 'text-indigo-200 hover:bg-white/10 hover:text-white'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Subscription
            </a>

            <a href="<?php echo e(route('user.profile')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('user.profile*') ? 'bg-white/15 text-white' : 'text-indigo-200 hover:bg-white/10 hover:text-white'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile
            </a>
        </nav>

        
        <div class="border-t border-white/10 p-3">
            <div class="flex items-center gap-3 px-1 mb-2">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xs flex-shrink-0"><?php echo e(strtoupper(substr($user->name,0,1))); ?></div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?php echo e($user->name); ?></p>
                    <p class="text-indigo-300 text-xs truncate"><?php echo e($user->email); ?></p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-indigo-300 hover:text-red-300 hover:bg-white/10 rounded-xl text-xs transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        
        <header class="bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 h-16 flex-shrink-0">
            <div class="flex items-center gap-3">
                <button @click="mobileMenu=true" class="md:hidden p-2 rounded-xl hover:bg-gray-100 text-gray-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-base font-semibold text-gray-900"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                    <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?><div class="flex items-center gap-1 text-xs text-gray-400"><?php echo $__env->yieldContent('breadcrumb'); ?></div><?php endif; ?>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <?php $sub = \App\Models\Subscription::where('user_id',auth()->id())->where('is_active',true)->latest()->first(); ?>
                <?php if($sub): ?>
                    <span class="hidden sm:inline-flex px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full"><?php echo e($sub->plan_id ? $sub->plan->name ?? 'Active' : ucfirst($sub->plan)); ?></span>
                <?php else: ?>
                    <a href="<?php echo e(route('user.subscription.plans')); ?>" class="hidden sm:inline-flex px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full hover:bg-amber-100">Upgrade Plan</a>
                <?php endif; ?>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm"><?php echo e(strtoupper(substr($user->name,0,1))); ?></div>
            </div>
        </header>

        
        <?php if(session('success') || session('error') || $errors->any()): ?>
        <div class="px-6 pt-4 space-y-2">
            <?php if(session('success')): ?>
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php echo e(session('success')); ?>

                <button @click="show=false" class="ml-auto text-emerald-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php echo e(session('error')); ?>

                <button @click="show=false" class="ml-auto text-red-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul class="list-disc list-inside space-y-0.5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                <button @click="show=false" class="ml-auto text-red-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\lab\lab-management\resources\views/layouts/user.blade.php ENDPATH**/ ?>