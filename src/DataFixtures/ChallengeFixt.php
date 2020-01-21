<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Challenge;
use App\Entity\User;
use Faker;

class ChallengeFixt extends Fixture
{

    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $repoU = $manager->getRepository(User::class);
        $adminlist = $repoU->findByRole(["ROLE_USER", "ROLE_ADMIN"]);

        for($i=1; $i<=100; $i++){
            $challenge = new Challenge();
            $challenge->setName("Challenge n°$i")
                ->setDescription("Une description pasque j'ai la flemme de faker")
                ->setPassword($faker->password)
                ->setCreatedOn($faker->dateTime)
                ->setDifficulty(rand(1, 5))
                ->setReward(rand(1, 50))
                ->setCreatedBy($adminlist[$i%5]);

            $manager->persist($challenge);
        }

        $manager->flush();
    }
}
