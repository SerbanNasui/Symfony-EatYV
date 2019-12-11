<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeReviewRepository")
 */
class RecipeReview
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="recipe_review_id")
     */
    private $recipeReviewId;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=2000)
     */
    private $comment;

    /**
     *
     * @ORM\Column(name="grade", type="integer")
     */
    private $grade;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_review_recipe_id", referencedColumnName="user_id")
     */
    private $userReviewRecipeId;

    /**
     * @var Recipe
     *
     * @ORM\ManyToOne(targetEntity="Recipe")
     * @ORM\JoinColumn(name="recipe_review_recipe_id", referencedColumnName="recipe_id")
     */
    private $recipeReviewRecipeId;

    /**
     * Get id
     *
     * @return int
     */
    public function getRecipeReviewId(): ?int
    {
        return $this->recipeReviewId;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return RecipeReview
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set grade
     *
     * @return RecipeReview
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
        return $this;
    }
    /**
     * Get grade
     *
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set userReviewRecipeId
     *
     * @param User $userReviewRecipeId
     *
     * @return RevireRecipe
     */
    public function setUserReviewRecipeId(User $userReviewRecipeId)
    {
        $this->userReviewRecipeId = $userReviewRecipeId;
        return $this;
    }
    /**
     * Get userReservaionId
     *
     * @return User
     */
    public function getUserReviewRecipeId()
    {
        return $this->userReviewRecipeId;
    }

    /**
     * Set recipeReviewRecipeId
     *
     * @param Recipe $recipeReviewRecipeId
     *
     * @return ReviewRecipe
     */
    public function setRecipeReviewRecipeId(Recipe $recipeReviewRecipeId)
    {
        $this->recipeReviewRecipeId = $recipeReviewRecipeId;
        return $this;
    }
    /**
     * Get recipeReviewRecipeId
     *
     * @return Recipe
     */
    public function getRecipeReviewRecipeId()
    {
        return $this->recipeReviewRecipeId;
    }
}
