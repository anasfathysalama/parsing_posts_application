<?php

namespace App\Command;


use App\Message\SyncPostParsing;
use App\Services\ParsingPostsService;
use App\Sources\Coindesk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;


class ParsingPostsCommand extends Command
{

    public MessageBusInterface $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this->setName('parsing:posts')->setDescription('CLI Command For Scrapping Posts From Out Source');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start Parsing......");
        $this->bus->dispatch(new SyncPostParsing('fetch new post'));
        $output->writeln("Successfully Execution, Good Luck.");
        return Command::SUCCESS;
    }
}