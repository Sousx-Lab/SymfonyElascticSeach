<?php
namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }
    public function testHomePage()
    {
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1HomePage()
    {
        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Les derniers articles');
    }

    public function testLinkPostHomePage()
    {
        $crawler = $this->client->request('GET', '/');
        $link = $crawler
                ->filter('a:contains("Ipsam")')
                ->eq(0)
                ->link();
        $crawler = $this->client->click($link);
        $this->assertSelectorTextContains('h1', 'Ipsam fugiat eaque repudiandae sit voluptatibus possimus.');
    }
    
    public function testDisplayedDateinFrenchFormat()
    {
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr_FR']);
        $this->assertSelectorTextContains('small', '4 oct. 2015');
    }

    public function testDisplayedDateinEnglishFormat()
    {
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en_US']);
        $this->assertSelectorTextContains('small', 'Oct 4, 2015');
    }
}