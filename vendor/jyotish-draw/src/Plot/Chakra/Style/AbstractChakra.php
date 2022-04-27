<?php
/**
 * @link      http://github.com/kunjara/jyotish for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Plot\Chakra\Style;

use Jyotish\Bhava\Bhava;
use Jyotish\Base\Analysis;
use Jyotish\Ganita\Matrix;

/**
 * Class for generate Chakra.
 *
 * @author Kunjara Lila das <vladya108@gmail.com>
 */
abstract class AbstractChakra
{
    use \Jyotish\Base\Traits\DataTrait;
    
    /**
     * Triangle bhava
     */
    const BHAVA_TRIANGLE = 'triangle';
    /**
     * Rectangle bhava
     */
    const BHAVA_RECTANGLE = 'rectangle';
    
    /**
     * North Indian style
     */
    const STYLE_NORTH = 'north';
    /**
     * South Indian style
     */
    const STYLE_SOUTH = 'south';
    /**
     * Eastern Indian Style
     */
    const STYLE_EAST = 'east';
    
    /**
     * Fixed bhava chakra
     */
    const FIX_BHAVA = 'bhava';
    /**
     * Fixed rashi chakra
     */
    const FIX_RASHI = 'rashi';
    
    const COUNT_ONE = 'one';
    const COUNT_FOUR = 'four';
    const COUNT_FIVE = 'five';
    const COUNT_MORE = 'more';

    /**
     * List of styles.
     * 
     * @var array
     */
    public static $style = [
        self::STYLE_NORTH,
        self::STYLE_SOUTH,
        self::STYLE_EAST,
    ];
    
    /**
     * Analysis object.
     * 
     * @var \Jyotish\Base\Analysis
     */
    protected $Analysis = null;
    
    /**
     * Chakra graha.
     * 
     * @var string
     */
    protected $chakraGraha;
    
    /**
     * Chakra divider.
     * 
     * @var int
     */
    protected $chakraDivider;
    
    /**
     * Fixed type of chakra.
     * 
     * @var string
     */
    protected $chakraFix;

    /**
     * Base coordinates of bhavas.
     * 
     * @var array
     */
    protected $bhavaPointsBase = [];
    
    /**
     * Base coordinates of grahas.
     * 
     * @var array
     */
    protected $grahaPointsBase = [];

    /**
     * Rules of transformations.
     * 
     * @var array
     */
    protected $transformRules = [];
    
    /**
     * Bhava coordinates after transformations.
     * 
     * @var array
     */
    protected $bhavaPoints = [];

    /**
     * Constructor
     * 
     * @param \Jyotish\Base\Data $Data
     */
    public function __construct(\Jyotish\Base\Data $Data)
    {
        $this->setDataInstance($Data);
        
        $this->Analysis = new Analysis($Data);
    }

    /**
     * Get bhava points.
     * 
     * @param int $leftOffset Left offset
     * @param int $topOffset Top offset
     * @param array $options
     * @return array
     */
    public function getBhavaPoints($leftOffset = 0, $topOffset = 0, array $options = null)
    {
        $myPoints = [];
        foreach ($this->transformRules as $bhavaKey => $transformInfo) {
            $bhavaPoints = $this->bhavaPointsBase[$transformInfo['base']];
            foreach ($bhavaPoints as $point => $value) {
                if ($point % 2) {
                    $y = $value;
                    $factor = round($options['chakraSize'] / $this->chakraDivider);
                    $transformInfo['transform'][Matrix::TYPE_SCALING] = [$factor, $factor];
                    $matrixCoord = Matrix::getInstance(Matrix::TYPE_DEFAULT, [[$x, $y, 1]]);
                    foreach ($transformInfo['transform'] as $transform => $params) {
                        $matrixTransform = Matrix::getInstance($transform, ...$params);
                        $matrixCoord->multiMatrix($matrixTransform);
                    }
                    $arrayCoord = $matrixCoord->toArray();
                    list($x, $y) = $arrayCoord[0];
                    
                    $myPoints[$bhavaKey][] = $x + $leftOffset;
                    $myPoints[$bhavaKey][] = $y + $topOffset;
                } else {
                    $x = $value;
                }
            }
        }
        $this->bhavaPoints = $myPoints;
        
        return $myPoints;
    }
    
    /**
     * Get body label points.
     * 
     * @param int $leftOffset Left offset
     * @param int $topOffset Top offset
     * @param array $options
     * @return array
     */
    public function getBodyLabelPoints($leftOffset = 0, $topOffset = 0, array $options = null)
    {
        $grahaDisposition = $this->getGrahaDisposition($options['chakraVarga']);
        $myPoints = [];
        
        foreach ($grahaDisposition as $disposition => $grahas) {
            $bhavaType = $this->getBhavaType($disposition);
            $countKey = $this->getCountKey(count($grahas), $bhavaType);
            $i = 0;
            foreach ($grahas as $key => $graha) {
                $x = $this->grahaPointsBase[$bhavaType][$countKey][$i * 2];
                $y = $this->grahaPointsBase[$bhavaType][$countKey][$i * 2 + 1];
                $factor = round($options['chakraSize'] / $this->chakraDivider);
                $transformInfo = $this->transformRules[$disposition];
                $transformInfo['transform'][Matrix::TYPE_SCALING] = [$factor, $factor];
                $matrixCoord = Matrix::getInstance(Matrix::TYPE_DEFAULT, [[$x, $y, 1]]);
                
                foreach ($transformInfo['transform'] as $transform => $params) {
                    $matrixTransform = Matrix::getInstance($transform, ...$params);
                    $matrixCoord->multiMatrix($matrixTransform);
                }
                
                $arrayCoord = $matrixCoord->toArray();
                list($x, $y) = $arrayCoord[0];
                
                $myPoints[$graha]['x'] = $x + $leftOffset;
                $myPoints[$graha]['y'] = $y + $topOffset;
                $myPoints[$graha]['textAlign'] = 'center';
                $myPoints[$graha]['textValign'] = 'middle';
                $i += 1;
            }
        }
        return $myPoints;
    }
    /**
     * Get graha dispositions.
     * 
     * @param string $chakraVarga
     * @return array
     */
    private function getGrahaDisposition($chakraVarga)
    {
        if ($this->chakraFix == self::FIX_RASHI) {
            $bodies = $this->Analysis->getBodyInRashi($chakraVarga);
        } else {
            $bodies = $this->Analysis->getBodyInBhava($chakraVarga);
        }
        
        $grahaDisposition = [];
        foreach ($bodies as $graha => $disposition) {
            $grahaDisposition[$disposition][] = $graha;
        }
        
        return $grahaDisposition;
    }

    /**
     * Get count key.
     * 
     * @param int $grahaCount
     * @param string $bhavaType
     * @return string
     */
    private function getCountKey($grahaCount, $bhavaType)
    {
        if ($bhavaType == self::BHAVA_TRIANGLE) {
            if ($grahaCount == 1) {
                $countKey = self::COUNT_ONE;
            } elseif ($grahaCount > 1 && $grahaCount <= 4) {
                $countKey = self::COUNT_FOUR;
            } else {
                $countKey = self::COUNT_MORE;
            }
        } else {
            if ($grahaCount == 1) {
                $countKey = self::COUNT_ONE;
            } elseif ($grahaCount > 1 && $grahaCount <= 5) {
                $countKey = self::COUNT_FIVE;
            } else {
                $countKey = self::COUNT_MORE;
            }
        }
        
        return $countKey;
    }
    
    /**
     * Get type of bhava (rectangle or triangle).
     * 
     * @param int $disposition
     * @return string
     */
    private function getBhavaType($disposition)
    {
        if (count($this->bhavaPointsBase) > 1) {
            return (in_array($disposition, Bhava::$bhavaKendra)) ? self::BHAVA_RECTANGLE : self::BHAVA_TRIANGLE;
        } else {
            return self::BHAVA_RECTANGLE;
        }
    }

    /**
     * Get rashi label points.
     * 
     * @param array $options
     */
    abstract public function getRashiLabelPoints(array $options);
}