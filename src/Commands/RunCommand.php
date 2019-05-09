<?php

namespace Husky\Commands;

use Husky\Payloads\FlagPayload;
use Husky\Payloads\HuskyCodePayload;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends BaseCommand
{
    /** @var string */
    private $action = 'run';

    protected function configure()
    {
        $this->setName($this->action)
             ->addArgument('hookName', InputArgument::REQUIRED, 'which hook trigger run')
             ->addArgument('gitParams', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'gitParams');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $hookName = $input->getArgument('hookName');
        $gitParams = $input->getArgument('gitParams');

        exec("$this->huskyPath {$this->package}:{$this->action} {$hookName} {$gitParams}", $result, $code);

        if ($code === HuskyCodePayload::SUCCESS) {
            $flags = FlagPayload::SUCCESS;
        } else {
            $flags = FlagPayload::FAIL;
        }

        $output->writeln([
            FlagPayload::COMMENT[0] . '---------- composer-husky-plugin -----------' . FlagPayload::COMMENT[1],
            $flags[0] . implode($result, PHP_EOL) . $flags[1]
        ]);
    }
}