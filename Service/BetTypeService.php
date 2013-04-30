<?php

namespace Qwer\LottoFrontendBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BetTypeService extends ContainerAware
{

    /**
     *
     * @var \Qwer\LottoBundle\Repository\BetTypeRepository 
     */
    protected $typeRepo;

    public function getNameType($count)
    {
        $currentType = "";
        $betType = $this->typeRepo->findAll();
        foreach ($betType as $type) {
            if (in_array($count, $type->getAvailableBallsCount()) && $type->getCode() != "straight") {
                $currentType[] = $type;
            }
        }

        return $currentType;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->typeRepo = $this->em->getRepository("QwerLottoBundle:BetType");
    }

}