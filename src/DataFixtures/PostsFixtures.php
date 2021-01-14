<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Faker;



class PostsFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return array(
            UsersFixtures::class,
        );
    }
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $categories = [];

        $cat = new Category;
        $cat->setTitle("Développement économique et social");
        $categories[] = $cat;
        $manager->persist($cat);
        $manager->flush();

        $cat = new Category;
        $cat->setTitle("Culturel et sportif");
        $categories[] = $cat;
        $manager->persist($cat);
        $manager->flush();
        
        $cat = new Category;
        $cat->setTitle("Transport");
        $categories[] = $cat;
        $manager->persist($cat);
        $manager->flush();
        
        $cat = new Category;
        $cat->setTitle("Acceuil");
        $categories[] = $cat;
        $manager->persist($cat);
        $manager->flush();
        

        for ($nbArticle = 1; $nbArticle <= 50; $nbArticle++) {
            $post = new Post;
            $paragraphs = implode(' ', $faker->paragraphs($nb = $faker->numberBetween($min = 2, $max = 5), $asText = false));
            $user = $this->getReference('userId_' . $faker->numberBetween($min = 2, $max = 30));
            $post->setIsValided($faker->boolean($chanceOfGettingTrue = 50));
            $post->setTitle($faker->sentence($nbWords = $faker->numberBetween($min = 3, $max = 6), $variableNbWords = true));
            $post->setContent($paragraphs);
            $post->setCategory($categories[$faker->numberBetween($min = 0, $max = 2)]);
            $post->setAuthor($user);
            $post->setOnlyMembers(false);
            $post->setOnlyAssembly(false);
            $post->setCreatedAt($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($post);
        }
        $manager->flush();
    }
}
