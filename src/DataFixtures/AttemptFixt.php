<?php

namespace App\DataFixtures;

use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Attempt;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class AttemptFixt extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $repoU = $manager->getRepository(User::class);
        $userlist = $repoU->findAll();

        $repoC = $manager->getRepository(Challenge::class);
        $challengelist = $repoC->findAll();

        for($i=1; $i<=1000; $i++){
            $attempt = new Attempt();

            $attempt->setAttempt($faker->password)
                ->setAttemptedOn($faker->dateTime)
                ->setAttemptedBy($userlist[$i%55])
                ->setChallenge($challengelist[$i%100]);

            $manager->persist($attempt);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ChallengeFixt::class,
        );
    }
}
