<?php

namespace App\Entity\Referenciel;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Referenciel\TraitFieldsReferenciel;


/**
 * RefDelegation
 * @ORM\Table(name="referenciel")
 * @ORM\Entity()
 */

class RefDelegation extends Referenciel{
    use TraitFieldsReferenciel;


}
