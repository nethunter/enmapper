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

//    $positions = array(
//        array(
//            'position' => array(31.921452, 34.882401),
//            'game' => '#85 - Эта музыка будет вечной',
//            'level' => 'От автора игры'
//        ),
//        array(
//            'position' => array(31.860161, 34.923788),
//            'game' => '#85 - Эта музыка будет вечной',
//            'level' => 'Level #5 "Тель Гезер"'
//        ),
//        array(
//            'position' => array(31.964167, 34.783056 ),
//            'game' => '#85 - Эта музыка будет вечной',
//            'level' => 'Level #9 "Ришон - пианино"'
//        ),
//        array(
//            'position' => array(31.964167, 34.783056 ),
//            'game' => '#85 - Эта музыка будет вечной',
//            'level' => 'Level #9 "Ришон - пианино"'
//        ),
//        array(
//            'position' => array(32.101478, 34.827758),
//            'game' => '#85 - Эта музыка будет вечной',
//            'level' => 'Level #12 "Агентский уровень"'
//        ),
//        array(
//            'position' => array(32.083373, 34.9575488),
//            'game' => '#85 - Эта музыка будет вечной',
//            'level' => 'Level #18 "Могила Шейха"'
//        ),
//        
//        array(
//            'position' => array(31.820252, 34.662541),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'От автора игры'
//        ),
//        array(
//            'position' => array(31.807196, 34.638237),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'Level #3 "В магазин"'
//        ),
//        array(
//            'position' => array(31.78035, 34.621779),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'Level #6 "К доктору"'
//        ),
//        array(
//            'position' => array(31.813432, 34.641234),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'Level #8 "Посыльные"'
//        ),
//        array(
//            'position' => array(31.891214, 34.772018),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'Level #10 "к директору"'
//        ),
//        array(
//            'position' => array(31.886244, 34.792950),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'Level #12 "Опять в дорогу"'
//        ),
//        array(
//            'position' => array(31.995466, 34.910976),
//            'game' => '#86 - "Пионерский лагерь"',
//            'level' => 'Level #22 "Побег"'
//        ),
//    );
    
    $domains = $em->getRepository("En\Entity\GameDomain")->findBy(
        array(
            'name' => $app['en_domain']
        )
    );
    

    foreach($domains as $domain) {
        $games = $domain->getGames();
        foreach($games as $game) {
            $gameLevels = $game->getLevels();

            foreach($gameLevels as $gameLevel) {
                $locations = $gameLevel->getLocations();

                $marker_options = array(
                    'title' => $game->getNum() . ' - ' . $gameLevel->getName(),
                    'content' => '<p><strong>' . $game->getName() . '</strong><br />' 
                        . $gameLevel->getNum() . '</p>'
                );

                foreach($locations as $location) {

                    $lattitude = $location->getLat();
                    $longtitude = $location->getLng();

                    $marker = \PHPGoogleMaps\Overlay\Marker::createFromPosition( 
                        new \PHPGoogleMaps\Core\LatLng($lattitude, $longtitude),
                            $marker_options 
                    );
                    $map->addObject( $marker );

                }
            }
        }
    }
    
    $map->setWidth('940px');
    $map->setHeight('800px');
    
    $map->enableStreetView();

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
