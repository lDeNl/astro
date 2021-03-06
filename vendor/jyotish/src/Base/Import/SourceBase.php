<?php
/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Base\Import;

/**
 * Class for importing the data.
 *
 * 
 */
class SourceBase implements ImportInterface
{
    /**
     * Import array block data.
     * 
     * @var array
     */
    protected $importData = null;
    
    /**
     * Get import data.
     * 
     * @return array Array block data
     */
    public function getImportData() {
        return $this->importData;
    }
}
