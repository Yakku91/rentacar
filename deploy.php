<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@gitlab.bysaeth.de:fi09-projects/mobilitaetsmacher.git');
set('branch', 'main');

add('shared_files', ['.env.local', 'public/robots.txt']);
add('shared_dirs', ['public/assets']);
add('writable_dirs', []);

// Hosts
host('production', )
    ->set('remote_user', 'root')
    ->set('deploy_path', '/var/www/mobilitaetsmacher.bysaeth.de')
    ->set('hostname', '128.140.35.50');

// Hooks
after('deploy', 'database:migrate');


after('deploy:failed', 'deploy:unlock');
