<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use \Doctrine\Common\Cache\ApcCache;
use \Doctrine\Common\Cache\ArrayCache;

use Symfony\Component\Translation\Loader\YamlFileLoader;

use SilexAssetic\AsseticExtension;

$app->register(new SessionServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new TranslationServiceProvider());
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/../resources/locales/fr.yml', 'fr');

    return $translator;
}));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../resources/log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => 300 // = Logger::WARNING
));

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
    'twig.path'           => array(__DIR__ . '/../resources/views')
));

if (isset($app['assetic.enabled']) && $app['assetic.enabled']) {
    $app->register(new AsseticExtension(), array(
        'assetic.options' => array(
            'debug'            => $app['debug'],
            'auto_dump_assets' => $app['debug'],
        ),
        'assetic.filters' => $app->protect(function($fm) use ($app) {
            $fm->set('yui_css', new Assetic\Filter\Yui\CssCompressorFilter(
                $app['assetic.filter.yui_compressor.path']
            ));
            $fm->set('yui_js', new Assetic\Filter\Yui\JsCompressorFilter(
                $app['assetic.filter.yui_compressor.path']
            ));
        }),
        'assetic.assets' => $app->protect(function($am, $fm) use ($app) {
            $am->set('styles', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_css'],
                    // Yui compressor is disabled by default.
                    // If you need it, and you have installed it, uncomment the
                    // next line, and delete "array()"
                    //array($fm->get('yui_css'))
                    array()
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);

            $am->set('scripts', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_js'],
                    // Yui compressor is disabled by default.
                    // If you need it, and you have installed it, uncomment the
                    // next line, and delete "array()"
                    //array($fm->get('yui_js'))
                    array()
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
        })
    ));
}

$app->register(new Silex\Provider\DoctrineServiceProvider());

$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.proxies_dir' => __DIR__ . '/../resources/cache/doctrine/proxy',
    'db.orm.proxies_namespace' => 'DoctrineProxy',
    'db.orm.cache' => 
        !$app['debug'] && extension_loaded('apc') ? new ApcCache() : new ArrayCache(),
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type' => 'annotation',
        'path' => __DIR__ . '/En/Entity',
        'namespace' => 'En\Entity',
    )),
));

return $app;
