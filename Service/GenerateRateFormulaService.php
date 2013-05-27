<?php

namespace Qwer\LottoFrontendBundle\Service;

use Itc\DocumentsBundle\Listener\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Qwer\LottoFrontendBundle\Entity\RateFormula;

class GenerateRateFormulaService extends ContainerAware
{

    /**
     *
     * @var \Qwer\LottoBundle\Repository\BetTypeRepository 
     */
    protected $typeRepo;

    public function SetRateFormula()
    {
        $betTypes = $this->typeRepo->findAll();
        foreach ($betTypes as $type) {
            $code = $type->getCode();
            foreach ($type->getAvailableBallsCount() as $count) {
                $rateFormula = new RateFormula();
                $rateFormula->setBallsNumber($count);
                $rateFormula->setBetType($type);
                list($betNumber, $formula ) = $this->getFormula($count, $code);
                $rateFormula->setBetNumber($betNumber);
                $rateFormula->setFormula($formula);
                $this->em->merge($rateFormula);
            }
        }

        $this->em->flush();
    }

    private function getFormula($ballsNumber, $code)
    {
        $generator = $this->getBetLineGenerator($code);
        $lines = $generator->getBetLines(range(1, $ballsNumber));
        $resultArr = array();
        foreach ($lines as $line) {
            $balls = $line->getBalls();
            $ballsCnt = count($balls);
            if (isset($resultArr[$ballsCnt])) {
                $resultArr[$ballsCnt]++;
            } else {
                $resultArr[$ballsCnt] = 1;
            }
        }

        $formulaArr = array();
        foreach($resultArr as $ballsNum => $count) {
            $formulaArr[] = "$count*%$ballsNum%";
        }
        $formula = implode("+", $formulaArr);
        return array(count($lines), $formula);
    }

    /**
     * @param string $code
     * @return \Qwer\LottoDocumentsBundle\Service\BetLineGenerator
     * @throws ResourceNotFoundException
     */
    private function getBetLineGenerator($code)
    {
        $serviceId = sprintf("bet_line.generator.%s", $code);

        if (!$this->container->has($serviceId)) {
            $message = sprintf("Service \"%s\" was not found.", $serviceId);
            throw new ResourceNotFoundException($message);
        }

        $service = $this->container->get($serviceId);
        return $service;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->typeRepo = $this->em->getRepository("QwerLottoBundle:BetType");
    }

}