<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Recipe;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecipeFormType;

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
    public function viewAllRecipesAction()
    {
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();
        return $this->render('home/index.html.twig', array('recipes' => $recipes));
    }

    /**
     * @Route("/new", name="new_recipe")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createRecipeAction(Request $request)
    {
        $recipePost = new Recipe();
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $recipePost->setUserAuthor($author);
        $form = $this->createForm(RecipeFormType::class, $recipePost);
        $form->handleRequest($request);
        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($recipePost);
            $this->entityManager->flush($recipePost);
            $this->addFlash('success', 'Congratulations! Your recipe is created');
            return $this->redirectToRoute('show_all_recipe');
        }
        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }



}
