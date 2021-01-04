<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User;

        $user->setLastName("admin");
        $user->setFirstName("admin");
        $user->setEmail("admin@admin.com");
        $user->setPassword($this->encoder->encodePassword($user, "admin123"));
        $user->setAdresse("admin");
        $user->setZipCode("admin");
        $user->setCity("admin");
        $user->setBirthday($faker->dateTime($max = 'now', $timezone = null));
        $user->setRoles(["ROLE_ADMIN"]);

        $manager->persist($user);

        $manager->flush();

        for ($nbUsers = 2; $nbUsers <= 30; $nbUsers++) {

            $user = new User;

            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, "admin123"));
            $user->setAdresse($faker->streetAddress);
            $user->setZipCode($faker->numberBetween($min = 11111, $max = 99999));
            $user->setCity($faker->city);
            $user->setBirthday($faker->dateTime($max = 'now', $timezone = null));
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);

            $this->addReference('userId_'.$nbUsers, $user);
        }
        $manager->flush();
    }
}