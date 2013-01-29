<?php
namespace En\Games;

class LocationFilter
{
    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getCoordinates()
    {
        $text = $this->text;
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
}
