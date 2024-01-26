<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Entity\FridgeProduct;
use App\Entity\User;
use App\Form\FridgeProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        /** @var null|User $user */
        $user = $this->getUser();

        if ($user === null) {
            return new RedirectResponse($this->generateUrl('homepage'));
        }

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

    #[Route('/fridge/new', name: 'fridge_new')]
    public function new(Request $request): Response
    {
        $newFridgeProduct = new FridgeProduct();
        $form = $this->createForm(FridgeProductType::class, $newFridgeProduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fridge = $this->em->getRepository(Fridge::class)->findOneBy(['user' => $this->getUser()]);

            $existingProduct = $this->em->getRepository(FridgeProduct::class)->findOneBy([
                'product' => $newFridgeProduct->getProduct()->getId(),
                'fridge' => $fridge
            ]);

            if ($existingProduct) {
                $existingProduct->addQuantity($newFridgeProduct->getQuantity());

                $this->em->persist($existingProduct);
                $this->em->flush();

                return $this->redirectToRoute('fridge');
            }

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

    #[Route('/fridge/export.json', name: 'fridge_export')]
    function export(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $fridge = $this->em->getRepository(Fridge::class)->findOneBy(['user' => $user]);

        $products = [];
        foreach ($fridge->getProducts() as $product) {
            $products[] = [
                'id' => $product->getId(),
                'code' => $product->getProduct()->getCode(),
                'name' => $product->getProduct()->getName(),
                'quantity' => $product->getQuantity(),
                'unit' => $product->getProduct()->getUnit()
            ];
        }

        $response = new JsonResponse([
            'products' => $products
        ]);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'export.json'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/fridge/add/{id}', name: 'fridge_add')]
    public function add(int $id): RedirectResponse
    {
        $fridgeProduct = $this->em->getRepository(FridgeProduct::class)->find($id);
        $fridgeProduct->setQuantity($fridgeProduct->getQuantity() + 1);
        $this->em->flush();
        return $this->redirectToRoute('fridge');
    }

    #[Route('/fridge/subtract/{id}', name: 'fridge_subtract')]
    public function subtract(int $id): RedirectResponse
    {
        $fridgeProduct = $this->em->getRepository(FridgeProduct::class)->find($id);
        $fridgeProduct->setQuantity($fridgeProduct->getQuantity() - 1);
        if ($fridgeProduct->getQuantity() < 1) {
            $this->em->remove($fridgeProduct);
        }
        $this->em->flush();
        return $this->redirectToRoute('fridge');
    }
}
