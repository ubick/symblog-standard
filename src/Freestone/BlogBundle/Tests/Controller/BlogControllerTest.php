<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Freestone\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase {

    public function testAddBlogComment() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/21/a-day-with-symfony2');

        $this->assertEquals(1, $crawler->filter('h2:contains("A day with Symfony2")')->count());

        // Select based on button value, or id or name for buttons
        $form = $crawler->selectButton('Submit')->form();

        $crawler = $client->submit($form, array(
            'freestone_blogbundle_commenttype[user]' => 'Jimmy',
            'freestone_blogbundle_commenttype[comment]' => 'Great post!',
            ));

        // Follow redirects
        $crawler = $client->followRedirect();

        $articleCrawler = $crawler->filter('section .previous-comments article')->last();

        $this->assertEquals('Jimmy', $articleCrawler->filter('header span.highlight')->text());
        $this->assertEquals('Great post!', $articleCrawler->filter('p')->last()->text());

        // Check the sidebar to ensure latest comments are display and there is 10 of them

        $this->assertEquals(10, $crawler->filter('aside.sidebar section')->last()
                ->filter('article')->count()
        );

        $this->assertEquals('Jimmy', $crawler->filter('aside.sidebar section')->last()
                ->filter('article')->first()
                ->filter('header span.highlight')->text()
        );
    }

}