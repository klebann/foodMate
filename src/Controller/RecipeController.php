<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipes', name: 'recipes')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $recipes = $entityManager->getRepository(Recipe::class)->findAll();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipes/{id}', name: 'recipes_show')]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/recipes/import', name: 'import_recipes')]
    function import(Request $request, EntityManagerInterface $em)
    {
        $file = new File($request->files->get('import'));
        dd(json_decode($file->getContent()));


    }
}
