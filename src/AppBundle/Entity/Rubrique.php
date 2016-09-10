<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RubriqueRepository")
 */
class Rubrique
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="icone", type="string", length=255)
     */
    private $icone;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="text", nullable=true)
     */
    private $subtitle;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="rubrique")
     */
    private $posts;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Newsletter", inversedBy="rubriques")
     */
    private $newsletter;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Set title
     *
     * @param string $title
     *
     * @return Rubrique
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Set image
     *
     * @param string $image
     *
     * @return Rubrique
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get icone
     *
     * @return string
     */
    public function getIcone()
    {
        return $this->icone;
    }

    /**
     * Set icone
     *
     * @param string $icone
     *
     * @return Rubrique
     */
    public function setIcone($icone)
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Rubrique
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return Rubrique
     */
    public function addPost(\AppBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AppBundle\Entity\Post $post
     */
    public function removePost(\AppBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Get newsletter
     *
     * @return \AppBundle\Entity\Newsletter
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set newsletter
     *
     * @param \AppBundle\Entity\Newsletter $newsletter
     *
     * @return Rubrique
     */
    public function setNewsletter(\AppBundle\Entity\Newsletter $newsletter = null)
    {
        $this->newsletter = $newsletter;

        return $this;
    }
}
