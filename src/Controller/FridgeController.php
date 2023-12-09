<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Entity\FridgeProduct;
use App\Entity\User;
use App\Form\FridgeProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FridgeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/fridge', name: 'fridge')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $fridge = $this->em->getRepository(Fridge::class)->findOneBy(['user' => $user]);

        if ($fridge === null) {
            $fridge = new Fridge();
            $fridge->setUser($user);
            $this->em->persist($fridge);
            $this->em->flush();
        }

        return $this->render('fridge/index.html.twig', [
            'fridge_products' => $fridge->getProducts(),
        ]);
    }

    #[Route('/fridge/add', name: 'fridge_add')]
    public function add(Request $request): Response
    {
        $newFridgeProduct = new FridgeProduct();
        $form = $this->createForm(FridgeProductType::class, $newFridgeProduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingProduct = $this->em->getRepository(FridgeProduct::class)->findOneBy([
                'product' => $newFridgeProduct->getProduct()->getId()
            ]);
            if ($existingProduct) {
                $existingProduct->addQuantity($newFridgeProduct->getQuantity());

                $this->em->persist($existingProduct);
                $this->em->flush();

                return $this->redirectToRoute('fridge');
            }

            $fridge = $this->em->getRepository(Fridge::class)->find($this->getUser());
            $newFridgeProduct->setFridge($fridge);

            $this->em->persist($newFridgeProduct);
            $this->em->flush();

            return $this->redirectToRoute('fridge');
        }

        return $this->render('fridge/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/fridge/delete/{id}', name: 'fridge_delete')]
    public function delete(int $id): RedirectResponse
    {
        $fridgeProduct = $this->em->getRepository(FridgeProduct::class)->find($id);
        $this->em->remove($fridgeProduct);
        $this->em->flush();
        return $this->redirectToRoute('fridge');
    }
}
