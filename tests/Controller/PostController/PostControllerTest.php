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
}