<?php

namespace App\Repository;

use App\Entity\Challenge;
use App\Entity\User;
use App\Entity\Validation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Validation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Validation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Validation[]    findAll()
 * @method Validation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Validation::class);
    }

    public function getLastValidation(Challenge $challenge){
        return $this->findOneBy(['challenge' => $challenge], ['validatedOn' => 'DESC']);
    }

    public function getValidationOf(Challenge $challenge, User $user){
        return $this->findOneBy(['challenge' => $challenge, 'createdBy' => $user]);
    }

    public function getChallengeValidated(User $user){

        $qb = $this->createQueryBuilder('v')
            ->innerJoin('v.challenge', 'c')
            ->select('c.id')
            ->andWhere('v.createdBy = :user')
            ->setParameter('user', $user);

        $validations = $qb->getQuery()->getArrayResult();
        $challrepo = $this->getEntityManager()->getRepository('App:Challenge');

        return array_map(array($challrepo, 'find'), $validations);
    }

    public function getHarderValidated(User $user){
        $qb = $this->createQueryBuilder('v')
            ->innerJoin('v.challenge', 'c')
            ->select('c.difficulty')
            ->orderBy('c.difficulty', 'DESC')
            ->andWhere('v.createdBy = :user')
            ->setParameter('user', $user);

        $result = $qb->getQuery()->getResult();

        if($result){
            return $result[0]['difficulty'];
        }

        return 0;
    }

    // /**
    //  * @return Validation[] Returns an array of Validation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Validation
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
