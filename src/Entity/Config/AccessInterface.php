<?php

/**
 * Description of AccessInterface
 *
 * @author Lamine Mansouri
 * @date 22/06/2020
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
use ApiPlatform\Core\Annotation\ApiProperty;

/**
 * AccessInterface
 *
 * @ORM\Table(name="access_interface", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Config\AccessInterfaceRepository")
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={"get"}
 * )
 */
class AccessInterface {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"role:read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="back_interface", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le champ interface est obligatoire")
     */
    private $backInterface;

    /**
     * @var string
     * @ORM\Column(name="front_interface", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le champ interface est obligatoire")
     */
    private $frontInterface;

    /**
     * @var string
     * @ORM\Column(name="method", type="array", nullable=false)
     * @Assert\NotBlank(message="Le champ interface est obligatoire")
     */
    private $method;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="accessInterfaces")
     * @ApiSubresource(maxDepth=1)
     */
    private $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getBackInterface(): ?string {
        return $this->backInterface;
    }

    public function setBackInterface(string $backInterface): self {
        $this->backInterface = $backInterface;

        return $this;
    }

    public function getFrontInterface(): ?string {
        return $this->frontInterface;
    }

    public function setFrontInterface(?string $frontInterface): self {
        $this->frontInterface = $frontInterface;

        return $this;
    }

    public function getMethod(): ?array {
        return $this->method;
    }

    public function setMethod(array $method): self {
        $this->method = $method;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection {
        return $this->roles;
    }

    public function addRole(Role $role): self {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addRoleInterface($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeRoleInterface($this);
        }

        return $this;
    }

}
