test
<?php

use App\DataFixtures\PostFixtures;
use App\Repository\PostRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCountAllPosts()
    {
        self::bootKernel();
        $this->loadFixtures([PostFixtures::class]);
        $posts = self::$container->get(PostRepository::class)->count([]);
        $this->assertEquals(31, $posts);
    }

    public function testFindLatest()
    {
        self::bootKernel();
        $this->loadFixtures([PostFixtures::class]);
        $posts = self::$container->get(PostRepository::class)->findLatest(10);
        $this->assertCount(10, $posts);
    }
}