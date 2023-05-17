<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Content.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $content;

//    /**
//     * Email.
//     */
//    #[ORM\Column(type: 'string', length: 255)]
//    #[Assert\NotBlank]
//    #[Assert\Email]
//    private ?string $email;

    /**
     * Nick.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $nick;

    /**
     * Post.
     */
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    /**
     * Writer.
     *
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $writer;




    /**
     * Getter for id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for content.
     *
     * @return string|null content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Content.
     *
     * @param string|null $content Content
     *
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

//
    /**
     * Getter for nick.
     *
     * @return string|null Nick
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Setter for nick.
     *
     * @param string|null $nick Nick
     *
     * @return $this
     */
    public function setNick(?string $nick): self
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Getter for post.
     *
     * @return Post|null Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * Setter for post.
     *
     * @param Post|null $post Post
     *
     * @return $this
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return User Writer
     */
    public function getWriter(): ?User
    {
        return $this->writer;
    }

    /**
     * @param User $writer Writer
     */
    public function setWriter(?User $writer): void
    {
        $this->writer = $writer;
    }
}


