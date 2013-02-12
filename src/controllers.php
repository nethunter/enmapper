<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/map', function() use ($app) {
    $em = $app['db.orm.em'];

    $locations = $em->getRepository('En\Entity\Location')->findAllAvailableLocations();

    // Get all domains
    $gameDomains = $em->getRepository('En\Entity\GameDomain')->findAll();
    $gameDomainNames = array("All");
    foreach($gameDomains as $gameDomain) {
        $gameDomainNames[] = $gameDomain->getName();
    }

    // Get all games
    // Get all domains
    $games = $em->getRepository('En\Entity\Game')->findAll();
    $gameNames = array("All");
    foreach($games as $game) {
        $gameNames[] = $game;
    }

    $formData = array(
        'game_doamain' => 'Game domain',
        'game' => 'Game'
    );

    $form = $app['form.factory']->createBuilder('form', $formData)
        ->add('game_domain', 'choice', array(
            'choices' => $gameDomainNames,
            'expanded' => false
        ))
        ->add('game', 'choice', array(
            'choices' => $gameNames,
            'expanded' => false
        ))
    ->getForm();

    $mapGenerator = new \En\Games\MapGenerator();
    $mapGenerator->generateMapFromLocations($locations);
    $map = $mapGenerator->getMap();

    return $app['twig']->render('map.html.twig', array(
        'map' => array(
            'headerjs' => $map->getHeaderJS(),
            'js' => $map->getMapJS(),
            'body' => $map->getMap()
        ),
        'form' => $form->createView()
    ));
})->bind('map');

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

$app->get('/admin', function() use ($app) {

})->bind('admin');

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
