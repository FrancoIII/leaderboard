<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixt extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i=1; $i<=50; $i++){
            $user = new User();
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $user->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail("$firstName.$lastName@ntm.com")
                ->setUsername("$firstName[0]$lastName")
                ->setPassword(sha1($faker->colorName))
                ->setRoles(array(["ROLE_USER"]))
                ->setScore(0);

            $manager->persist($user);
        }

        for($i=1; $i<=5; $i++){
            $user = new User();
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $user->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail("$firstName.$lastName@ntm.com")
                ->setUsername("$firstName[0]$lastName")
                ->setPassword($faker->password)
                ->setRoles(array(["ROLE_USER", "ROLE_ADMIN"]))
                ->setScore(0);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
