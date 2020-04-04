<?php

namespace App\DataFixtures;

use App\Entity\Challenge;
use App\Entity\User;
use App\Entity\Validation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ValidationFixt extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $repoU = $manager->getRepository(User::class);
        $userlist = $repoU->findAll();

        $repoC = $manager->getRepository(Challenge::class);
        $challengelist = $repoC->findAll();

        for($i=1; $i<=200; $i++){
            $validation = new Validation();

            /** @var User $user */
            $user = $userlist[$i%55];
            /** @var Challenge $challenge */
            $challenge = $challengelist[$i%100];

            $validation->setChallenge($challenge)
                ->setCreatedBy($user)
                ->setValidatedOn($faker->dateTime)
                ->setFeedback(rand(0, 5));

            $user->setScore($user->getScore() + $challenge->getReward());

            $manager->persist($validation);
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
