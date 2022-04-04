<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
use App\Service\FileProcessingHelpers;

class ProcessFileCommand extends Command {

    protected static $defaultName = 'app-process-file';
    private FileProcessingHelpers $fileProcessingHelpers;

    CONST PROCESS_REMOTE = 'remote';

    public function __construct(FileProcessingHelpers $fileProcessingHelpers, LoggerInterface $logger) {
        $this->fileProcessingHelpers = $fileProcessingHelpers;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure() {
        $this->setDescription('Process file')
                ->addOption('mode', null, InputOption::VALUE_REQUIRED, 'What is input mode', ['local', 'remote'])
                ->addOption('storage', null, InputOption::VALUE_REQUIRED, 'What is storage mode', ['csv', 'database'])
                ->addOption('file', null, InputOption::VALUE_REQUIRED, 'Input complete file path', '/public/xml-files/coffee_feed.xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        $storageMode = $input->getOption('storage');
        $processMode = $input->getOption('mode');
        $pocessFile = $input->getOption('file');
        try {
            if ($processMode == self::PROCESS_REMOTE) {
                $fileData = $this->fileProcessingHelpers->accessRemotefile();
            } else {
                $fileData = $this->fileProcessingHelpers->getXmlRowsAsArrays($pocessFile);
            }
            $responseString = $this->fileProcessingHelpers->createData($storageMode, $fileData);
            
            $this->logger->info($responseString, [$storageMode, $processMode]);
            $io->success(trim($responseString));
            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $io->error(sprintf('Error Message: %s |Error code: %s', $ex->getMessage(), $ex->getCode()));
            $this->logger->error(sprintf('Error Message: %s |Error code: %s | File: %s | Line: %s', $ex->getMessage(), $ex->getCode(), $ex->getFile(), $ex->getLine()), [$storageMode, $processMode]);
            return Command::INVALID;
        }
    }

}
