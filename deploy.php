<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/Arvind4242/LAB-management.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('35.175.173.40')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/lam-management');

// Hooks

after('deploy:failed', 'deploy:unlock');
