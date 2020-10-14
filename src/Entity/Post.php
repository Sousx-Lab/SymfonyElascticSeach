<?php

namespace App\Entity;

use IntlDateFormatter;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @UniqueEntity("title")
 */
class Post
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $author;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;
    
    /**
     * @var string
     */
    private string $slug;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getContentPreview(int $limit = 60)
    {
        if($this->content === null){
            return null;
        }
        if(mb_strlen($this->content) <= $limit){
            return $this->content;
        }
        $lastSpace = mb_strpos($this->content, ' ', $limit);
        return (mb_substr($this->content, 0, $lastSpace)) . '...';
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Formate date in different language 
     * @return string|null
     */
    public function getFormattedDate(string $local = 'fr_FR'): ?string
    {  
        $formatter = new IntlDateFormatter($local,
        IntlDateFormatter::MEDIUM,
        IntlDateFormatter::NONE
        );
        return $formatter->format($this->getCreatedAt());
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        $this->slug = (new Slugify())->slugify($this->title);
        return $this->slug;
    }
}
