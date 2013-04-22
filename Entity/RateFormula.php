<?php

namespace Qwer\LottoFrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RateFormula
 */
class RateFormula
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Qwer\LottoBundle\Entity\BetType
     */
    private $betType;

    /**
     * @var integer
     */
    private $ballsNumber;

    /**
     * @var integer
     */
    private $betNumber;

    /**
     * @var string
     */
    private $formula;
    private $betTypeId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set betType
     *
     * @param string $betType
     * @return RateFormula
     */
    public function setBetType($betType)
    {
        $this->betType = $betType;
        $this->betTypeId = $betType->getId();
        return $this;
    }

    /**
     * Get betType
     *
     * @return string 
     */
    public function getBetType()
    {
        return $this->betType;
    }

    /**
     * Set ballsNumber
     *
     * @param integer $ballsNumber
     * @return RateFormula
     */
    public function setBallsNumber($ballsNumber)
    {
        $this->ballsNumber = $ballsNumber;

        return $this;
    }

    /**
     * Get ballsNumber
     *
     * @return integer 
     */
    public function getBallsNumber()
    {
        return $this->ballsNumber;
    }

    /**
     * Set betNumber
     *
     * @param integer $betNumber
     * @return RateFormula
     */
    public function setBetNumber($betNumber)
    {
        $this->betNumber = $betNumber;

        return $this;
    }

    /**
     * Get betNumber
     *
     * @return integer 
     */
    public function getBetNumber()
    {
        return $this->betNumber;
    }

    /**
     * Set formula
     *
     * @param string $formula
     * @return RateFormula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;

        return $this;
    }

    /**
     * Get formula
     *
     * @return string 
     */
    public function getFormula()
    {
        return $this->formula;
    }

    public function getBetTypeId()
    {
        return $this->betTypeId;
    }

    public function setBetTypeId($betTypeId)
    {
        $this->betTypeId = $betTypeId;
    }

}
