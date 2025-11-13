<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'https://github.com/Arvind4242/LAB-management.git');
set('ssh_multiplexing', false);
set('keep_releases', 2);
set('default_timeout', 900);
set('writable_dirs', ['storage', 'bootstrap/cache']);
set('writable_mode', 'chmod');
set('writable_use_sudo', false);

set('release_name', function () {
    return date('YmdHis');
});

add('shared_files', ['.env']);
add('shared_dirs', ['storage']);

// Hosts
host('myserver')
    ->set('hostname', '35.175.173.40')
    ->set('remote_user', 'ubuntu')
    ->set('identity_file', 'C:\Users\Arvind singh sikarwa\.ssh\mykey.pem')
    ->set('deploy_path', '/home/ubuntu/lab-management')
    ->set('keep_releases', 3)
    ->set('http_user', 'root')
    ->set('default_timeout', null)
    ->set('branch', 'main')
    ->set('stage', 'production')
    ->set('is_production', 1)
    ->set('labels', ['stage' => 'production'])
    ->set('ssh_arguments', ['-o UserKnownHostsFile=/dev/null', '-o StrictHostKeyChecking=no']);

// -----------------------------
// ✅ Skip migration in production
// -----------------------------
desc('Skip database migration on production');
task('artisan:migrate', function () {
    writeln('⏭️  Skipping artisan migrate for production.');
});

// -----------------------------
// ✅ Fix file permissions
// -----------------------------
desc('Fix file permissions');
task('fix:permissions', function () {
    run('cd {{deploy_path}} && sudo chown -R ubuntu:www-data .');
    run('cd {{deploy_path}} && sudo chmod -R 775 .');
});

after('deploy:symlink', 'fix:permissions');
after('deploy:failed', 'deploy:unlock');
