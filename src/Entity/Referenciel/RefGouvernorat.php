<?php

namespace App\Entity\Referenciel;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Referenciel\TraitFieldsReferenciel;

/**
 * RefGouvernorat
 * @ORM\MappedSuperclass
 * @ORM\Table(name="referenciel")
 * @ORM\Entity()
 */
class RefGouvernorat extends Referenciel
{
    use TraitFieldsReferenciel;
    
}
