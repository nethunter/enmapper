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
        $coordinates = array();
        $matched = preg_match_all('/[0-9]{1,2}[:|°|º][0-9]{1,2}[:|\'](?:\b[0-9]+(?:\.[0-9]*)?|\.[0-9]+\b)"?[N|S|E|W]/',
            $text, $matches);

        print_r($matches);

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
        $matched = preg_match_all('/(\-?\d+(\.\d+))[,\s]+\s*?(\-?\d+(\.\d+))/', $text, $matches);
        $coordinates = array();

        if ($matched) {
            while ($matches[1] && $matches[3]) {
                $coordinates[] = array(
                    array_shift($matches[1]),
                    array_shift($matches[3])
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
