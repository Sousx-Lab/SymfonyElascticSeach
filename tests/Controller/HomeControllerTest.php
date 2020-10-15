<?php
namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): KernelBrowser
    {
        return $this->client = static::createClient();
    }
    public function testHomePage(): void
    {
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1HomePage(): void
    {
        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Les derniers articles');
    }

    public function testLinkPostHomePage(): void
    {
        $crawler = $this->client->request('GET', '/');
        $link = $crawler
                ->filter('a:contains("Ipsam")')
                ->eq(0)
                ->link();
        $crawler = $this->client->click($link);
        $this->assertSelectorTextContains('h1', 'Ipsam fugiat eaque repudiandae sit voluptatibus possimus.');
    }
    
    public function testDisplayedDateinFrenchFormat(): void
    {
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr_FR']);
        $this->assertSelectorTextContains('small', '4 oct. 2015');
    }

    public function testDisplayedDateinEnglishFormat(): void
    {
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en_US']);
        $this->assertSelectorTextContains('small', 'Oct 4, 2015');
    }

    public function testSearchBarFormAction(): void
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('Search')->form([
            'q' => ''
        ]);
        $this->client->submit($form);
        $this->assertEquals('http://localhost/search?q=', $form->getUri());
    }
}