<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Recipe;
use App\Entity\Reservation;
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
use App\Form\ReservationFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ReservationController extends AbstractController
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
    }



    /**
     * @Route("/reservation", name="reservation")
     */
    public function index()
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     *
     * @Route("/new-reservation/{id}", name="reservation_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function newReservationAction(Request $request, $id)
    {
        $reservation = new Reservation();

        $reservationAuthor = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $reservation->setUserReservaionId($reservationAuthor);

        $reservationRecipe = $this->recipeRepository->findOneByRecipeId($id);
        $reservation->setRecipeReservaionId($reservationRecipe);

        $reservationForm = $this->createForm(ReservationFormType::class, $reservation);
        $reservationForm->handleRequest($request);
  
        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            
            // $user = $form['userReservaionId']->getData();
            //$recipe = $reservationForm['recipeReservaionId']->getData();
            // $reservation->setUserReservaionId($user);
            //$reservation->setRecipeReservaionId($recipe);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('show_all_recipe', array('reservationId' => $reservation->getReservationId()));
        }

        return $this->render('reservation/new-reservation.html.twig', array(
            'reservation' => $reservation,
            'reservationForm' => $reservationForm->createView(),
        ));
    }

    /**
     * @Route("/own-reservation", name="own_reservation")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showYourOwnReservationAction()
    {
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $reservation = [];
        if ($author) {
            $reservation = $this->reservationRepository->findByUserReservaionId($author);
        }
        return $this->render('reservation/own-reservation.html.twig', [
            'reservation' => $reservation
        ]);
    }
    
    /**
     * @Route("/edit-reservation/{id}", name="edit_reservation")
     * Method({"GET", "POST"})
     */
    public function editReservationAction(Request $request, $id) 
    {
        $reservation = new Reservation();
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $form = $this->createFormBuilder($reservation)
          ->add('reservationForFirstName', TextType::class, array('attr' => array('class' => 'form-control')))
          ->add('reservationForSecondName', TextType::class, array('attr' => array('class' => 'form-control')))
          ->add('message', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
          ))
          ->add('save', SubmitType::class, array(
            'label' => 'Update reservation',
            'attr' => array('class' => 'btn btn-orange mt-3')
          ))
          ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
          return $this->redirectToRoute('own_reservation');
        }
    
        $this->addFlash('success', 'Reservation is up-to-date!');
        return $this->render('reservation/edit-reservation.html.twig', array(
          'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/delete-reservation/{reservationId}", name="delete_reservation")
     *
     * @param $reservationId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReservationAction($reservationId)
    {
        $reservation = $this->reservationRepository->findOneByReservationId($reservationId);
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        if (!$reservation || $author !== $reservation->getUserReservaionId()) {
            $this->addFlash('error', 'Unable to remove recipe!');
            return $this->redirectToRoute('user_own_recipes');
        }
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
        $this->addFlash('success', 'Reservation was deleted!');
        return $this->redirectToRoute('own_reservation');
    }

}
