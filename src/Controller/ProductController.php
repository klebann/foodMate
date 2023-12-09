<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Entity\FridgeProduct;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    #[Template('product/index.html.twig')]
    public function index(EntityManagerInterface $entityManager): array
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        return [
            'products' => $products,
        ];
    }

    #[Route('/product/new', name: 'product_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $newProduct = new Product();
        $form = $this->createForm(ProductType::class, $newProduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newProduct);
            $em->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/{id}/edit', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/{id}/delete', name: 'product_delete')]
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('products');
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', ['product' => $product]);
    }
}
