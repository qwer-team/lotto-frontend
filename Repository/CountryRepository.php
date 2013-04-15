<?php

namespace Qwer\LottoFrontendBundle\Repository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Qwer\LottoFrontendBundle\Entity\Country")
 */
class CountryRepository extends EntityRepository

{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM QwerLottoFrontendBundle:Country\Country c ORDER BY c.name ASC')
            ->getResult();
    }
}