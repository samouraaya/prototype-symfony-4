<?php

namespace App\Entity\Referenciel;

use App\Entity\Config\AbstractEntity;
use App\Repository\Referenciel\ReferencielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReferencielRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="categories", type="string")
 * @ORM\DiscriminatorMap({
 *     "RefGouvernorat" = "RefGouvernorat",
 *     "RefDelegation" = "RefDelegation",
 *     "Referenciel" = "Referenciel",
 * })
 *  @ApiResource()
 */

class Referenciel
{
    use AbstractEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"referenciel:write","referenciel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referenciel:write","referenciel:read"})
     */
    private $intituleFr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referenciel:write","referenciel:read"})
     */
    private $intituleAr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referenciel:write","referenciel:read"})
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="Referenciel", mappedBy="parent")
     * @Groups({"referenciel:write","referenciel:read"})
     * @ORM\OrderBy({"intituleFr" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Referenciel", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * @Groups({"referenciel:write","referenciel:read"})
     */
    protected $parent;

    /**
     * @var boolean
     * @ORM\Column(name="enable", type="boolean",nullable=true)
     * @Groups({"referenciel:write","referenciel:read"})
     */
    protected $enable;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntituleFr(): ?string
    {
        return $this->intituleFr;
    }

    public function setIntituleFr(string $intituleFr): self
    {
        $this->intituleFr = $intituleFr;

        return $this;
    }

    public function getIntituleAr(): ?string
    {
        return $this->intituleAr;
    }

    public function setIntituleAr(string $intituleAr): self
    {
        $this->intituleAr = $intituleAr;

        return $this;
    }
    /**
     * Method to retrieve the possible categories for a reference
     * @return ArrayCollection
     */
    public static function getReferencielCategories() {
        $categorie = new ArrayCollection();
        $categorie->add("RefGouvernorat");
        $categorie->add("RefDelegation");
        return $categorie;
    }

    /**
     * Method to check if the categorie is valid
     * @return bool
     */
    public static function checkIfValidCategorie($categorie) {
        $categories = Referenciel::getReferencielCategories();
        return $categories->contains($categorie);
    }


    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(?bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return Collection|Referenciel[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Referenciel $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Referenciel $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


    public function setCategories(string $categories): self
    {
        $this->categories = $categories;

        return $this;
    }


}
