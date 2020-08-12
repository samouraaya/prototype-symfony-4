<?php

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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}}
 *     },
 *     collectionOperations={
 *          "post",
 *          "user_post"={
 *                 "route_name"="user_register",
 *                 "denormalization_context"={"groups"={"user:write"}},
 *                 "method"="POST",
 *                 "responses" = {
 *                          "201" = {"description" = "The count of changes will be in response."},
 *                          "400" = {"description" = "Returned when invalid data posted."},
 *                          "401" = {"description" = "Returned when not authenticated."},
 *                          "403" = {"description" = "Returned when token not valid or expired."},
 *                  }
 *           },
 *          "user_get"={
 *                 "route_name"="user_all",
 *                 "denormalization_context"={"groups"={"user:read"}},
 *                 "method"="GET",
 *                 "responses" = {
 *                          "200" = {"description" = "The count of changes will be in response."},
 *                          "400" = {"description" = "Returned when invalid data posted."},
 *                          "401" = {"description" = "Returned when not authenticated."},
 *                          "403" = {"description" = "Returned when token not valid or expired."},
 *                  }
 *           },
 *          "user_get_pagination"={
 *                 "route_name"="user_all_pagination",
 *                 "denormalization_context"={"groups"={"user:read"}},
 *                 "method"="GET",
 *                 "responses" = {
 *                          "200" = {"description" = "The count of changes will be in response."},
 *                          "400" = {"description" = "Returned when invalid data posted."},
 *                          "401" = {"description" = "Returned when not authenticated."},
 *                          "403" = {"description" = "Returned when token not valid or expired."},
 *                  }
 *           },
 *     },
 *     itemOperations={
 *           "get",
 *           "put",
 *           "patch",
 *          "user_patch"={
 *                 "route_name"="user_edit_patch",
 *                 "denormalization_context"={"groups"={"user:write"}},
 *                 "method"="PATCH",
 *                 "responses" = {
 *                          "201" = {"description" = "The count of changes will be in response."},
 *                          "400" = {"description" = "Returned when invalid data posted."},
 *                          "401" = {"description" = "Returned when not authenticated."},
 *                          "403" = {"description" = "Returned when token not valid or expired."},
 *                  }
 *           },
 *          "user_put"={
 *                 "route_name"="user_edit_put",
 *                 "denormalization_context"={"groups"={"user:write"}},
 *                 "method"="PUT",
 *                 "responses" = {
 *                          "201" = {"description" = "The count of changes will be in response."},
 *                          "400" = {"description" = "Returned when invalid data posted."},
 *                          "401" = {"description" = "Returned when not authenticated."},
 *                          "403" = {"description" = "Returned when token not valid or expired."},
 *                  }
 *           },
 *           "delete"
 *     },
 *     normalizationContext={"groups"={"user:read","abstract:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Config\UserRepository")
 */
class User implements UserInterface {

    use AbstractEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user:write","user:read","abstract:read"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user:write","user:read","abstract:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=500)
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Groups({"user:read","abstract:read"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users",  cascade={"persist"})
     * @ORM\JoinTable(name="user_role",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @Groups({"user:read","abstract:read","user:write"})
     */
    private $role;

    public function __construct() {
        $this->isActive = true;
        $this->role     = new ArrayCollection();
        $this->studies  = new ArrayCollection();
    }

    public function getUsername() {
        return $this->username;
    }

    public function getSalt() {
        return null;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
        $this->password      = null;
    }

    public function eraseCredentials() {
        
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRoles(): array {
        $roles = $this->roles;
        return $roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRole(): Collection {
        return $this->role;
    }

    public function addRole(Role $role): self {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self {
        if ($this->role->contains($role)) {
            $this->role->removeElement($role);
        }

        return $this;
    }

}
