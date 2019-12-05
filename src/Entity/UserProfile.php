<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfileRepository")
 */
class UserProfile
{
    /**
     * @ORM\Id()
     * ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToOne(targetEntity="User",mappedBy="userProfileId")
     * @ORM\JoinColumn(name="user_profile_id", referencedColumnName="user_id")
     */
    private $userProfileId;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="second_name", type="string", length=50)
     */
    private $secondName;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="string", length=2000)
     */
    private $biography;

    /**
     * Set userProfileAuthor
     *
     * @param User $userProfileId
     *
     * @return UserProfile
     */
    public function setUserProfileAuthor(User $userProfileId)
    {
        $this->userProfileId = $userProfileId;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getUserProfileId()
    {
        return $this->userProfileId;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return UserProfile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set secondName
     * 
     * @param string $secondName
     * 
     * @return UserProfile
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;
        return $this;
    }

    /**
     * Get secondName
     * 
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Set biography
     *
     * @param string $biography
     *
     * @return UserProfile
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }
}