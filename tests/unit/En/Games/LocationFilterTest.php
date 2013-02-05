<?php
namespace En\Games;

use En\Games\LocationFilter;

class LocationFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetLocationFromCleanCoordinates()
    {
        $text = 'Sample text with coordinates: 31.921452, 34.882401, see you there!';

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(
            array(31.921452, 34.882401)
        ), $coordinates);
    }

    public function testGetLocationFromCoordinatesWithNewlines()
    {
        $text = "Sample text with coordinates: 31.921452\n34.882401\nI'm all for new lines.";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(
            array(31.921452, 34.882401)
        ), $coordinates);
    }

    public function testGetLocationFromCoordinatesWithSpaces()
    {
        $text = "Sample text with coordinates: 31.921452 34.882401, nuff said.";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(
            array(31.921452, 34.882401)
        ), $coordinates);
    }

    public function testGetLocationsFromMultipleCoordinates()
    {
        $text = "Sample text with coordinates: 31.921452 34.882401, 31.860161, 34.923788\n31.964167\n34.783056"
            . " nuff said.";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(
            array(31.921452, 34.882401),
            array(31.860161, 34.923788),
            array(31.964167, 34.783056)
        ), $coordinates);
    }

    public function testGetLocationsFromLongCoordinates()
    {
        $text = "To Bristol, my friends at 32.5154779, 34.9488387";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(
            array(32.5154779, 34.9488387)
        ), $coordinates);
    }

    public function testMatchDMSNewLineCoordinates()
    {
        $this->markTestIncomplete('Not implemented yet');
        $text = "Sample text with coordinates: 32° 05' 37\" N\n34° 50' 49\" E nuff said.";

        $filter = new LocationFilter($text);
        $coordinates = $filter->matchDMSCoordinates($text);

        $this->assertEquals(array(
            array(32.093611111111116, 34.846944444444446)
        ), $coordinates);
    }

    public function testVerifyDatesArentMatched()
    {
        $text = "Game begins on the 19.08.2011, at 10:00 in Jerusalem";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(), $coordinates);
    }

    public function testRandomNumbersArentMatched()
    {
        $text = "Please verify the following numbers: 2.41 12.7 4.25 0.07 0.07 7.51 it should be fun";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(), $coordinates);
    }

    public function testHandlePartialMatchesGracefully()
    {
        $text = "Don't fall on partial coordinates: 31.921452";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(), $coordinates);
    }

    public function testEmptyArrayForTextWithoutLocations()
    {
        $text = "Sample text with coordinates: nuff said.";

        $filter = new LocationFilter($text);
        $coordinates = $filter->getCoordinates();

        $this->assertEquals(array(), $coordinates);
    }
}
