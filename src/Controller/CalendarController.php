<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Entity\FridgeProduct;
use App\Entity\Meal;
use App\Entity\Recipe;
use App\Entity\ShoppingList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar/{year}/{month}/{day}', name: 'app_calendar')]
    public function index(EntityManagerInterface $em, Request $request, string $year = "0", string $month = "0", string $day = "0"): \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($year === "0") {
            $year = date('Y');
            $month = date('m');
            $day = date('d');
        }
        $date = new \DateTime("{$year}-{$month}-{$day}");

        $meal = new Meal();
        $meal->setUser($this->getUser());
        $meal->setDate($date);
        $recipes = $em->getRepository(Recipe::class)->findAll();

        $form = $this->createFormBuilder($meal)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Śniadanie' => 'Śniadanie',
                    'II Śniadanie' => 'II Śniadanie',
                    'Lunch' => 'Lunch',
                    'Obiad' => 'Obiad',
                    'Przekąska' => 'Przekąska',
                    'Kolacja' => 'Kolacja'
                ]
            ])
            ->add('recipe', ChoiceType::class, [
                'choices' => $recipes,
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, ['label' => 'Dodaj'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Meal $meal */
            $meal = $form->getData();

            $em->persist($meal);

            $fridge = $em->getRepository(Fridge::class)->findOneBy(['user' => $this->getUser()]);

            foreach ($meal->getRecipe()->getIngredients() as $ingredient) {
                $fProduct = $em->getRepository(FridgeProduct::class)->findOneBy([
                    'product' => $ingredient->getProduct(),
                    'fridge' => $fridge
                ]);

                if ($fProduct === null) {
                    $shoppingList = new ShoppingList();
                    $shoppingList
                        ->setQuantity($ingredient->getQuantity())
                        ->setUser($this->getUser())
                        ->setProduct($ingredient->getProduct());
                    $em->persist($shoppingList);
                } else {
                    $quantity = $fProduct->getQuantity() - $ingredient->getQuantity();
                    $fProduct->setQuantity($quantity);
                    if ($quantity < 0) {
                        $shoppingList = new ShoppingList();
                        $shoppingList
                            ->setQuantity(-$quantity)
                            ->setUser($this->getUser())
                            ->setProduct($ingredient->getProduct());
                        $em->persist($shoppingList);
                    }
                    if ($quantity < 1) {
                        $em->remove($fProduct);
                    }
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_calendar', [
                'day' => $day,
                'month' => $month,
                'year' => $year
            ]);
        }

        $meals = $em->getRepository(Meal::class)->findBy([
            'date' => $date,
            'user' => $this->getUser()
        ]);

        return $this->render('calendar/index.html.twig', [
            'form' => $form,
            'recipes' => $recipes,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'meals' => $meals
        ]);
    }

    #[Route('/meal/{meal}/delete', name: 'delete_meal', options: ['expose' => true])]
    #[Template('calendar/index.html.twig')]
    public function delete(EntityManagerInterface $em, Meal $meal): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $em->remove($meal);
        $em->flush();
        return $this->redirectToRoute('app_calendar');
    }
}
