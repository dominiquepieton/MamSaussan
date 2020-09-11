<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Article
{
   
   
   
   /**
     * permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }
   
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ImgArticle::class, mappedBy="article", cascade={"persist"})
     */
    private $imgArticles;

    public function __construct()
    {
        $this->imgArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ImgArticle[]
     */
    public function getImgArticles(): Collection
    {
        return $this->imgArticles;
    }

    public function addImgArticle(ImgArticle $imgArticle): self
    {
        if (!$this->imgArticles->contains($imgArticle)) {
            $this->imgArticles[] = $imgArticle;
            $imgArticle->setArticle($this);
        }

        return $this;
    }

    public function removeImgArticle(ImgArticle $imgArticle): self
    {
        if ($this->imgArticles->contains($imgArticle)) {
            $this->imgArticles->removeElement($imgArticle);
            // set the owning side to null (unless already changed)
            if ($imgArticle->getArticle() === $this) {
                $imgArticle->setArticle(null);
            }
        }

        return $this;
    }
}
