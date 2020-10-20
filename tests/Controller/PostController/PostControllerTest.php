<?php
namespace App\Tests\Controller\PostController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    public function setUp(): KernelBrowser
    {
        return $this->client = static::createClient();
    }

    public function testShowPostPageWithGoodSlug(): void
    {
       $this->client->request('GET', '/post/ipsam-fugiat-eaque-repudiandae-sit-voluptatibus-possimus-61');
       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testShowPostWithBadSlug(): void
    {
        $this->client->request('GET', '/post/ipsam-fugiat-eaque-61');
        $this->assertResponseStatusCodeSame(Response::HTTP_MOVED_PERMANENTLY);
    }

    public function testShowPostWithBadId(): void
    {
        $this->client->request('GET', '/post/ipsam-fugiat-eaque-63');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSearchWithEmptyKeyWord() :void
    {
        $this->client->request('GET', '/search', ['q' => '']);
        $this->assertSelectorTextContains('h2', 'Vous tentez de faire une recherche sans mot clé !');
        $this->assertSelectorTextNotContains('p', 'resultats pour le mot:');   
    }

    /**
     * This test must be done with ElastcSearch Server start!
     * @return void
     */
    public function testSearchWithAnResult(): void
    {
        $search = "Ipsam";
        $this->client->request('GET', '/search', ['q' => $search]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('p', 'resultats pour le mot: '. $search);
    }

    /**
     * This test must be done with ElastcSearch Server Start!
     * @return void
     */
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