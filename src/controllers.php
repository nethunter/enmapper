<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/map', function() use ($app) {
    $map = new \PHPGoogleMaps\Map;

    $em = $app['db.orm.em'];

    $dql = 'SELECT l, gl, g, gd FROM En\Entity\Location l JOIN l.level gl JOIN gl.game g JOIN g.domain gd';

    $query = $app['db.orm.em']->createQuery($dql);
    $locations = $query->getResult();

    /**
     * @var En\Entity\Location $location
     */
    foreach($locations as $location) {
        $gameLevel = $location->getLevel();
        $game = $gameLevel->getGame();
        $domain = $game->getDomain();

        $marker_options = array(
            'title' => '#' . $game->getNum() . ' - ' . $gameLevel,
            'content' => '<p><strong>' . $game . '</strong><br />'
                . '<a href=' . $gameLevel->getFullLink() . ' target=\'en_level_view\'>' . $gameLevel . '</a></p>'
        );

        $lattitude = $location->getLat();
        $longtitude = $location->getLng();

        $marker = \PHPGoogleMaps\Overlay\Marker::createFromPosition(
            new \PHPGoogleMaps\Core\LatLng($lattitude, $longtitude),
            $marker_options
        );
        $map->addObject( $marker );
    }
    
    $map->setWidth('940px');
    $map->setHeight('800px');
    
    $map->enableStreetView();
    // Set cluster options
    $cluster_options = array(
        'maxZoom' => 10,
        'gridSize' => null
    );
    $map->enableClustering( 'js/libs/markerclusterer_compiled.js', $cluster_options );

    return $app['twig']->render('map.html.twig', array(
        'map' => array(
            'headerjs' => $map->getHeaderJS(),
            'js' => $map->getMapJS(),
            'body' => $map->getMap()
        )
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
