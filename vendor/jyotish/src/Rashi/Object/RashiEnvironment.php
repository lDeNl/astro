<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Rashi\Object;

/**
 * Bhava environment trait.
 *
 * 
 */
trait RashiEnvironment
{
    use \Jyotish\Base\Traits\EnvironmentTrait;
    
    /**
     * Get bhava, where rashi is positioned.
     * 
     * @return int
     */
    public function getBhava()
    {
        $bhava = 0;
        do {
            $bhava++;
            $rashi = $this->getEnvironment()['bhava'][$bhava]['rashi'];
        } while ($rashi <> $this->objectKey);
        
        return $bhava;
    }
}
