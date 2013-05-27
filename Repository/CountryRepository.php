<?php

namespace Qwer\LottoFrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * CountryRepository
 */
class CountryRepository extends EntityRepository

{
    public function findAllOrderedByName()
    {
        $qb = $this->createQueryBuilder("country");
        $qb->select("country, types");
        $qb->innerJoin("country.lottoTypes", "types")
           ->orderBy("country.title");
          
        

        $set = $qb->getQuery()->execute();
        return $set;
    }
    
    public function getFullSchedule()
    {
        $qb = $this->createQueryBuilder("country");
        $qb->select("country, types, times");
        $qb->innerJoin("country.lottoTypes", "types")
           ->innerJoin("types.lottoTimes", "times")
           ->orderBy("country.title");
          
        

        $set = $qb->getQuery()->execute();
        return $set;
    }
    
}