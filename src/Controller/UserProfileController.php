<?php
namespace App\Controller;

use App\Entity\UserProfile;
use App\Service\UploaderHelper;
use App\Form\UserProfileFormType;
use App\Form\EditUserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    public function createUserProfileAction(Request $request, UploaderHelper $uploaderHelper)
    {
        $userProfile = new UserProfile();
        $author = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $userProfile->setUserProfileAuthor($author);
        $form = $this->createForm(UserProfileFormType::class, $userProfile);
        $form->handleRequest($request);
        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
           $uploadedFile = $form['profileImage']->getData();
           if ($uploadedFile) {
               $newFilename = $uploaderHelper->uploadImage($uploadedFile);
               $userProfile->setProfileImage($newFilename);
           }

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
    public function editUserProfileAction(Request $request, $userId, UploaderHelper $uploaderHelper)
    {
        $userProfile = new UserProfile();
        $userProfile = $this->getDoctrine()->getRepository(UserProfile::class)->find($userId);
        $form = $this->createForm(EditUserProfileFormType::class, $userProfile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['profileImage']->getData();
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadImage($uploadedFile);
                $userProfile->setProfileImage($newFilename);
            }

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