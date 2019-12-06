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
     * @Route("/new-reservation", name="reservation_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function newReservationAction(Request $request)
    {
        $reservation = new Reservation();

        $reservationAuthor = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $reservation->setUserReservaionId($reservationAuthor);

        $reservationForm = $this->createForm(ReservationFormType::class, $reservation);
        $reservationForm->handleRequest($request);
  
        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            
            // $user = $form['userReservaionId']->getData();
            $recipe = $reservationForm['recipeReservaionId']->getData();
        
            
            // $reservation->setUserReservaionId($user);
            $reservation->setRecipeReservaionId($recipe);
            
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
     * @Route("/show-reservation/{id}", name="show_reservation")
     */
    public function showReservationAction($id)
    {
        $reservation = $this->reservationRepository->findOneByReservationId($id);
        if (!$reservation) {
            $this->addFlash('error', 'Unable to find entry!');
            return $this->redirectToRoute('show_all_recipe');
        }
        return $this->render('reservation/show-reservation.html.twig', array(
            'reservation' => $reservation
        ));
    }

    //edit reservation
    
    //delete reservation
}
