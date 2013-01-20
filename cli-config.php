<?php
require_once __DIR__.'/vendor/autoload.php';
$app = new Silex\Application();
require __DIR__.'/resources/config/dev.php';
$app = require __DIR__.'/src/app.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['db.orm.em'])
));