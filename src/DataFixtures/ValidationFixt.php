<?php

namespace App\DataFixtures;

use App\Entity\Challenge;
use App\Entity\User;
use App\Entity\Validation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ValidationFixt extends Fixture
{
    public function getOrder()
    {
        return 4;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $repoU = $manager->getRepository(User::class);
        $userlist = $repoU->findAll();

        $repoC = $manager->getRepository(Challenge::class);
        $challengelist = $repoC->findAll();

        for($i=1; $i<=200; $i++){
            $validation = new Validation();

            $validation->setChallenge($challengelist[$i%100])
                ->setCreatedBy($userlist[$i%55])
                ->setValidatedOn($faker->dateTime)
                ->setFeedback(rand(0, 5));

            $manager->persist($validation);
        }

        $manager->flush();
    }
}
