<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr-FR");
        for($p = 0; $p <= 30; $p++){
            $post = new Post;
            $post->setTitle($faker->sentence(6))
                 ->setAuthor($faker->name('male'|'female'))
                 ->setContent($faker->paragraphs(rand(3,15), true))
                 ->setCreatedAt($faker->dateTimeBetween('-5 years', 'now'));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
