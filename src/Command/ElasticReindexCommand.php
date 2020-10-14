<?php

namespace App\Command;

use App\Elasticseach\IndexBuilder;
use App\Elasticseach\PostIndexer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticReindexCommand extends Command
{
    protected static $defaultName = 'elastic:reindex';

    private $indexBuilder;
    private $postIndexer;

    public function __construct(IndexBuilder $indexBuilder, PostIndexer $postIndexer)
    {
        $this->indexBuilder = $indexBuilder;
        $this->postIndexer = $postIndexer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Rebuild the index and populate it.')
            ->addArgument('index_name', InputArgument::REQUIRED, 'Give a index name')
            ->addArgument('configuration_env_var', InputArgument::REQUIRED, 'Give the environement variable that points to the Yaml configuration file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $indexName = $input->getArgument('index_name');

        if(empty($_SERVER[$input->getArgument('configuration_env_var')])){
            throw new \ErrorException($input->getArgument('configuration_env_var') . ' Not exist. Please create a environement variable in .env file');  
        }
        
        $settingsFile = $_SERVER[$input->getArgument('configuration_env_var')];
        $io = new SymfonyStyle($input, $output);
        
        $index = $this->indexBuilder->create($indexName, $settingsFile);
        $io->success('Index created!');

        $this->postIndexer->indexAllDocuments($index->getName());
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
