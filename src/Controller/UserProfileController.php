<?php
namespace App\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserProfileFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class UserProfileController extends AbstractController
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userProfileRepository;
  
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('App:User');
        $this->userProfileRepository = $entityManager->getRepository('App:UserProfile');
    }



    /**
     * @Route("/profile", name="user_profile")
     */
    public function index()
    {
        return $this->render('user_profile/index.html.twig', [
            'controller_name' => 'UserProfileController',
        ]);
    }

    /**
     * @Route("/create-profile", name="create_profile")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createUserProfileAction(Request $request)
    {
        $userProfile = new UserProfile();
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $userProfile->setUserProfileAuthor($author);
        $form = $this->createForm(UserProfileFormType::class, $userProfile);
        $form->handleRequest($request);
        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($userProfile);
            $this->entityManager->flush($userProfile);
            $this->addFlash('success', 'Congratulations! Your user profile is created');
            return $this->redirectToRoute('user_profile');
        }
        return $this->render('user_profile/create-profile.html.twig', [
            'createProfileForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showYourProfileAction()
    {
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $userProfile = [];
        if ($author) {
            $userProfile = $this->userProfileRepository->findByUserProfileId($author);
        }
        return $this->render('user_profile/index.html.twig', [
            'userProfile' => $userProfile
        ]);
    }

    /**
     * @Route("/user-profile/edit/{userId}", name="edit_user_profile")
     * Method({"GET", "POST"})
     */
    public function editUserProfileAction(Request $request, $userId)
    {
        $userProfile = new UserProfile();
        $userProfile = $this->getDoctrine()->getRepository(UserProfile::class)->find($userId);
        $form = $this->createFormBuilder($userProfile)
            ->add('firstName', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('secondName', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('biography', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-orange mt-3')
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('user_profile');
        }
        $this->entityManager->persist($userProfile);
        $this->entityManager->flush($userProfile);
        $this->addFlash('warning', ' Your user profile was changed');
        return $this->render('user_profile/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete-profile/{userId}", name="delete_user_profile")
     *
     * @param $userId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserProfileAction($userId)
    {
        $userProfile = $this->userProfileRepository->findOneByUserProfileId($userId);
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        if (!$userProfile || $author !== $userProfile->getUserProfileId()) {
            $this->addFlash('error', 'Unable to remove profile!');
            return $this->redirectToRoute('user_profile');
        }
        $this->entityManager->remove($userProfile);
        $this->entityManager->flush();
        $this->addFlash('success', 'Profile was deleted!');
        return $this->redirectToRoute('user_profile');
    }

}