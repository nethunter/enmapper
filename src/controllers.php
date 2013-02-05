<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/map', function() use ($app) {
    $locations = $app['db.orm.em']->getRepository('En\Entity\Location')->findAllAvailableLocations();

    $mapGenerator = new \En\Games\MapGenerator();
    $mapGenerator->generateMapFromLocations($locations);
    $map = $mapGenerator->getMap();

    return $app['twig']->render('map.html.twig', array(
        'map' => array(
            'headerjs' => $map->getHeaderJS(),
            'js' => $map->getMapJS(),
            'body' => $map->getMap()
        ),
        'form' => ''
    ));
})->bind('map');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;
