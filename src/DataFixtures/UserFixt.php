<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixt extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i=1; $i<=50; $i++){
            $user = new User();
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $user->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail("$firstName.$lastName$i@ntm.com")
                ->setUsername("$firstName$lastName$i")
                ->setPassword($this->encoder->encodePassword($user, $faker->colorName))
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
                ->setEmail("$firstName.$lastName$i@ntm.com")
                ->setUsername("$firstName$lastName$i")
                ->setPassword($this->encoder->encodePassword($user, $faker->colorName))
                ->setRoles(array(["ROLE_USER", "ROLE_ADMIN"]))
                ->setScore(0);

            $manager->persist($user);
        }

        $user = new User();
        $user->setUsername('tocard')
            ->setEmail('tocard@ginfo.xyz')
            ->setFirstName('Alex')
            ->setLastName('Pilouf')
            ->setRoles(array(["ROLE_USER", "ROLE_ADMIN"]))
            ->setPassword($this->encoder->encodePassword($user,'faible'))
            ->setScore(1000);

        $manager->persist($user);

        $manager->flush();
    }
}
