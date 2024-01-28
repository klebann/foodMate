<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'json')]
    private array $roles;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Fridge $fridge = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ShoppingList::class, orphanRemoval: true)]
    private Collection $shoppingLists;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->shoppingLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFridge(): ?Fridge
    {
        return $this->fridge;
    }

    public function setFridge(Fridge $fridge): self
    {
        // set the owning side of the relation if necessary
        if ($fridge->getUser() !== $this) {
            $fridge->setUser($this);
        }

        $this->fridge = $fridge;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return Collection<int, ShoppingList>
     */
    public function getShoppingLists(): Collection
    {
        return $this->shoppingLists;
    }

    public function addShoppingList(ShoppingList $shoppingList): self
    {
        if (!$this->shoppingLists->contains($shoppingList)) {
            $this->shoppingLists->add($shoppingList);
            $shoppingList->setUser($this);
        }

        return $this;
    }

    public function removeShoppingList(ShoppingList $shoppingList): self
    {
        if ($this->shoppingLists->removeElement($shoppingList)) {
            // set the owning side to null (unless already changed)
            if ($shoppingList->getUser() === $this) {
                $shoppingList->setUser(null);
            }
        }

        return $this;
    }
}
