<?php

namespace App\Entity;

use App\Repository\StoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: StoryRepository::class)]
class Story
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 25)]
    private string $language = 'english';

    #[ORM\Column(length: 25)]
    private string $status = 'pending';

    #[ORM\Column(length: 255, unique: true)]
    #[Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $genre = null;

    #[ORM\OneToMany(mappedBy: 'story', targetEntity: Contribution::class)]
    #[ORM\OrderBy(['createdAt' => 'asc'])]
    private Collection $contributions;

    #[ORM\OneToMany(mappedBy: 'Story', targetEntity: Comment::class)]
    private Collection $comments;
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedStories', fetch: 'EXTRA_LAZY')]
    private Collection $likes;
    #[ORM\Column(nullable: true)]
    private ?bool $IsReported = null;
    #[ORM\Column (nullable: true)]
    private ?string $storyImage;

    public function __construct()
    {
        $this->contributions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getCommentsCount(): int
    {
        return $this->comments->count();
    }

    public function getStoryImage(): ?string
    {
        return $this->storyImage;
    }

    public function setStoryImage(?string $storyImage): self
    {
        $this->storyImage = $storyImage;

        return $this;
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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function addContribution(contribution $contribution): self
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->setStory($this);
        }

        return $this;
    }

    public function removeContribution(contribution $contribution): self
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getStory() === $this) {
                $contribution->setStory(null);
            }
        }

        return $this;
    }

    public function addLike(User $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        }

        return $this;
    }

    public function removeLike(User $like): self
    {
        $this->likes->removeElement($like);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setStory($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getStory() === $this) {
                $comment->setStory(null);
            }
        }

        return $this;
    }

    public function getTotalLikes(): int
    {
        return $this->getLikes()->count();
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function isIsReported(): ?bool
    {
        return $this->IsReported;
    }

    public function setIsReported(?bool $IsReported): self
    {
        $this->IsReported = $IsReported;

        return $this;
    }

    public function getStoryContent(): string
    {
        $contentArray = [];
        foreach ($this->getContributions() as $contribution) {
            $contentArray[] = $contribution->getContent();
        }

        return implode("\n", $contentArray);
    }

    /**
     * @return Collection<int, contribution>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }
}