<?php

require 'Slim/Slim.php';
require 'nagios/config.php';

$app = new Slim(array(
  'log.enable' => true,
  'log.path' => './logs',
  'log.level' => 4,
  'nagios.checks.base_path' => '/tmp/nagios-checks'
));

function create_host_check($host_name) {
  $app = Slim::getInstance();

  $base_path = $app->config('nagios.checks.base_path');

  $config = new Config($base_path, 'host');
  $config->host_name = $host_name;
  $config->write_out();
}

$app->put('/hosts/:host_name', 'create_host_check');

$app->run();

?>
