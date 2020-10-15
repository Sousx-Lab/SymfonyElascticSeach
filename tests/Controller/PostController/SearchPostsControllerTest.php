<?php

namespace App\Tests\Controller\PostController;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SearchPostsControllerTest extends WebTestCase
{   
    protected KernelBrowser $client;

    protected function setUp(): KernelBrowser
    {
       return $this->client = static::createClient();
    }
    
    public function testSearchWithEmptyKeyWord() :void
    {
        $this->client->request('GET', '/search', ['q' => '']);
        $this->assertSelectorTextContains('h2', 'Vous tentez de faire une recherche sans mot clé !');
        $this->assertSelectorTextNotContains('p', 'resultats pour le mot:');   
    }

    public function testSearchWithAndResult(): void
    {
        $search = "Ipsam";
        $this->client->request('GET', '/search', ['q' => $search]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('p', 'resultats pour le mot: '. $search);
    }

    public function testSearchWithoutResult(): void
    {
        $search = "lol";
        $this->client->request('GET', '/search', ['q' => $search]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h3', 'Aucun resultat trouvé pour le mot: ' . $search);
    }

    /**
     * This test must be done with ElastcSearch Server Shutdown !
     * @return void
     */
    public function testElasticSearchServerDown(): void
    {
        $search = "Ipsam";
        $this->client->request('GET', '/search', ['q' => $search]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}