<?php
// DIC configuration

use Spipu\Html2Pdf\Html2Pdf;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// userlist
$container['users'] = function ($c) {
  return $c->get('settings')['users'];
};

// doc
$container['doc'] = function ($c) {
  return new Html2Pdf();
};
