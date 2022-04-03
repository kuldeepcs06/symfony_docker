<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use App\Entity\Product;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class FileProcessingHelpers {

    private $entityManager;
    private $filesystem;

    CONST FILE_CSV = 'csv';
    CONST LOCAL_FILE = 'data.csv';
    CONST REMOTE_FILE = 'coffee_feed_trimmed.xml';
    CONST DB_BATCH_COUNT = 1000;

    /**
     * defined constructor with required parametes
     *
     * @param $params get configuration params from env
     * @param $entityManager entity manager
     * @return void
     */
    public function __construct(ContainerBagInterface $params, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new XmlEncoder(), new CsvEncoder()]);
        $this->filesystem = new Filesystem();
        $this->params = $params;
    }

    /**
     * Get the request to execute file with params
     *
     * @param $storageMode store in CSV or in database
     * @param $rawData array from input file
     * @return response string
     */
    public function createData($storageMode, $rawData) {
        $products = $rawData['item'];
        if ($storageMode == self::FILE_CSV) {
            $this->filesystem->dumpFile(self::LOCAL_FILE, $this->serializer->encode($products, 'csv'));
            $successString = sprintf("CSV file %s created successfully", self::LOCAL_FILE);
        } else {
            $successString = $this->insertInBatch($products);
        }
        $this->entityManager->flush();
        return $successString;
    }

    /**
     * Logic to check DB data and insert in batches
     *
     * @param array of data
     * @return response string
     */
    public function insertInBatch($products) {
        $newRecord = $existedRecord = 0;
        $stockBooksRepo = $this->entityManager->getRepository(Product::class);
        foreach ($products as $product) {
            if ($stockBooksRepo->findBy(['entity_id' => $product['entity_id']])) {
                $existedRecord++;
                continue;
            }
            $newRecord++;
            $this->insertBookItem($product);
            if (($newRecord % self::DB_BATCH_COUNT) == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        return sprintf("%s record exists, %s records added", $existedRecord, $newRecord);
    }

    /**
     * Insert Data into DB
     *
     * @param array of data
     * @return void
     */
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

    /**
     * Get data from local file and decode it to Array
     *
     * @param Local file from public folder
     * @return array
     */
    public function getXmlRowsAsArrays($pocessFile) {
        try {
            $pocessFile = $this->params->get('app.project_dir') . $pocessFile;
            if (!$this->filesystem->exists($pocessFile)) {
                throw new \Exception("unable to access local file! $pocessFile", 503);
            }
            return $this->serializer->decode(file_get_contents($pocessFile), 'xml');
        } catch (\IOExceptionInterface $ex) {
            throw new \Exception("unable to get local storage file $pocessFile", 503, $ex);
        }
    }

    /**
     * Get data from FTP server and decode it to Array
     *
     * @param void
     * @return array
     */
    public function accessRemotefile(): array {
        $finder = new Finder();
        try {
            $ftp_path = sprintf('ftp://%s:%s@%s', $this->params->get('app.ftp_user'), $this->params->get('app.ftp_password'), $this->params->get('app.ftp_host'));
            foreach ($finder->files()->in($ftp_path)->name(self::REMOTE_FILE) as $file) {
                $content = file_get_contents($file->getPathname());
            }
            $content = $this->serializer->decode($content, 'xml');
            return $content;
        } catch (\Exception $ex) {
            throw new \Exception('unable to access remote file!', 503, $ex);
        }
    }

}
