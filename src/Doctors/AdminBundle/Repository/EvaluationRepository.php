<?php

namespace Doctors\AdminBundle\Repository;

/**
 * EvaluationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvaluationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getEvaluationNumbers()
    {
        return $this->createQueryBuilder('e')
            ->select("COUNT(e)")
            ->getQuery()
            ->getSinglescalarResult();
    }
}