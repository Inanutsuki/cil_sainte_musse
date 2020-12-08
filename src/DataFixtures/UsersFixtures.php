<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
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

        for ($nbUsers = 1; $nbUsers <= 30; $nbUsers++) {
            $user = new User;

            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, "admin123"));
            $user->setAdresse($faker->streetAddress);
            $user->setZipCode($faker->numberBetween($min = 11111, $max = 99999));
            $user->setCity($faker->city);
            $user->setBirthday($faker->dateTime($max = 'now', $timezone = null));
            if ($nbUsers === 1) {
                $user->setRoles("ROLE_ADMIN");
            } else {
                $user->setRoles("ROLE_USER");
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
