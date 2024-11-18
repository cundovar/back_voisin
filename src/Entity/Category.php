<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Objet>
     */
    #[ORM\OneToMany(targetEntity: Objet::class, mappedBy: 'category')]
    private Collection $objet;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['objet:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->objet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Objet>
     */
    public function getObjet(): Collection
    {
        return $this->objet;
    }

    public function addObjet(Objet $objet): static
    {
        if (!$this->objet->contains($objet)) {
            $this->objet->add($objet);
            $objet->setCategory($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): static
    {
        if ($this->objet->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getCategory() === $this) {
                $objet->setCategory(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
