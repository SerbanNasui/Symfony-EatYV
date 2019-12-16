<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FoodCategoryRepository")
 */
class FoodCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Recipe", mappedBy="foodCategory")
     */
    private $recipePost;

    public function __construct()
    {
        $this->recipePost = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipePost(): Collection
    {
        return $this->recipePost;
    }

    public function addRecipePost(Recipe $recipePost): self
    {
        if (!$this->recipePost->contains($recipePost)) {
            $this->recipePost[] = $recipePost;
            $recipePost->setFoodCategory($this);
        }

        return $this;
    }

    public function removeRecipePost(Recipe $recipePost): self
    {
        if ($this->recipePost->contains($recipePost)) {
            $this->recipePost->removeElement($recipePost);
            // set the owning side to null (unless already changed)
            if ($recipePost->getFoodCategory() === $this) {
                $recipePost->setFoodCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}