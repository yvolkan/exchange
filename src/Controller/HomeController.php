<?php

namespace App\Controller;

use App\Entity\Exchange;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        if ($exchange = $this->getDoctrine()->getRepository(Exchange::class)->findAll()) {
            $currencies = [];

            foreach ($exchange as $currency) {
                $currencies[] = [
                    'price' => $currency->getPrice(),
                    'code'  => $currency->getCode(),
                ];
            }
        }


        return $this->json($currencies);
    }
}
