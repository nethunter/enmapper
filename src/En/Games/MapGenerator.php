<?php
namespace En\Games;

use En\Entity\Location;

use PHPGoogleMaps;
use PHPGoogleMaps\Overlay\Marker;
use PHPGoogleMaps\Core\LatLng;

class MapGenerator
{
    protected $map = null;
    protected $height = '800px';
    protected $width = '940px';
    protected $singleZoom = '15';

    private function addLocationToMap(PHPGoogleMaps\Map $map, Location $location)
    {
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

    public function generateMapFromLocations($locations)
    {
        $this->addLocationsToMap($locations);
        $map = $this->generate();

        // Set cluster options
        $cluster_options = array(
            'maxZoom' => 10,
            'gridSize' => null
        );

        $map->enableClustering( '/js/libs/markerclusterer_compiled.js', $cluster_options );
    }

    public function addLocationsToMap($locations)
    {
        $map = $this->getMap();

        foreach($locations as $location) {
            $this->addLocationToMap($map, $location);
        }
    }

    public function addSingleLocation(Location $location)
    {
        $map = $this->getMap();

        if ($this->getSingleZoom()) {
            $map->disableAutoEncompass();
            $map->setZoom($this->getSingleZoom());
            $map->setCenter(new LatLng($location->getLat(), $location->getLng()));
        }

        $this->addLocationToMap($map, $location);
    }

    public function generate()
    {
        $map = $this->getMap();

        $map->setWidth($this->getWidth());
        $map->setHeight($this->getHeight());

        $map->enableStreetView();

        return $map;
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

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($weight)
    {
        $this->width = $weight;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setSingleZoom($zoom)
    {
        $this->singleZoom = $zoom;
    }

    public function getSingleZoom()
    {
        return $this->singleZoom;
    }
}
