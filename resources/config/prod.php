<?php

// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];
$app['translator.messages'] = array(
    'fr' => __DIR__.'/../resources/locales/fr.yml',
);

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Twig cache
$app['twig.options.cache'] = $app['cache.path'] . '/twig';

// Assetic
$app['assetic.enabled'] = true;
$app['assetic.path_to_cache']       = $app['cache.path'] . '/assetic' ;
$app['assetic.path_to_web']         = __DIR__ . '/../../web/assets';
$app['assetic.input.path_to_assets']    = __DIR__ . '/../assets/';
$app['assetic.input.path_to_css']       = $app['assetic.input.path_to_assets'] . 'css/*.css';
$app['assetic.output.path_to_css']      = 'css/styles.css';
$app['assetic.input.path_to_js']        = array(
    $app['assetic.input.path_to_assets'] . 'js/bootstrap.min.js',
    $app['assetic.input.path_to_assets'] . 'js/script.js',
);
$app['assetic.output.path_to_js']       = 'js/scripts.js';
$app['assetic.filter.yui_compressor.path'] = '/usr/share/yui-compressor/yui-compressor.jar';

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'enmapper',
    'user'     => 'root',
    'password' => '160557',
);

$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.proxies_dir'           => __DIR__ . '/cache/doctrine/proxy',
    'db.orm.proxies_namespace'     => 'DoctrineProxy',
    'db.orm.cache'                 => 
        !$app['debug'] && extension_loaded('apc') ? new ApcCache() : new ArrayCache(),
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type'      => 'annotation',       // entity definition 
        'path'      => __DIR__ . '/src',   // path to your entity classes
        'namespace' => 'MyWebsite\Entity', // your classes namespace
    )),
));


$app['en_domain'] = 'rusisrael.en.cx';