<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Entity\Product;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Amp\Iterator\concat;

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

    #[Route('/import/recipes', name: 'import_recipes')]
    function import(Request $request, EntityManagerInterface $em)
    {
        $file = new File($request->files->get('import'));
        $recipes = json_decode($file->getContent());

        foreach ($recipes as $recipe) {
            $repRecipe = $em->getRepository(Recipe::class)->findOneBy([
                'name' => $recipe->name
            ]);

            if ($repRecipe !== null) {
                continue;
            }

            $newRecipe = new Recipe();
            $newRecipe->setName($recipe->name);
            $newRecipe->setDescription($recipe->description);
            $newRecipe->setPrice($recipe->price);
            $newRecipe->setTime($recipe->time);
            $newRecipe->setServings($recipe->portion);
            $newRecipe->setDifficulty($recipe->difficulty);

            $instructions = "";
            foreach ($recipe->steps as $step) {
                $instructions .= "$step->step_number. $step->description\n";
            }
            $newRecipe->setInstructions($instructions);

            foreach ($recipe->ingredients as $ingredient) {
                $product = $em->getRepository(Product::class)->findOneBy([
                    'code' => $ingredient->bar_code
                ]);

                if ($product === null) {
                    $product = new Product();
                    $product->setName($ingredient->name);
                    $product->setUnit($ingredient->unit);
                    $product->setCode($ingredient->bar_code);
                    $em->persist($product);
                }

                $newIngredient = new Ingredients();
                $newIngredient->setProduct($product);
                $newIngredient->setQuantity($ingredient->quantity);
                $em->persist($newIngredient);

                $newRecipe->addIngredient($newIngredient);
            }
            $em->persist($newRecipe);
        }
        $em->flush();

        return $this->redirectToRoute('recipes');
    }
}
