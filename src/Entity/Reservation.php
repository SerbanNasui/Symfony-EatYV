<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="reservation_id")
     */
    private $reservationId;

    /**
     * @var string
     *
     * @ORM\Column(name="reservation_for_first_name", type="string", length=50)
     */
    private $reservationForFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="reservation_for_second_name", type="string", length=50)
     */
    private $reservationForSecondName;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=2000)
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="persons_participate", type="integer")
     */
    private $personsParticipate;

    /**
     *
     * @ORM\Column(name="date_time_coming", type="datetime")
     */
    private $dateTimeComing;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_reservation_id", referencedColumnName="user_id")
     */
    private $userReservaionId;

    /**
     * @var Recipe
     *
     * @ORM\ManyToOne(targetEntity="Recipe")
     * @ORM\JoinColumn(name="recipe_reservation_id", referencedColumnName="recipe_id")
     */
    private $recipeReservaionId;

    // ////////////////////////////// get  for reservation_id  ////////////////////////////////////////////////

    /**
     * Get id
     *
     * @return int
     */
    public function getReservationId(): ?int
    {
        return $this->reservationId;
    }

    // ////////////////////////////// get and set for first name ////////////////////////////////////////////////

    /**
     * Set reservationForFirstName
     *
     * @param string $reservationForFirstName
     *
     * @return Reservation
     */
    public function setReservationForFirstName($reservationForFirstName)
    {
        $this->reservationForFirstName = $reservationForFirstName;
        return $this;
    }

    /**
     * Get reservationForFirstName
     *
     * @return string
     */
    public function getReservationForFirstName()
    {
        return $this->reservationForFirstName;
    }

    // ////////////////////////////// get and set for second name ////////////////////////////////////////////////

    /**
     * Set reservationForSecondName
     * 
     * @param string $reservationForSecondName
     * 
     * @return Reservation
     */
    public function setReservationForSecondName($reservationForSecondName)
    {
        $this->reservationForSecondName = $reservationForSecondName;
        return $this;
    }

    /**
     * Get reservationForSecondName
     * 
     * @return string
     */
    public function getReservationForSecondName()
    {
        return $this->reservationForSecondName;
    }

    // ////////////////////////////// get and set for message ////////////////////////////////////////////////

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Reservation
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    // ////////////////////////////// get and set for user_id and recipe_id ////////////////////////////////////////////////

    /**
     * Set userReservaionId
     *
     * @param User $userReservaionId
     *
     * @return Reservation
     */
    public function setUserReservaionId(User $userReservaionId)
    {
        $this->userReservaionId = $userReservaionId;
        return $this;
    }
    /**
     * Get userReservaionId
     *
     * @return User
     */
    public function getUserReservaionId()
    {
        return $this->userReservaionId;
    }

    /**
     * Set recipeReservaionId
     *
     * @param Recipe $recipeReservaionId
     *
     * @return Reservation
     */
    public function setRecipeReservaionId(Recipe $recipeReservaionId)
    {
        $this->recipeReservaionId = $recipeReservaionId;
        return $this;
    }
    /**
     * Get recipeReservaionId
     *
     * @return Recipe
     */
    public function getRecipeReservaionId()
    {
        return $this->recipeReservaionId;
    }

    // ////////////////////////////// get and set for personsParticipate ////////////////////////////////////////////////

    /**
     * Set personsParticipate
     *
     * @param string $personsParticipate
     *
     * @return Reservation
     */
    public function setPersonsParticipate($personsParticipate)
    {
        $this->personsParticipate = $personsParticipate;
        return $this;
    }

    /**
     * Get personsParticipate
     *
     * @return string
     */
    public function getPersonsParticipate()
    {
        return $this->personsParticipate;
    }

    // ////////////////////////////// get and set for dateTimeComing ////////////////////////////////////////////////

    /**
     * Set dateTimeComing
     *
     * @param string $dateTimeComing
     *
     * @return Reservation
     */
    public function setDateTimeComing($dateTimeComing)
    {
        $this->dateTimeComing = $dateTimeComing;
        return $this;
    }

    /**
     * Get dateTimeComing
     *
     * @return string
     */
    public function getDateTimeComing()
    {
        return $this->dateTimeComing;
    }
}
