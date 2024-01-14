<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/recipes/import', name: 'import_recipes')]
    function import(Request $request, EntityManagerInterface $em)
    {
        dd($request->files->get('import'));
//        $form = $this->createForm(ProductType::class, $newProduct);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form2->isValid()) {
//            /** @var UploadedFile */
//            $file = $form2->get('submitFile')->getData();
//
//            // Open the file
//            if (($handle = fopen($file->getPathname(), "r")) !== false) {
//                // Read and process the lines.
//                // Skip the first line if the file includes a header
//                while (($data = fgetcsv($handle)) !== false) {
//                    // Do the processing: Map line to entity, validate if needed
//                    $entity = new Entity();
//                    // Assign fields
//                    $entity->setField1($data[0]);
//                    $em->persist($entity);
//                }
//                fclose($handle);
//                $em->flush();
//            }
//        }
    }
}
