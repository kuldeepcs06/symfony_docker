<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use \Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\BookItem;

class ProcessFileCommand extends Command {

    protected static $defaultName = 'app-process-file';

    public function __construct($projectDir, EntityManagerInterface $entityManager, HttpClientInterface $client) {
        $this->projectDir = $projectDir;
        $this->entityManager = $entityManager;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new XmlEncoder(), new CsvEncoder()]);
        $this->client = $client;
        parent::__construct();
    }

    protected function configure() {
        $this->setDescription('Process file')
                ->addOption('mode', null, InputOption::VALUE_REQUIRED, 'What is input mode', ['local', 'remote'])
                ->addOption('storage', null, InputOption::VALUE_REQUIRED, 'What is storage mode', ['csv', 'database'])
                ->addOption('file', null, InputOption::VALUE_REQUIRED, 'Input complete file path', '/public/xml-files/file1.xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        
        $pocessMode = $input->getOption('mode');
        if($pocessMode == 'remote'){
            $books = $this->fetchRemoteInformation();
            echo "\r\n Remote fetch Called";
        } else {
            $pocessFile = $input->getOption('file');
            $books = $this->getXmlRowsAsArrays($pocessFile);
            echo "\r\n Local fetch Called";
        }
        $storageMode = $input->getOption('storage');
        $stockBooksRepo = $this->entityManager->getRepository(BookItem::class);

        $newRecord = 0;
        $existedRecord = 0;
        $successString = "";

        
        if ($storageMode == 'csv') {
            file_put_contents(
                    'data.csv',
                    $this->serializer->encode($books['book'], 'csv')
            );
            $successString = "CSV file updated successfully";
        } else {
            foreach ($books['book'] as $book) {
                if ($stockBooksRepo->findBy(['author' => $book['author']])) {
                    $existedRecord++;
                    continue;
                }
                $newRecord++;
                $this->insertBookItem($book);
            }
            $successString = "$existedRecord record exists, $newRecord records added";
        }

        $this->entityManager->flush();
        $io = new SymfonyStyle($input, $output);
        $io->success($successString);
        return Command::SUCCESS;
    }

    public function insertBookItem($book) {
        $newBookItem = new BookItem();
        $newBookItem->setAuthor($book['author']);
        $newBookItem->setTitle($book['title']);
        $newBookItem->setGenre($book['genre']);
        $newBookItem->setPrice($book['price']);
        $newBookItem->setPublishDate(\DateTime::createFromFormat('Y-m-d', $book['publish_date']));
        $newBookItem->setDescription($book['description']);
        try {
            $this->entityManager->persist($newBookItem);
        } catch (\Exception $ex) {
            print_r($ex);
        }
    }

    public function getXmlRowsAsArrays($pocessFile) {
        $inputFile = $this->projectDir . $pocessFile;
        return $this->serializer->decode(file_get_contents($inputFile), 'xml');
    }

    public function fetchRemoteInformation(): array
    {
        $response = $this->client->request(
            'GET',
            'https://gist.githubusercontent.com/Ram-N/5189462/raw/46db0b43ad7bf9cbd32a8932f3ab981bd4b4da7c/books.xml'
        );
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $this->serializer->decode($content, 'xml');
        return $content;
    }
}
