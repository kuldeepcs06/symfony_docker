<?php

namespace App\Tests;

use App\Entity\Product;

class ProductTest extends DatabaseDependantTestCase {

    /** @test */
    public function a_product_record_can_be_created_in_the_database() {
        // Set up
        $newProductItem = new Product();
        $newProductItem->setEntityId('1001');
        $newProductItem->setCategoryName('Lorem ipsum doler');
        $newProductItem->setSku(50);
        $newProductItem->setName('Baril so Inso');
        $newProductItem->setDescription('Deyo nac Pa ce to');
        $newProductItem->setShortdesc('Symfo Na ho Te Ho');
        $newProductItem->setPrice(12);
        $newProductItem->setLink('google.com');
        $newProductItem->setImage('mcdn.coffeeforless.com/media/catalog/product/images/uploads/spoons.jpg');
        $newProductItem->setBrand('Marcal');
        $newProductItem->setRating(4);
        $newProductItem->setCaffineType('Caffeinated');
        $newProductItem->setCount(15);
        $newProductItem->setFlavored('YES');
        $newProductItem->setSeasonal('No');
        $newProductItem->setInStock('No');
        $newProductItem->setFacebook(1);
        $newProductItem->setIskCup(0);

        $this->entityManager->persist($newProductItem);

        // Do something
        $this->entityManager->flush();

        $productRepository = $this->entityManager->getRepository(Product::class);

        $productRecord = $productRepository->findOneBy(['entity_id' => '1001']);

        // Make assertions
        $this->assertEquals('1001', $productRecord->getEntityId());
        $this->assertEquals('Lorem ipsum doler', $productRecord->getCategoryName());
        $this->assertEquals(50, $productRecord->getSku());
        $this->assertEquals('Baril so Inso', $productRecord->getName());
        $this->assertEquals('Deyo nac Pa ce to', $productRecord->getDescription());
        $this->assertEquals('Symfo Na ho Te Ho', $productRecord->getShortdesc());
        $this->assertEquals(12, $productRecord->getPrice());
        $this->assertEquals('google.com', $productRecord->getLink());
        $this->assertEquals('mcdn.coffeeforless.com/media/catalog/product/images/uploads/spoons.jpg', $productRecord->getImage());
        $this->assertEquals('Marcal', $productRecord->getBrand());
        $this->assertEquals(4, $productRecord->getRating());
        $this->assertEquals('Caffeinated', $productRecord->getCaffineType());
        $this->assertEquals(15, $productRecord->getCount());
        $this->assertEquals('YES', $productRecord->getFlavored());
        $this->assertEquals('No', $productRecord->getSeasonal());
        $this->assertEquals('No', $productRecord->getInStock());
        $this->assertEquals(1, $productRecord->getFacebook());
        $this->assertEquals(0, $productRecord->getIskCup());
    }

}
