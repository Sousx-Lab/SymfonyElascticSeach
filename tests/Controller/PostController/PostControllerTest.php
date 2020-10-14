<?php
namespace App\Tests\Controller\PostController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testShowPostPageWithGoodSlug()
    {
       $this->client->request('GET', '/post/ipsam-fugiat-eaque-repudiandae-sit-voluptatibus-possimus-61');
       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testShowPostWithBadSlug()
    {
        $this->client->request('GET', '/post/ipsam-fugiat-eaque-61');
        $this->assertResponseStatusCodeSame(Response::HTTP_MOVED_PERMANENTLY);
    }

    public function testShowPostWithBadId()
    {
        $this->client->request('GET', '/post/ipsam-fugiat-eaque-63');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}