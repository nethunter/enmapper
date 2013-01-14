<?php

namespace SKE\Composer;

use Composer\Script\Event;

class Script
{

    public static function createOrChmodPath($path)
    {
	if (file_exists($path)) {
		chmod($path, 0777);
	} else {
		mkdir($path, 0777);
	}
    }

    public static function postInstall(Event $event)
    {
	self::createOrChmodPath('resources/cache');
	self::createOrChmodPath('resources/log');
	self::createOrChmodPath('web/assets');

        chmod('console', 0500);
        exec('php console assetic:dump');
    }

}
