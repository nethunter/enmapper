<?php

namespace En;

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $app = new \Silex\Application();
        require __DIR__.'/../../resources/config/test.php';
        require __DIR__.'/../../src/app.php';

        // Use FilesystemSessionStorage to store session
        $app['session.storage'] = $app->share(
            function () {
                return new MockFileSessionStorage(sys_get_temp_dir());
            }
        );

        // Controllers
        require __DIR__ . '/../../src/controllers.php';

        return $this->app = $app;
    }

    public function test404()
    {
        $client = $this->createClient();

        $client->request('GET', '/give-me-a-404');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGameDomainCount()
    {
        $em = $this->app['db.orm.em'];
        $gameDomains = $em->getRepository('En\Entity\GameDomain')->findAll();

        foreach($gameDomains as $gameDomain) {
            /**
             * @var $gameDomain \En\Entity\GameDomain
             */
            echo $gameDomain->getName() . ': ' . count($gameDomain->getGames());
        }
    }
}
