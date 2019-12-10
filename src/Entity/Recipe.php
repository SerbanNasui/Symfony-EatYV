<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="recipe_id")
     */
    private $recipeId;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2000)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string")
     */
    private $image;
    
    /**
     *
     *  @var \DateTime
     * 
     * @ORM\Column(name="date_start", type="datetime")
     */
    private $dateStart;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="date_end", type="datetime")
     */
    private $dateEnd;
    
    /**
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /**
     *
     * @ORM\Column(name="max_nr_persons", type="integer")
     */
    private $maxNrPersons;

    /**
     *
     * @ORM\Column(name="address", type="string")
     */
    private $address;

    /**
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     *
     * @ORM\Column(name="country", type="string")
     */
    private $country;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_author_id", referencedColumnName="user_id")
     */
    private $userAuthor;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getRecipeId()
    {
        return $this->recipeId;
    }
    
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Recipe
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Recipe
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Recipe
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set userAuthor
     *
     * @param User $userAuthor
     *
     * @return Recipe
     */
    public function setUserAuthor(User $userAuthor)
    {
        $this->userAuthor = $userAuthor;
        return $this;
    }
    /**
     * Get userAuthor
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->userAuthor;
    }

    /**
     * Set dateStart
     *
     * @return Recipe
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
        return $this;
    }
    /**
     * Get dateStart
     *
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @return Recipe
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }
    /**
     * Get dateEnd
     *
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }


    /**
     * Set price
     *
     * @return Recipe
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    /**
     * Get price
     *
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set maxNrPersons
     *
     * @return Recipe
     */
    public function setMaxNrPersons($maxNrPersons)
    {
        $this->maxNrPersons = $maxNrPersons;
        return $this;
    }
    /**
     * Get maxNrPersons
     *
     */
    public function getMaxNrPersons()
    {
        return $this->maxNrPersons;
    }

    /**
     * Set address
     *
     * @return Recipe
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    /**
     * Get address
     *
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @return Recipe
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    /**
     * Get city
     *
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @return Recipe
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
    /**
     * Get country
     *
     */
    public function getCountry()
    {
        return $this->country;
    }
}