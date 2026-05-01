<?php $__env->startSection('title', 'Sign In'); ?>

<?php $__env->startSection('form'); ?>
<div>
    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome back</h2>
    <p class="text-gray-500 mb-8">Sign in to your LabMS account</p>

    <?php if($errors->any()): ?>
    <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?php echo e($errors->first()); ?>

    </div>
    <?php endif; ?>

    <form method="POST" action="/login" class="space-y-5">
        <?php echo csrf_field(); ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors"
                placeholder="you@labname.com">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors"
                placeholder="••••••••">
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500">
                Remember me
            </label>
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
            Sign In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <a href="<?php echo e(route('register')); ?>" class="font-semibold text-indigo-600 hover:text-indigo-700">Start free trial</a>
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\lab\lab-management\resources\views/auth/login.blade.php ENDPATH**/ ?>