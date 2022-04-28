<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Panchanga;

/**
 * Panchanga object class.
 *
 * 
 */
class AngaObject
{
    /**
     * Anga type.
     * 
     * @var string
     */
    protected $angaType = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setAngaName();
    }
    
    /**
     * Set name of the anga.
     * 
     * @return void
     */
    protected function setAngaName()
    {
        $angaType = $this->angaType;
        $fileName = ucfirst($angaType);
        
        $angaName = $angaType. 'Name';
        $angaKey = $angaType . 'Key';
        $className = 'Jyotish\\Panchanga\\' . $fileName . '\\' . $fileName;
        
        $list = $className::$$angaType;
        
        $this->$angaName = $list[$this->$angaKey];
    }
}
