<?php
namespace Qwer\LottoFrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class BetTypesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("bettypes:reset");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get("frontend.generator_service");
        $service->SetRateFormula();
    }

}
