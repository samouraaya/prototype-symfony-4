<?php

/**
 * Description of CreateRoleInterfaceCommand
 *
 * @author Lamine Mansouri
 * @date 25/06/2020
 */

namespace App\Command\Config;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateRoleInterfaceCommand extends ContainerAwareCommand {

    // the name of the command (the part after "bin/console")
    //protected static $defaultName = 'app:create-interface';

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('app:create-interface')
                ->setDescription('generate and persist roleInterfaces')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $text = $this->getContainer()->get('app_role_interface_service')->createRoleInterfaces();
        $output->writeln($text);
    }

}
