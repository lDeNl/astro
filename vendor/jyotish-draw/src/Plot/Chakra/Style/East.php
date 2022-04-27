<?php
/**
 * @link      http://github.com/kunjara/jyotish for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Plot\Chakra\Style;

use Jyotish\Graha\Graha;
use Jyotish\Ganita\Matrix;

/**
 * Class for generate East chakra.
 *
 * @author Kunjara Lila das <vladya108@gmail.com>
 */
final class East extends AbstractChakra
{
    /**
     * Chakra graha.
     * 
     * @var string
     */
    protected $chakraGraha = Graha::KEY_SY;
    
    /**
     * Chakra divider.
     * 
     * @var int
     */
    protected $chakraDivider = 3;
    
    /**
     * Fixed type of chakra.
     * 
     * @var string
     */
    protected $chakraFix = self::FIX_RASHI;
    
    /**
     * Base coordinates of bhavas.
     * 
     * @var array
     */
    protected $bhavaPointsBase = [
        self::BHAVA_TRIANGLE => [0, 0,   1, 0,   1, 1],
        self::BHAVA_RECTANGLE => [0, 0,   1, 0,   1, 1,   0, 1],
    ];
    
    /**
     * Base coordinates of grahas.
     * 
     * @var array
     */
    protected $grahaPointsBase = [
        self::BHAVA_TRIANGLE => [
            self::COUNT_ONE  => [2/3, 1/3],
            self::COUNT_FOUR => [0.375, 0.125,   0.625, 0.375,   0.875, 0.650,   0.825, 0.175],
            self::COUNT_MORE => [
                0.310, 0.120,
                0.490, 0.300,
                0.670, 0.480,
                0.850, 0.660,
                0.510, 0.120,
                0.690, 0.300,
                0.870, 0.480,
                0.710, 0.120,
                0.890, 0.300,
            ]
        ],
        self::BHAVA_RECTANGLE => [
            self::COUNT_ONE  => [0.5, 0.5],
            self::COUNT_FIVE => [0.25, 0.25,   0.75, 0.25,   0.25, 0.75,   0.75, 0.75,   0.5, 0.5],
            self::COUNT_MORE => [
                0.2, 0.2,
                0.5, 0.2,
                0.8, 0.2,
                0.2, 0.8,
                0.5, 0.8,
                0.8, 0.8,
                0.2, 0.5,
                0.8, 0.5,
                0.5, 0.5,
            ]
        ],
    ];
    
    /**
     * Rules of transformations.
     * 
     * @var array
     */
    protected $transformRules = [
        1 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [1, 0],
            ],
        ],
        2 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [],
        ],
        3 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [true, false],
                Matrix::TYPE_ROTATION => [M_PI_2],
            ],
        ],
        4 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [0, 1],
            ],
        ],
        5 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [false, true],
                Matrix::TYPE_TRANSLATION => [1, 2],
            ],
        ],
        6 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [true, false],
                Matrix::TYPE_TRANSLATION => [0, 3],
            ],
        ],
        7 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [1, 2],
            ],
        ],
        8 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [true, false],
                Matrix::TYPE_ROTATION => [M_PI_2],
                Matrix::TYPE_TRANSLATION => [2, 2],
            ],
        ],
        9 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [2, 2],
            ],
        ],
        10 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [2, 1],
            ],
        ],
        11 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [M_PI_2],
                Matrix::TYPE_TRANSLATION => [3, 0],
            ],
        ],
        12 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [false, true],
                Matrix::TYPE_TRANSLATION => [3, 0],
            ],
        ],
    ];

    /**
     * Get rashi label points.
     * 
     * @param array $options
     * @return array
     */
    public function getRashiLabelPoints(array $options)
    {
        $offsetBorder = $options['offsetBorder'];
        $offsetCorner3 = $offsetBorder * 3;
        $offsetCorner4 = $offsetBorder * 4;
        $rashis = $this->Analysis->getRashiInBhava($options['chakraVarga']);

        $myPoints = [];
        foreach ($rashis as $rashi => $bhava) {
            $bhava = $rashi;

            if ($bhava == 1) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][6] + $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][7] - $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 2) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] - $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] - $offsetCorner3;
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 3) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] - $offsetCorner4;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] - $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 4) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] - $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] - $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 5) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] - $offsetCorner4;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] + $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'top';
            } elseif ($bhava == 6) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] - $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] + $offsetCorner3;
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'top';
            } elseif ($bhava == 7) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][2] - $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][3] + $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'top';
            } elseif ($bhava == 8) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] + $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] + $offsetCorner3;
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'top';
            } elseif ($bhava == 9) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] + $offsetCorner4;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] + $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'top';
            } elseif ($bhava == 10) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] + $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] + $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'top';
            } elseif ($bhava == 11) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] + $offsetCorner4;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] - $offsetBorder;
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } else {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] + $offsetBorder;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] - $offsetCorner3;
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'bottom';
            }
        }
        return $myPoints;
    }
}