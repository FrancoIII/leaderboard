<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ChallengeFixt extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        /** @var EntityRepository $repoU */
        /** @var QueryBuilder $queryU */
        $repoU = $manager->getRepository(User::class);
        $queryU = $repoU->createQueryBuilder('u');
        $queryU->where("u.roles = :valR")
            ->setParameter('valR', 'a:1:{i:0;a:2:{i:0;s:9:"ROLE_USER";i:1;s:10:"ROLE_ADMIN";}}');
        $adminlist = $queryU->getQuery()->getArrayResult();

        for($i=1; $i<=100; $i++){
            $challenge = new Challenge();
            $challenge->setName("Challenge n°$i")
                ->setDescription("Une description pasque j'ai la flemme de faker")
                ->setPassword($faker->password)
                ->setCreatedOn($faker->dateTime)
                ->setDifficulty(rand(1, 5))
                ->setReward(rand(1, 50)*$challenge->getDifficulty())
                ->setCreatedBy($repoU->findOneBy(['id'=>$adminlist[$i%5]]));

            $manager->persist($challenge);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixt::class,
        );
    }
}
