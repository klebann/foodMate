<?php

namespace App\Entity;

use App\Repository\FridgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FridgeRepository::class)]
class Fridge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'fridge', targetEntity: FridgeProduct::class)]
    private Collection $products;

    #[ORM\OneToOne(inversedBy: 'fridge', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, FridgeProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(FridgeProduct $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setFridge($this);
        }

        return $this;
    }

    public function removeProduct(FridgeProduct $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getFridge() === $this) {
                $product->setFridge(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
