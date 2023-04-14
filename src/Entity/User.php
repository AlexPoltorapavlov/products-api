<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(paginationItemsPerPage: 5),
        new Put(),
        new Patch(),
//        new Delete(),
        new Post()
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]    
)]
#[ApiFilter(SearchFilter::class, properties: ['createdAt' => 'ipartial', 'id' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt'], arguments: ['orderParameterName' => 'order'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[Assert\Email()]
    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: "json")]
    #[Groups(['read', 'write'])]
    private $roles = [];

    #[Assert\NotBlank()]
    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('read')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: "string")]
    private $password = '4uvirlo';

    // #[ORM\ManyToMany(targetEntity: "UserFavorite", mappedBy: "users")]
    // private $userFavorites;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ApiToken::class, orphanRemoval: true)]
    private Collection $apiTokens;

    #[ORM\ManyToMany(targetEntity: UserFavorite::class, mappedBy: 'users')]
    private Collection $userFavorites;

    #[ORM\OneToMany(mappedBy: 'uploadedBy', targetEntity: Image::class)]
    #[Groups(['read', 'write'])]
    private Collection $uploadedImages;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable("now");
        // $this->userFavorites = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
        $this->uploadedImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens->add($apiToken);
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, UserFavorite>
    //  */
    // public function getUserFavorites(): Collection
    // {
    //     return $this->userFavorites;
    // }

    // public function addUserFavorite(UserFavorite $userFavorite): self
    // {
    //     if (!$this->userFavorites->contains($userFavorite)) {
    //         $this->userFavorites->add($userFavorite);
    //         $userFavorite->addUser($this);
    //     }

    //     return $this;
    // }

    // public function removeUserFavorite(UserFavorite $userFavorite): self
    // {
    //     if ($this->userFavorites->removeElement($userFavorite)) {
    //         $userFavorite->removeUser($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, UserFavorite>
     */
    public function getUserFavorites(): Collection
    {
        return $this->userFavorites;
    }

    public function addUserFavorite(UserFavorite $userFavorite): self
    {
        if (!$this->userFavorites->contains($userFavorite)) {
            $this->userFavorites->add($userFavorite);
            $userFavorite->addUser($this);
        }

        return $this;
    }

    public function removeUserFavorite(UserFavorite $userFavorite): self
    {
        if ($this->userFavorites->removeElement($userFavorite)) {
            $userFavorite->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getUploadedImages(): Collection
    {
        return $this->uploadedImages;
    }

    public function addUploadedImage(Image $uploadedImage): self
    {
        if (!$this->uploadedImages->contains($uploadedImage)) {
            $this->uploadedImages->add($uploadedImage);
            $uploadedImage->setUploadedBy($this);
        }

        return $this;
    }

    public function removeUploadedImage(Image $uploadedImage): self
    {
        if ($this->uploadedImages->removeElement($uploadedImage)) {
            // set the owning side to null (unless already changed)
            if ($uploadedImage->getUploadedBy() === $this) {
                $uploadedImage->setUploadedBy(null);
            }
        }

        return $this;
    }

}
