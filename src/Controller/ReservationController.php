<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReservationFormType;
use App\Form\EditReservationFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
    public function newReservationAction(Request $request, $id, MailerInterface $mailer, Recipe $maxNrPersons)
    {
        $reservation = new Reservation();

        $reservationAuthor = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $reservation->setUserReservaionId($reservationAuthor);

        $reservationRecipe = $this->recipeRepository->findOneByRecipeId($id);
        $reservation->setRecipeReservaionId($reservationRecipe);

        $reservationForm = $this->createForm(ReservationFormType::class, $reservation);
        $reservationForm->handleRequest($request);


        if ($reservationForm->isSubmitted() && $reservationForm->isValid() && $reservationForm['personsParticipate']->getData() <= $maxNrPersons->getMaxNrPersons()) {
            
            $email = (new TemplatedEmail())
                ->from('infomailer@eatyourvegetabels.com')
                ->to($reservationAuthor->getEmail())
                ->subject('Created new Reservation')
                ->htmlTemplate('email/notify_user_created_reservation.html.twig')
                ->context([
                    'reservationRecipe' => $reservationRecipe,
                    'reservationAuthor' => $reservationAuthor
                ]);

                
                $author = $reservationRecipe->getAuthor();
                $emailToRecipeAuthor = (new TemplatedEmail())
                ->from('infomailer@eatyourvegetabels.com')
                ->to($author->getEmail())
                ->subject('Have a new reservation')
                ->htmlTemplate('email/notify_user_have_reservation.html.twig')
                 ->context([
                     'reservationRecipe' => $reservationRecipe,
                     'reservation' => $reservation
                 ]);
                

            $mailer->send($email);
            $mailer->send($emailToRecipeAuthor);

            $maxNrPersons->setMaxNrPersons($maxNrPersons->getMaxNrPersons() - $reservationForm['personsParticipate']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('own_reservation', array('reservationId' => $reservation->getReservationId()));
        } else if($reservationForm['personsParticipate']->getData() >= $maxNrPersons->getMaxNrPersons()){
            $this->addFlash('warning', 'You can add only '.$maxNrPersons->getMaxNrPersons().' persons for this reservation!!!');
        }

        return $this->render('reservation/new-reservation.html.twig', array(
            'reservation' => $reservation,
            'reservationForm' => $reservationForm->createView(),
        ));
    }

    /**
     * @Route("/own-reservation", name="own_reservation")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showYourOwnReservationAction(PaginatorInterface $paginator, Request $request)
    {
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $reservation = $this->userRepository->createQueryBuilder('p');
        $reservation = [];
        if ($author) {
            $reservation = $this->reservationRepository->findByUserReservaionId($author);
            $reservation = $paginator->paginate(
                $reservation,
                $request->query->getInt('page',1),
                12
            );
        }
        return $this->render('reservation/own-reservation.html.twig', [
            'reservation' => $reservation
        ]);
    }
    
    /**
     * @Route("/edit-reservation/{id}", name="edit_reservation")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * Method({"GET", "POST"})
     */
    public function editReservationAction(Request $request, $id) 
    {
        $reservation = new Reservation();
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(EditReservationFormType::class, $reservation);
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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

    /**
     * @Route("/show-reservations-for-specific-recipe/{id}", name="show_reservations_for_specific_recipe")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showReservationForSpecificRecipe($id, PaginatorInterface $paginator, Request $request)
    {
        $recipeReservation = $this->recipeRepository->findOneByRecipeId($id);
        $showReservation = $this->reservationRepository->createQueryBuilder('p');
        $showReservation = [];
        if ($recipeReservation) {
            $showReservation = $this->reservationRepository->findByRecipeReservaionId($id);
            $showReservation = $paginator->paginate(
                $showReservation,
                $request->query->getInt('page',1),
                8
            );
        }

        return $this->render('reservation/show-reservations-for-recipe.html.twig', [
            'showReservation' => $showReservation
        ]);
    }
}