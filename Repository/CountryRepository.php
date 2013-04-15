<?php

namespace Qwer\LottoFrontendBundle\Repository;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityRepository;
/**
 * CountryRepository
 */
class CountryRepository extends EntityRepository

{
    public function findAllOrderedByName()
    {
         return $this->getEntityManager()
            ->createQuery('SELECT p FROM QwerLottoFrontendBundle:Country\Country p ORDER BY p.name ASC')
            ->getResult();
    }
}