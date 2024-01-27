<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar/{year}/{month}/{day}', name: 'app_calendar')]
    #[Template('calendar/index.html.twig')]
    public function index(EntityManagerInterface $em, Request $request, string $year = "0", string $month = "0", string $day = "0"): array
    {
        if ($year === "0") {
            $year = date('Y');
            $month = date('m');
            $day = date('d');
        }

        $meal = new Meal();
        $meal->setUser($this->getUser());
        $meal->setDate(new \DateTime("{$year}-{$month}-{$day}"));

        $recipes = $em->getRepository(Recipe::class)->findAll();

        $form = $this->createFormBuilder($meal)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Śniadanie' => '1',
                    'II Śniadanie' => '2',
                    'Lunch' => '3',
                    'Obiad' => '4',
                    'Przekąska' => '5',
                    'Kolacja' => '6'
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
            $meal = $form->getData();

            $em->persist($meal);
            $em->flush();
        }

        return [
            'form' => $form,
            'recipes' => $recipes
        ];
    }
}
