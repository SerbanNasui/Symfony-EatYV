<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecipeFormType;
use App\Form\EditRecipeFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\UploaderHelper;
use Knp\Component\Pager\PaginatorInterface;


class RecipeController extends AbstractController
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $recipeRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userProfileRepository;
    
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->recipeRepository = $entityManager->getRepository('App:Recipe');
        $this->userRepository = $entityManager->getRepository('App:User');
        $this->userProfileRepository = $entityManager->getRepository('App:UserProfile');
    }



    /**
     * @Route("/", name="show_all_recipe")
     */
    public function viewAllRecipesAction(PaginatorInterface $paginator, Request $request)
    {
        $recipesRepository = $this->getDoctrine()->getManager()->getRepository(Recipe::class);
        $allRecipesQuery = $recipesRepository->createQueryBuilder('p')
            ->where('p.recipeId != :recipeId')
            ->setParameter('recipeId', 'canceled')
            ->getQuery();
        
        $recipes = $paginator->paginate(
            $allRecipesQuery,
            $request->query->getInt('page', 1),
            // Items per page
            6
        );
        
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes
        ]);
    

        // $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();
        // return $this->render('home/index.html.twig', array('recipes' => $recipes));
    }

    /**
     * @Route("/new-recipe", name="new_recipe")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createRecipeAction(Request $request, UploaderHelper $uploaderHelper)
    {
        $recipePost = new Recipe();
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $recipePost->setUserAuthor($author);
        $form = $this->createForm(RecipeFormType::class, $recipePost);
        $form->handleRequest($request);
        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {

           
           /** @var UploadedFile $uploadedFile */
           $uploadedFile = $form['image']->getData();
           if ($uploadedFile) {
               $newFilename = $uploaderHelper->uploadImage($uploadedFile);
               $recipePost->setImage($newFilename);
           }

            $this->entityManager->persist($recipePost);
            $this->entityManager->flush($recipePost);
            $this->addFlash('success', 'Congratulations! Your recipe is created');
            return $this->redirectToRoute('show_all_recipe');
        }
        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show-recipe/{id}", name="show_recipe")
     */
    public function showRecipeAction($id)
    {
        $recipe = $this->recipeRepository->findOneByRecipeId($id);
        if (!$recipe) {
            $this->addFlash('error', 'Unable to find entry!');
            return $this->redirectToRoute('show_all_recipe');
        }
        return $this->render('recipe/show-recipe.html.twig', array(
            'recipe' => $recipe
        ));
    }

    /**
     * @Route("/own-recipes", name="user_own_recipes")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showYourOwnRecipesAction(PaginatorInterface $paginator, Request $request)
    {
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $recipes = $this->userRepository->createQueryBuilder('p');
        $recipes = [];
        if ($author) {
            $recipes = $this->recipeRepository->findByUserAuthor($author);
            $recipes = $paginator->paginate(
                $recipes,
                $request->query->getInt('page',1),
                6
            );
        }
        return $this->render('recipe/own-recipes.html.twig', [
            'recipes' => $recipes
        ]);
    }


    /**
     * @Route("/edit-recipe/{id}", name="edit_recipe")
     * Method({"GET", "POST"})
     */
    public function editRecipeAction(Request $request, $id, UploaderHelper $uploaderHelper) 
    {
        $recipe = new Recipe();
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);
        $form = $this->createForm(EditRecipeFormType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadImage($uploadedFile);
                $recipe->setImage($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
             $entityManager->flush();
             return $this->redirectToRoute('user_own_recipes');
        }
    
        $this->addFlash('success', 'Recipe is up-to-date!');
        return $this->render('recipe/edit-recipe.html.twig', array(
          'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete-recipe/{recipeId}", name="delete_recipe")
     *
     * @param $recipeId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteRecipeAction($recipeId)
    {
        $recipe = $this->recipeRepository->findOneByRecipeId($recipeId);
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        if (!$recipe || $author !== $recipe->getAuthor()) {
            $this->addFlash('error', 'Unable to remove recipe!');
            return $this->redirectToRoute('user_own_recipes');
        }
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();
        $this->addFlash('success', 'Recipe was deleted!');
        return $this->redirectToRoute('user_own_recipes');
    }

    /**
     * @Route("/show-owner-profile/{id}", name="show_owner_profile")
     */
    public function showOwnerProfileAction($id)
    {
        $profile = $this->userProfileRepository->findOneByUserProfileId($id);
        return $this->render('recipe/show-owner-profile.html.twig', array(
            'profile' => $profile
        ));
    }



}
