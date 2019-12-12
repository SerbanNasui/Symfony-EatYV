<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Recipe;
use App\Entity\RecipeReview;
use App\Entity\User;
use App\Entity\UserProfile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecipeReviewFormType;
use App\Form\EditReviewRecipeFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RecipeReviewController extends AbstractController
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $recipeRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userProfileRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $reservationRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $recipeReviewRepository;
    
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->recipeRepository = $entityManager->getRepository('App:Recipe');
        $this->userRepository = $entityManager->getRepository('App:User');
        $this->userProfileRepository = $entityManager->getRepository('App:UserProfile');
        $this->reservationRepository = $entityManager->getRepository('App:Reservation');
        $this->recipeReviewRepository = $entityManager->getRepository('App:RecipeReview');
    }


    /**
     * @Route("/recipe-review", name="recipe_review")
     */
    public function index()
    {
        return $this->render('recipe_review/index.html.twig', [
            'controller_name' => 'RecipeReviewController',
        ]);
    }

    /**
     *
     * @Route("/new-recipe-review/{id}", name="new_recipe_review")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function newRecipeReviewAction(Request $request, $id)
    {
        $recipeReview = new RecipeReview();

        $reviewAuthor = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $recipeReview->setUserReviewRecipeId($reviewAuthor);

        $reviewForRecipe = $this->recipeRepository->findOneByRecipeId($id);
        $recipeReview->setRecipeReviewRecipeId($reviewForRecipe);

        $recipeReviewForm = $this->createForm(RecipeReviewFormType::class, $recipeReview);
        $recipeReviewForm->handleRequest($request);
  
        if ($recipeReviewForm->isSubmitted() && $recipeReviewForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipeReview);
            $em->flush();
            $this->addFlash('success', 'Review was created!');
        }
        
        return $this->render('recipe_review/new-recipe-review.html.twig', array(
            'recipeReview' => $recipeReview,
            'recipeReviewForm' => $recipeReviewForm->createView(),
        ));
    }

    /**
     * @Route("/show-reviews-for-specific-recipe/{id}", name="show_reviews_for_specific_recipe")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showReviewsForSpecificRecipe($id)
    {
        $recipeReview = $this->recipeRepository->findOneByRecipeId($id);
        $showReview = [];
        if ($recipeReview) {
            $showReview = $this->recipeReviewRepository->findByRecipeReviewRecipeId($id);
        }

        return $this->render('recipe_review/show-reviews-for-recipe.html.twig', [
            'showReview' => $showReview
        ]);
    }

    /**
     * @Route("/delete-recipe-review/{reviewId}", name="delete_recipe_review")
     *
     * @param $reviewId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReservationAction($reviewId)
    {
        $reviewRecipe = $this->recipeReviewRepository->findOneByRecipeReviewId($reviewId);
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        if (!$reviewRecipe || $author !== $reviewRecipe->getUserReviewRecipeId()) {
            $this->addFlash('error', 'Unable to remove recipe!');
        }
        $this->entityManager->remove($reviewRecipe);
        $this->entityManager->flush();
        $this->addFlash('success', 'Review was deleted!');
        return $this->redirectToRoute('show_all_recipe');
    }

    /**
     * @Route("/edit-review-recipe/{id}", name="edit_review_recipe")
     * Method({"GET", "POST"})
     */
    public function editReviewRecipeAction(Request $request, $id) 
    {
        $reviewRecipe = new RecipeReview();
        $reviewRecipe = $this->getDoctrine()->getRepository(RecipeReview::class)->find($id);
        $form = $this->createForm(EditReviewRecipeFormType::class, $reviewRecipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
          return $this->redirectToRoute('show_all_recipe');
        }
    
        $this->addFlash('success', 'Review is up-to-date!');
        return $this->render('recipe_review/edit-recipe-review.html.twig', array(
          'form' => $form->createView()
        ));
    }
}
