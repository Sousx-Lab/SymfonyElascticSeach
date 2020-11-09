<?php

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Services\Cache\PostsCache;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostsCacheTest extends KernelTestCase{
    
    protected PostsCache $postsCache;

    public function setUp()
    {
      self::bootKernel();
      $repository = self::$container->get(PostRepository::class);
      $cacheInterface = self::$container->get(CacheInterface::class);
      $this->postsCache = new PostsCache($repository, $cacheInterface);
    }

    public function testGetCachedPosts(): void
    {
        $posts = $this->postsCache->getPosts();
        $this->assertIsArray($posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);
    }

    public function testDeleteCachedPosts(): void
    {
        $this->assertTrue($this->postsCache->deletePosts());
    }
}