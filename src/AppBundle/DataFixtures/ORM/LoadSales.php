<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sale;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Crea los datos de prueba para la entidad Photo.
 *
 * @author Enrique José Esteban Plaza <ense.esteban@gmail.com>
 */
class LoadSales extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Insertamos una factura de compra
        $sale = new Sale();

        $sale->setDevice($this->getReference('device-iphone-7'));
        $sale->setColor('negro');
        $sale->setStorageSize('128 Gb');
        $sale->setCategory('A');
        $sale->setClient($this->getReference('client-andrea'));
        $sale->setSeller($this->getReference('employee-florin'));
        $sale->setPrice(450.00);
        $sale->setAdvance(0);
        $sale->setDiscount(0);
        $sale->setState($this->getReference('state-para-venta'));
        $sale->setDate(new \DateTime('Now -'.rand(0, 5).'days'));

        $this->addReference('sale-iphone-7', $sale);
        $manager->persist($sale);

        // Insertamos otra factura de compra
        $sale = new Sale();

        $sale->setDevice($this->getReference('device-iphone-7-plus'));
        $sale->setColor('oro rosa');
        $sale->setStorageSize('512 Gb');
        $sale->setCategory('A');
        $sale->setClient($this->getReference('client-andrea'));
        $sale->setSeller($this->getReference('employee-alberto'));
        $sale->setPrice(500.00);
        $sale->setAdvance(0);
        $sale->setDiscount(0);
        $sale->setDate(new \DateTime('Now -'.rand(0, 5).'days'));
        $sale->setState($this->getReference('state-entregado'));

        $this->addReference('sale-iphone-7-plus', $sale);
        $manager->persist($sale);

        // Insertamos otra factura de compra
        $sale = new Sale();

        $sale->setDevice($this->getReference('device-moto-g5-plus'));
        $sale->setColor('blanco');
        $sale->setStorageSize('64 Gb');
        $sale->setCategory('B');
        $sale->setClient($this->getReference('client-enrique'));
        $sale->setSeller($this->getReference('employee-florin'));
        $sale->setPrice(150.00);
        $sale->setAdvance(0);
        $sale->setDiscount(50);
        $sale->setDate(new \DateTime('Now -'.rand(0, 5).'days'));
        $sale->setState($this->getReference('state-para-venta'));

        $this->addReference('sale-moto-g5-plus', $sale);
        $manager->persist($sale);

        // Insertamos otra factura de compra
        $sale = new Sale();

        $sale->setDevice($this->getReference('device-galaxy-s7-edge'));
        $sale->setColor('negro');
        $sale->setStorageSize('128 Gb');
        $sale->setCategory('B');
        $sale->setClient($this->getReference('client-antonio'));
        $sale->setSeller($this->getReference('employee-alberto'));
        $sale->setPrice(350.00);
        $sale->setAdvance(0);
        $sale->setDiscount(0);
        $sale->setDate(new \DateTime('Now -'.rand(0, 5).'days'));
        $sale->setState($this->getReference('state-entregado'));

        $this->addReference('sale-galaxy-s7-edge', $sale);
        $manager->persist($sale);

        // Insertamos otra factura de compra
        $sale = new Sale();

        $sale->setDevice($this->getReference('device-huawei-p8-lite'));
        $sale->setColor('negro');
        $sale->setStorageSize('32 Gb');
        $sale->setCategory('C');
        $sale->setClient($this->getReference('client-juan'));
        $sale->setSeller($this->getReference('employee-florin'));
        $sale->setPrice(100.00);
        $sale->setAdvance(0);
        $sale->setDiscount(100);
        $sale->setDate(new \DateTime('Now -'.rand(0, 5).'days'));
        $sale->setState($this->getReference('state-para-venta'));

        $this->addReference('sale-huawei-p8-lite', $sale);
        $manager->persist($sale);

        // Insertamos otra factura de compra
        $sale = new Sale();

        $sale->setDevice($this->getReference('device-bq-aquaris-x5'));
        $sale->setColor('rojo');
        $sale->setStorageSize('32 Gb');
        $sale->setCategory('C');
        $sale->setClient($this->getReference('client-jose'));
        $sale->setSeller($this->getReference('employee-alberto'));
        $sale->setPrice(75.00);
        $sale->setAdvance(0);
        $sale->setDiscount(0);
        $sale->setDate(new \DateTime('Now -'.rand(0, 5).'days'));
        $sale->setState($this->getReference('state-entregado'));

        $this->addReference('sale-bq-aquaris-x5', $sale);
        $manager->persist($sale);

        // Persistimos la base de datos
        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}