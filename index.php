<?php

require 'Slim/Slim.php';
require 'nagios/config.php';

$app = new Slim(array(
  'log.enable' => true,
  'log.path' => './logs',
  'log.level' => 4,
  'nagios.checks.base_path' => '/tmp/nagios-checks'
));

function set_put_attributes($app, $config) {
  $params = $app->request()->put();
  foreach ($params as $key => $value) {
    $config->$key = $value;
  }
  return $config;
}

function create_host_check($host_name) {
  $app = Slim::getInstance();

  $base_path = $app->config('nagios.checks.base_path');

  $config = new Config($base_path, 'host');
  set_put_attributes($app, $config);
  $config->host_name = $host_name;
  $config->write_out();
}

function create_service_check($host_name,$service_description) {
  $app = Slim::getInstance();

  $base_path = $app->config('nagios.checks.base_path');
  $config = new Config($base_path, 'service');
  set_put_attributes($app, $config);
  $config->host_name = $host_name;
  $config->write_out();
}

$app->put('/hosts/:host_name', 'create_host_check');

$app->put('/hosts/:host_name/services', 'create_service_check');

$app->run();

?>
