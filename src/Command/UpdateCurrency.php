<?php

// src/Command/UpdateCurrency.php
namespace App\Command;

use App\Entity\Exchange;
use App\Entity\Provider;
use App\Service\Currency\CurrencyAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCurrency extends Command
{
    protected static $defaultName = 'currencies:update';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider_repository = $this->em->getRepository(Provider::class);

        $products = $provider_repository->findBy([
            'status' => '1'
        ]);

        if (! $products) {
            return;
        }

        foreach ($products as $product) {
            $currencyAdapter = new CurrencyAdapter($product);

            while ($currencyAdapter->getContent()) {
                $exchange = $this->em->getRepository(Exchange::class)->findOneBy([
                    'provider_id' => $product->getId(),
                    'code'        => $currencyAdapter->getCode(),
                ]);

                if (! $exchange) {
                    $exchange = new Exchange();
                }

                $exchange
                    ->setProviderId($product->getId())
                    ->setPrice($currencyAdapter->getPrice())
                    ->setCode($currencyAdapter->getCode());

                $this->em->persist($exchange);
            }

            $this->em->flush();
        }

        $exchange = $this->em->getRepository(Exchange::class)
            ->createQueryBuilder('exchange')
            ->select('MIN(exchange.price) as price')
            ->groupBy('exchange.code')
            ->getQuery()
            ->getResult();

        $exchange_prices = array_column($exchange, 'price');

        $exchange = $this->em->getRepository(Exchange::class)
            ->createQueryBuilder('exchange')
            ->delete()
            ->andWhere('exchange.price not in (:prices)')
            ->setParameter('prices', $exchange_prices)
            ->getQuery()
            ->execute();
    }
}
