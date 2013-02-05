<?php
namespace En\Games;

use PHPGoogleMaps;
use PHPGoogleMaps\Overlay\Marker;
use PHPGoogleMaps\Core\LatLng;

class MapGenerator
{
    protected $map = null;

    public function generateMapFromLocations($locations)
    {
        $map = $this->getMap();

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

            $marker = Marker::createFromPosition(new LatLng($lattitude, $longtitude), $marker_options);
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
    }

    public function setMap($map)
    {
        $this->map = $map;
    }

    public function getMap()
    {
        if (null == $this->map) {
            $this->map = new \PHPGoogleMaps\Map();
        }
        return $this->map;
    }
}
