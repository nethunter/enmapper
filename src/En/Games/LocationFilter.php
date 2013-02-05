<?php
namespace En\Games;

/**
 * @todo Implement additional coordinate systems, using https://github.com/treffynnon/Navigator
 */
class LocationFilter
{
    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @todo Implement the DMS Coordinates Validation and conversion
     *
     * @param $text
     * @return array
     */
    public function matchDMSCoordinates($text)
    {
        // $pattern = '/[0-9]{1,2}[:|°|º][0-9]{1,2}[:|\'](?:\b[0-9]+(?:\.[0-9]*)?|\.[0-9]+\b)"?[N|S|E|W]/';
        return array();


        $coordinates = array();
        $matched = preg_match_all($pattern, $text, $matches);


        if ($matched) {
            print_r($matches);
        }

        return $coordinates;
    }

    /**
     * @todo Implement the MinDec Coordinates Validation and conversion
     *
     * @param $text
     * @return array
     */
    public function matchMinDecCoordinates($text)
    {
        $coordinates = array();

        return $coordinates;
    }

    public function matchDecDegCoordinates($text)
    {
        $pattern = "/([-]?[0-9]{1,2}\.[0-9]{3,7})[,\s]+([-]?[0-9]{1,3}\.[0-9]{3,7})/";

        $matched = preg_match_all($pattern, $text, $matches);
        $coordinates = array();

        if ($matched) {
            while ($matches[1] && $matches[2]) {
                $coordinates[] = array(
                    array_shift($matches[1]),
                    array_shift($matches[2])
                );
            }
        }

        return $coordinates;
    }

    public function getCoordinates()
    {
        $text = $this->text;
        $coordinates = array();
        $coordinates += $this->matchDecDegCoordinates($text);
        $coordinates += $this->matchDMSCoordinates($text);
        $coordinates += $this->matchMinDecCoordinates($text);

        return $coordinates;
    }
}
