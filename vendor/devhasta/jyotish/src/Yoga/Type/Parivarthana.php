<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Yoga\Type;

use Jyotish\Yoga\Yoga;
use Jyotish\Graha\Graha;

/**
 * Parivarthana yoga class.
 *
 * 
 */
class Parivarthana extends YogaBase
{
    const GROUP_MAHA = 'maha';
    const GROUP_KHALA = 'khala';
    const GROUP_DAINYA = 'dainya';
    
    /**
     * Type of yogas.
     * 
     * @var string
     */
    protected $yogaType = Yoga::TYPE_PARIVARTHANA;
    
    /**
     * Generate list of Parivarthana yogas.
     * 
     * @return \Iterator
     * @see Mantreswara. Phaladeepika. Chapter 6, Verse 32.
     */
    public function generateYoga()
    {
        $saptaGraha = Graha::listGraha(Graha::LIST_SAPTA);
        
        $grahaChecked = [];
        foreach ($saptaGraha as $key1 => $name1) {
            $grahaChecked[] = $key1;
            
            foreach ($saptaGraha as $key2 => $name2) {
                if (in_array($key2, $grahaChecked)) {
                    continue;
                }
                
                if ($yogaData = $this->hasParivarthana($key1, $key2)) {
                    yield $yogaData;
                }
            }
        }
    }
}
