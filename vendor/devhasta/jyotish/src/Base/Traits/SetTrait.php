<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Base\Traits;

/**
 * SetTrait provides a common implementation of the 'set' interface.
 * 
 * 
 */
trait SetTrait
{
    /**
     * Set object property.
     * 
     * @param string $property
     * @param mixed $value
     * @throws Exception\InvalidArgumentException
     */
    public function __set($property, $value)
    {
        $pos = strpos($property, $this->objectType);
        
        if (!property_exists($this, $property)) {
            throw new \Jyotish\Base\Exception\InvalidArgumentException("Property '$property' does not exist.");
        }
        
        if ($pos === false) {
            $this->$property = $value;
        } else {
            throw new \Jyotish\Base\Exception\InvalidArgumentException("Property '$property' can not be set.");
        }
    }
}
