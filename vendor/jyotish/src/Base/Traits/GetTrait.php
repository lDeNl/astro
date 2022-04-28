<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Base\Traits;

/**
 * GetTrait provides a common implementation of the 'get' interface.
 * 
 * 
 */
trait GetTrait
{
    /**
     * Get object property.
     * 
     * @param string $property
     * @return mixed
     * @throws Exception\InvalidArgumentException
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return($this->$property);
        } else {
            throw new \Jyotish\Base\Exception\InvalidArgumentException("Property '$property' does not exist.");
        }
    }
}
