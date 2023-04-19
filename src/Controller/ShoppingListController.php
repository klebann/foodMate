<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingListController extends AbstractController
{
    #[Route('/shopping/list', name: 'app_shopping_list')]
    public function index(): Response
    {
        return $this->render('shopping_list/index.html.twig', [
            'controller_name' => 'ShoppingListController',
        ]);
    }
}
