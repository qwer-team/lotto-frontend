<?php

namespace Qwer\LottoFrontendBundle\Entity\Filter;

/**
 * Description of ResultFilter
 *
 * @author root
 */
class ResultFilter
{

    private $start;
    private $end;
    
    public function __construct()
    {
        $this->start = new \DateTime;
        $this->start->sub(\DateInterval::createFromDateString("1 month"));
        $this->end = new \DateTime;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
    }

}