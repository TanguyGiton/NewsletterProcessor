<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Newsletter
 *
 * @ORM\Table(name="newsletter")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsletterRepository")
 */
class Newsletter
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
     * @ORM\Column(name="week", type="string", length=255)
     */
    private $week;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="footer", type="text")
     */
    private $footer;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rubrique", mappedBy="newsletter")
     */
    private $rubriques;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rubriques = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Newsletter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get week
     *
     * @return string
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set week
     *
     * @param string $week
     *
     * @return Newsletter
     */
    public function setWeek($week)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Newsletter
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get footer
     *
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set footer
     *
     * @param string $footer
     *
     * @return Newsletter
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Add rubrique
     *
     * @param \AppBundle\Entity\Rubrique $rubrique
     *
     * @return Newsletter
     */
    public function addRubrique(\AppBundle\Entity\Rubrique $rubrique)
    {
        $this->rubriques[] = $rubrique;

        return $this;
    }

    /**
     * Remove rubrique
     *
     * @param \AppBundle\Entity\Rubrique $rubrique
     */
    public function removeRubrique(\AppBundle\Entity\Rubrique $rubrique)
    {
        $this->rubriques->removeElement($rubrique);
    }

    /**
     * Get rubriques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRubriques()
    {
        return $this->rubriques;
    }
}
