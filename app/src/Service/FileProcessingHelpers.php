<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use App\Entity\Product;

class FileProcessingHelpers {

    private $entityManager;
    private $filesystem;

    CONST FILE_CSV = 'csv';
    CONST LOCAL_FILE = 'data.csv';

    private $ftp_details = ['host' => 'transport.productsup.io/', 'username' => 'pupDev', 'password' => 'pupDev2018', 'file_name' => 'coffee_feed_trimmed.xml'];

    public function __construct($projectDir, HttpClientInterface $client, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new XmlEncoder(), new CsvEncoder()]);
        $this->client = $client;
        $this->filesystem = new Filesystem();
        $this->projectDir = $projectDir;
    }

    public function createData($storageMode, $rawData) {
        $products = $rawData['item'];
        $stockBooksRepo = $this->entityManager->getRepository(Product::class);
        $newRecord = 0;
        $existedRecord = 0;
        if ($storageMode == self::FILE_CSV) {
            $this->filesystem->dumpFile(self::LOCAL_FILE, $this->serializer->encode($products, 'csv'));
            $successString = sprintf("CSV file %s created successfully", self::LOCAL_FILE);
        } else {
            foreach ($products as $product) {
                if ($stockBooksRepo->findBy(['entity_id' => $product['entity_id']])) {
                    $existedRecord++;
                    continue;
                }
                yield $newRecord++;
                $this->insertBookItem($product);
            }
            $successString = "$existedRecord record exists, $newRecord records added";
        }
        $this->entityManager->flush();
        return $successString;
    }

    public function insertBookItem($product) {
        $newProductItem = new Product();
        $newProductItem->setEntityId($product['entity_id']);
        $newProductItem->setCategoryName($product['CategoryName']);
        $newProductItem->setSku($product['sku']);
        $newProductItem->setName($product['name']);
        $newProductItem->setDescription($product['description']);
        $newProductItem->setShortdesc($product['shortdesc']);
        $newProductItem->setPrice($product['price']);
        $newProductItem->setLink($product['link']);
        $newProductItem->setImage($product['image']);
        $newProductItem->setBrand($product['Brand']);
        $newProductItem->setRating($product['Rating']);
        $newProductItem->setCaffineType($product['CaffeineType']);
        $newProductItem->setCount($product['Count']);
        $newProductItem->setFlavored($product['Flavored']);
        $newProductItem->setSeasonal($product['Seasonal']);
        $newProductItem->setInStock($product['Instock']);
        $newProductItem->setFacebook($product['Facebook']);
        $newProductItem->setIskCup($product['IsKCup']);
        try {
            $this->entityManager->persist($newProductItem);
        } catch (\Exception $ex) {
            throw new Exception('unable to insert data in database.!', 500, $ex);
        }
    }

    public function getXmlRowsAsArrays($pocessFile) {
        try {
            $pocessFile = $this->projectDir . $pocessFile;
            if (!$this->filesystem->exists($pocessFile)) {
                throw new \Exception("unable to access local file! $pocessFile", 503);
            }
            return $this->serializer->decode(file_get_contents($pocessFile), 'xml');
        } catch (\IOExceptionInterface $ex) {
            throw new \Exception("unable to get local storage file $pocessFile", 503, $ex);
        }
    }

    public function accessRemotefile(): array {
        $finder = new Finder();
        try {
            foreach ($finder->files()->in(sprintf('ftp://%s:%s@%s', $this->ftp_details['username'], $this->ftp_details['password'], $this->ftp_details['host']))->name($this->ftp_details['file_name']) as $file) {
                $content = file_get_contents($file->getPathname());
            }
            $content = $this->serializer->decode($content, 'xml');
            return $content;
        } catch (\Exception $ex) {
            throw new \Exception('unable to access remote file!', 503, $ex);
        }
    }

}
