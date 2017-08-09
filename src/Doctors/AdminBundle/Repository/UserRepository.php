<?php

namespace Doctors\AdminBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserNumbers()
    {
        return $this->createQueryBuilder('u')
            ->select("COUNT(u)")
            ->getQuery()
            ->getSinglescalarResult();
    }
}