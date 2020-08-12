<?php

/**
 * Description of Role
 *
 * @author Lamine Mansouri
 * @date 17/06/2020
 */

namespace App\Entity\Config;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Filter\RegexpFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Config\AccessInterface;

/**
 * Role
 *
 * @ORM\Table(name="role", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ApiResource(
 *     collectionOperations={
 *     "get",
 *     "post"
 * },
 *     itemOperations={"get", "put", "patch", "delete"},
 *     normalizationContext={"groups"={"role:read"}},
 *     denormalizationContext={"groups"={"role:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Config\RoleRepository")
 */
class Role {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"role:write","role:read","user:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=45, nullable=false, unique=true)
     * @Groups({"role:write","role:read","user:read" })
     */
    private $role;

    /**
     * @var string
     * @ORM\Column(name="name_ar", type="string", length=255, nullable=false)
     * @Groups({"role:write","role:read","user:read" })
     * @Assert\NotBlank(message="Le champ role est obligatoire")
     */
    private $nameAr;

    /**
     * @var string
     * @ORM\Column(name="name_fr", type="string", length=255, nullable=false)
     * @Groups({"role:write","role:read","user:read" })
     * @Assert\NotBlank(message="Le champ role est obligatoire")
     */
    private $nameFr;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="role")
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Config\AccessInterface", inversedBy="roles" , cascade={"persist", "remove"})
     * @ORM\JoinTable(name="role_access_interface",
     *   joinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="access_interface_id", referencedColumnName="id")
     *   }
     * )
     * @Groups({"role:read","abstract:read","role:write"})
     */
    public $accessInterfaces;

    public function __construct() {
        $this->users            = new ArrayCollection();
        $this->roleInterfaces   = new ArrayCollection();
        $this->accessInterfaces = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(string $role): self {
        $this->role = $role;

        return $this;
    }

    public function getNameAr(): ?string {
        return $this->nameAr;
    }

    public function setNameAr(string $nameAr): self {
        $this->nameAr = $nameAr;

        return $this;
    }

    public function getNameFr(): ?string {
        return $this->nameFr;
    }

    public function setNameFr(string $nameFr): self {
        $this->nameFr = $nameFr;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection {
        return $this->users;
    }

    public function addUser(User $user): self {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addRole($this);
        }
        return $this;
    }

    public function removeUser(User $user): self {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRole($this);
        }
        return $this;
    }

    /**
     * @return Collection|AccessInterface[]
     */
    public function getAccessInterfaces(): Collection {
        return $this->accessInterfaces;
    }

    public function addAccessInterface(AccessInterface $accessInterface): self {
        if (!$this->accessInterfaces->contains($accessInterface)) {
            $this->accessInterfaces[] = $accessInterface;
        }

        return $this;
    }

    public function removeAccessInterface(AccessInterface $accessInterface): self {
        if ($this->accessInterfaces->contains($accessInterface)) {
            $this->accessInterfaces->removeElement($accessInterface);
        }

        return $this;
    }

}
