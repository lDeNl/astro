<?php
/**
 * @link      http://github.com/kunjara/jyotish for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Plot\Chakra\Style;

use Jyotish\Graha\Graha;
use Jyotish\Ganita\Matrix;

/**
 * Class for generate North chakra.
 *
 * @author Kunjara Lila das <vladya108@gmail.com>
 */
final class North extends AbstractChakra
{
    /**
     * Chakra graha.
     * 
     * @var string
     */
    protected $chakraGraha = Graha::KEY_SK;
    
    /**
     * Chakra divider.
     * 
     * @var int
     */
    protected $chakraDivider = 4;
    
    /**
     * Fixed type of chakra.
     * 
     * @var string
     */
    protected $chakraFix = self::FIX_BHAVA;

    /**
     * Base coordinates of bhavas.
     * 
     * @var array
     */
    protected $bhavaPointsBase = [
        self::BHAVA_TRIANGLE => [1, 1,   0, 0,   2, 0],
        self::BHAVA_RECTANGLE => [0, 2,  -1, 1,  0, 0,   1, 1],
    ];
    
    /**
     * Base coordinates of grahas.
     * 
     * @var array
     */
    protected $grahaPointsBase = [
        self::BHAVA_TRIANGLE => [
            self::COUNT_ONE  => [1, 1/3],
            self::COUNT_FOUR => [1, 0.5,   0.5, 1/6,   1, 1/6,   1.5, 1/6],
            self::COUNT_MORE => [
                0.375, 0.125,
                0.8, 0.125,
                1.2, 0.125,
                1.625, 0.125,
                0.6, 0.375,
                1.0, 0.375,
                1.4, 0.375,
                0.8, 0.625,
                1.2, 0.625,
            ]
        ],
        self::BHAVA_RECTANGLE => [
            self::COUNT_ONE  => [0, 1],
            self::COUNT_FIVE => [0, 1.5,   -0.5, 1,   0, 0.5,   0.5, 1,   0, 1],
            self::COUNT_MORE => [
                -0.25, 0.5,
                0.25, 0.5,
                -0.5, 5/6,
                0, 5/6,
                0.5, 5/6,
                -0.5, 7/6,
                0, 7/6,
                0.5, 7/6,
                -0.25, 1.5,
                0.25, 1.5,
            ]
        ],
    ];

    /**
     * Rules of transformations.
     * 
     * @var array
     */
    protected $transformRules = [
        1  => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [2, 0],
            ],
        ],
        2 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [],
        ],
        3 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [M_PI_2],
                Matrix::TYPE_REFLECTION => [false, true],
            ]
        ],
        4 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [-M_PI_2],
                Matrix::TYPE_TRANSLATION => [0, 2],
            ]
        ],
        5 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [-M_PI_2],
                Matrix::TYPE_TRANSLATION => [0, 4],
            ]
        ],
        6 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [true, false],
                Matrix::TYPE_TRANSLATION => [0, 4],
            ]
        ],
        7 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [M_PI],
                Matrix::TYPE_TRANSLATION => [2, 4],
            ]
        ],
        8 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_REFLECTION => [true, false],
                Matrix::TYPE_TRANSLATION => [2, 4],
            ]
        ],
        9 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [M_PI_2],
                Matrix::TYPE_TRANSLATION => [4, 2],
            ]
        ],
        10 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [M_PI_2],
                Matrix::TYPE_TRANSLATION => [4, 2],
            ]
        ],
        11 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_ROTATION => [M_PI_2],
                Matrix::TYPE_TRANSLATION => [4, 0],
            ]
        ],
        12 => [
            'base' => self::BHAVA_TRIANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [2, 0],
            ]
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
        $rashis = $this->Analysis->getRashiInBhava($options['chakraVarga']);
        $offsetCorner = sqrt($options['offsetBorder'] ** 2 * 2);

        $myPoints = [];
        foreach ($rashis as $rashi => $bhava) {
            if ($bhava == 1 || $bhava == 2 || $bhava == 12) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0];
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] - $offsetCorner;
                $myPoints[$rashi]['textAlign'] = 'center';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 3 || $bhava == 4 || $bhava == 5) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] - $offsetCorner;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1];
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'middle';
            } elseif ($bhava == 6 || $bhava == 7 || $bhava == 8) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0];
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] + $offsetCorner;
                $myPoints[$rashi]['textAlign'] = 'center';
                $myPoints[$rashi]['textValign'] = 'top';
            } else {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] + $offsetCorner;
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1];
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'middle';
            }
        }
        return $myPoints;
    }
}