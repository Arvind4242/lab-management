<?php $__env->startSection('title', 'Create Account'); ?>

<?php $__env->startSection('form'); ?>
<div>
    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Start free trial</h2>
    <p class="text-gray-500 mb-8">30-day free trial — no credit card required</p>

    <?php if($errors->any()): ?>
    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <ul class="list-disc list-inside space-y-0.5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        <div class="pb-3 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Your Details</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Dr. Ravi Kumar">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                    <input type="text" name="mobile" value="<?php echo e(old('mobile')); ?>" required class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="9876543210">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="you@lab.com">
                </div>
            </div>
        </div>

        <div class="pb-3 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Lab Details</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lab Name</label>
                    <input type="text" name="lab_name" value="<?php echo e(old('lab_name')); ?>" required class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="City Diagnostic Lab">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lab Address</label>
                        <input type="text" name="lab_address" value="<?php echo e(old('lab_address')); ?>" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="123 Main St">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lab Phone</label>
                        <input type="text" name="lab_phone" value="<?php echo e(old('lab_phone')); ?>" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="0141-2345678">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Password</p>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Min 8 chars">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Repeat password">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors text-sm mt-2">
            Create Account & Start Trial
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-gray-600">
        Already have an account? <a href="<?php echo e(route('login')); ?>" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign in</a>
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\lab\lab-management\resources\views/auth/register.blade.php ENDPATH**/ ?>