<?php

namespace App\Entity\Referenciel;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraitFieldsReferenciel
 */

/**
 * @ORM\Discriminator(field = "code")
 */
trait TraitFieldsReferenciel
{
    /**
     * @ORM\Column(name="code", type="text",nullable=true)
     */
    private $code;
    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return code
     */

    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }


}