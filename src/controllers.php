<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\SecurityServiceProvider;
use Silex\Application;

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
        'map' => $map,
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
    return $app['twig']->render('admin/index.html.twig');
})->bind('admin');

$app->get('/admin/domains', function() use ($app) {
    $em = $app['db.orm.em'];

    return $app['twig']->render('admin/domains.html.twig', array(
        'domains' => $em->getRepository('En\Entity\GameDomain')->findAll()
    ));
})->bind('admin_domains');

$app->get('/admin/games/{domain}', function(Application $app, $domain) {
    $em = $app['db.orm.em'];
    $gameRepository = $em->getRepository('En\Entity\Game');

    if ($domain) {
        $games = $gameRepository->findByDomain($domain);
    } else {
        $games = $gameRepository->findAll();
    }

    return $app['twig']->render('admin/games.html.twig', array(
        'games' => $games
    ));
})->value('domain', null)->bind('admin_games');

$app->get('/admin/game_levels/{game}', function(Application $app, $game) {
    $em = $app['db.orm.em'];
    $levelRepository = $em->getRepository('En\Entity\GameLevel');

    if ($game) {
        $levels = $levelRepository->findByGame($game);
    } else {
        $levels = $levelRepository->findAll();
    }
    return $app['twig']->render('admin/game_levels.html.twig', array(
        'levels' => $levels
    ));
})->value('game', null)->bind('admin_gamelevels');

$app->get('/admin/game_levels/content/{level}', function(Application $app, $level) {
    $em = $app['db.orm.em'];

    $level = $em->getRepository('En\Entity\GameLevel')->findOneById($level);
    /** @var \En\Entity\GameLevel $level */

    return $level->getContent();
})->bind('admin_gamelevels_content');

$app->get('/admin/locations/{level}', function(Application $app, $level) {
    $em = $app['db.orm.em'];
    $locationRepository = $em->getRepository('En\Entity\Location');

    if ($level) {
        $locations = $locationRepository->findByLevel($level);
    } else {
        $locations = $locationRepository->findAll();
    }

    $mapGenerator = new \En\Games\MapGenerator();
    $map = $mapGenerator->getMap();

    return $app['twig']->render('admin/locations.html.twig', array(
        'locations' => $locations,
        'map' => $map
    ));
})->value('level', null)->bind('admin_locations');

$app->get('/admin/locations/map/{location}', function(Application $app, $location) {
    $em = $app['db.orm.em'];
    $location = $em->getRepository('En\Entity\Location')->findOneById($location);

    $mapGenerator = new \En\Games\MapGenerator();
    $mapGenerator->addSingleLocation($location);
    $mapGenerator->setHeight('370px');
    $mapGenerator->setWidth('530px');
    $map = $mapGenerator->generate();

    return $app['twig']->render('admin/locations_map.html.twig', array(
        'map' => $map
    ));
})->bind('admin_location_map');

$app->post('/admin/locations/toggle-visible', function(Request $request) use ($app) {
    $em = $app['db.orm.em'];
    /** @var \Doctrine\ORM\EntityManager $em */

    $locationId = $request->get('location');
    $visible = ($request->get('visible') == 'true');

    $location = $em->getRepository('En\Entity\Location')->findOneById($locationId);
    /** @var \En\Entity\Location $location */

    $location->setVisible($visible);
    $em->persist($location);
    $em->flush();

    return $app->json(array(
        'success' => true,
        'location' => $location->getId(),
        'visible' => $location->getVisible()
    ));
})->bind('admin_locations_visible_toggle');

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

$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin',
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'logout' => array('logout_path' => '/admin/logout'),
            'users' => array(
                'admin' => array(
                    'ROLE_ADMIN',
                    '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='
                )
            )
        )
    )
));

$app['admin_navigation'] = $app->share(function() use ($app) {
    $em = $app['db.orm.em'];

    return array(
        'domain_count' => $em->getRepository('En\Entity\GameDomain')->getCount(),
        'game_count' => $em->getRepository('En\Entity\Game')->getCount(),
        'game_level_count' => $em->getRepository('En\Entity\GameLevel')->getCount(),
        'location_count' => $em->getRepository('En\Entity\Location')->getCount()
    );
});

return $app;
