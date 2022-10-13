<?php

/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Plot\Chakra;

use Jyotish\Base\Traits\EnvironmentTrait;
use Jyotish\Graha\Graha;
use Jyotish\Graha\Object\GrahaEnvironment;
use Jyotish\Rashi\Rashi;
use Jyotish\Varga\Varga;
use Jyotish\Base\Utility;
use Jyotish\Draw\Plot\Chakra\Style\AbstractChakra as Chakra;
use Jyotish\Base\Analysis;
use Jyotish\Graha\Object\GrahaObject;

/**
 * Class for rendering Chakra.
 * 
 * 
 */
class Renderer
{
    use \Jyotish\Base\Traits\DataTrait;
    use \Jyotish\Base\Traits\OptionTrait;
//    use EnvironmentTrait;
    use GrahaEnvironment;
    /**
     * Renderer object.
     * 
     * @var Image|Svg
     */
    protected $Renderer = null;
    
    /**
     * Chakra object.
     * 
     * @var North|South|East
     */
    protected $Chakra = null;

    protected $optionChakraSize = 200;
    protected $optionChakraStyle = Chakra::STYLE_NORTH;
    protected $optionChakraVarga = Varga::KEY_D1;
    
    protected $optionOffsetBorder = 4;
    
    protected $optionLabelGrahaType = 0;
    protected $optionLabelGrahaCallback = '';
    
    protected $optionLabelRashiShow = false;
    protected $optionLabelRashiFont = '';
    protected $optionLabelGrahaFont = '';
    protected $optionLabelExtraFont = '';

    /**
     * Constructor
     * 
     * @param Image|Svg $Renderer
     */
    public function __construct($Renderer)
    {
        $this->Renderer = $Renderer;
    }
    
    /**
     * Draw chakra.
     * 
     * @param \Jyotish\Base\Data $Data
     * @param int $x
     * @param int $y
     * @param null|array $options Options to set (optional)
     */
    public function drawChakra(\Jyotish\Base\Data $Data, $x, $y, array $options = null)
    {
        $this->setDataInstance($Data);
        $this->setOptions($options);
        
        $chakraStyle = 'Jyotish\Draw\Plot\Chakra\Style\\' . ucfirst($this->optionChakraStyle);
        $this->Chakra = new $chakraStyle($Data);

        $this->drawBhavas($x, $y);
        
        if ($this->optionChakraStyle == Chakra::STYLE_NORTH || $this->optionLabelRashiShow) {
            $this->drawRashiLabel();
        }
        
        $this->drawBodyLabel($x, $y);
    }
    
    /**
     * Draw bhavas in chakra.
     * 
     * @param int $x
     * @param int $y
     */
    private function drawBhavas($x, $y)
    {
        $bhavaPoints = $this->Chakra->getBhavaPoints($x, $y, $this->getOptions());
        $Data = $this->Data;


        foreach ($bhavaPoints as $number => $points) {
            if ($this->optionChakraStyle == Chakra::STYLE_NORTH) {
                $bhava = ' bhava' . $number;
                $rashi = ' rashi' . $Data->getData()['bhava'][$number]['rashi'];
            } else {
                $rashi = ' rashi' . $number;
                $Rashi = Rashi::getInstance($number);
                $Rashi->setEnvironment($Data);
                $bhava = ' bhava' . $Rashi->getBhava();
            }
            
            $attributes = [
                'class' => 'bhava' . $bhava . $rashi,
            ];
            
            $options = array_merge($this->getOptions(), ['attributes' => $attributes]);
            $this->Renderer->drawPolygon($points, $options);
        }
    }

    /**
     * Draw rashi labels.
     */
    private function drawRashiLabel()
    {
        $Data = $this->Data;
        $options = $this->getOptions();
        
        if (isset($options['labelRashiFont'])) {
            $this->Renderer->setOptions($options['labelRashiFont']);
        }


        $rashiLabelPoints = $this->Chakra->getRashiLabelPoints($options);




        foreach ($rashiLabelPoints as $rashi => $point) {

            $bhava_arr = $Data->getData()['bhava'];
             $houses_arr  =  array_flip(array_combine(array_keys($bhava_arr), array_column($bhava_arr, 'rashi')));

            $this->Renderer->drawText(
                $rashi, // planet
                $point['x'], 
                $point['y'], 
                [
                    'textAlign' => $point['textAlign'],
                    'textValign' => $point['textValign'],
                    'house' => $houses_arr[$rashi],
                    'type' => 'number',
                    'aspect' => false,
                ],
                $rashi
            );
        }
    }


    /**
     * aspectsCoordsNew
     * @param $aspectArray
     * @return array
     */
    public  function  aspectsCoordsNew($aspectArray)
    {
        $result = [];
        foreach ($aspectArray as $planet => $aspectHousesArr)
        {
            foreach ($aspectHousesArr as $house)
            {
                $result[$house][] = $planet;
            }
        }

        return $result;
    }


    /**
     * aspectsCoords
     * @param $aspectArray
     * @return array
     */
    public  function  aspectsCoords($aspectArray)
    {

//      dd($aspectArray);

        $result = [];
        $tmp = [];
        foreach ($aspectArray as $planet => $aspectHousesArr)
        {

            foreach ($aspectHousesArr as $house)
            {

                for($i=1; $i<=12; $i++)
                {

                    $house = $house + $i;
                    $house = $house > 12 ? $house-12 : $house;

                    $tmp[$i][$house] = empty($tmp[$i][$house]) ? 1 : $tmp[$i][$house]+1;


                    switch ($house)
                    {
                        case 1:
                            $result[$planet][$i][] = [
                                'x' => 82+(8*$tmp[$i][$house]),
                                'y' => 80,
                            ];
                            break;

                        case 2:
                            $result[$planet][$i][] = [
                                'x' => 32+(8*$tmp[$i][$house]),
                                'y' => 4,
                            ];
                            break;

                        case 3:
                            $result[$planet][$i][] = [
                                'x' => 2,
                                'y' => 85-(5*$tmp[$i][$house]),
                            ];
                            break;

                        case 4:
                            $result[$planet][$i][] = [
                                'x' => 32+(8*$tmp[$i][$house]),
                                'y' => 130,
                            ];
                            break;

                        case 5:
                            $result[$planet][$i][] = [
                                'x' => 2,
                                'y' => 190-(5*$tmp[$i][$house]),
                            ];
                            break;

                        case 6:
                            $result[$planet][$i][] = [
                                'x' => 32+(8*$tmp[$i][$house]),
                                'y' => 198,
                            ];
                            break;

                        case 7:
                            $result[$planet][$i][] = [
                                'x' => 82+(8*$tmp[$i][$house]),
                                'y' => 180,
                            ];
                            break;

                        case 8:
                            $result[$planet][$i][] = [
                                'x' => 132+(8*$tmp[$i][$house]),
                                'y' => 198,
                            ];
                            break;

                        case 9:
                            $result[$planet][$i][] = [
                                'x' => 192,
                                'y' => 182-(5*$tmp[$i][$house]),
                            ];
                            break;

                        case 10:
                            $result[$planet][$i][] = [
                                'x' => 132+(8*$tmp[$i][$house]),
                                'y' => 130,
                            ];
                            break;

                        case 11:
                            $result[$planet][$i][] = [
                                'x' => 192,
                                'y' => 82-(5*$tmp[$i][$house]),
                            ];
                            break;

                        case 12:
                            $result[$planet][$i][] = [
                                'x' => 132+(8*$tmp[$i][$house]),
                                'y' => 4,
                            ];
                            break;
                    }


                }
            }

        }

        return $result;

    }


    /**
     * coordsCalcHouse
     * @param $startX
     * @param $startY
     * @param $interval
     * @param $planetsQuan
     * @return array
     */
   public function coordsCalcHouse($startX, $startY, $interval = 10, $planetsQuan = 12)
    {
        $result = [];
        for($i = 1; $i <= $planetsQuan; $i++)
        {
            $x = $startX-($interval*$i);
            switch ($i)
            {
                case 2: $x = $startX-15;  break;
                case 3: $x = $startX-17;  break;
                case 4: $x = $startX-25;  break;
                case 5: $x = $startX-20;  break;
                case 6: $x = $startX-25;  break;
                default: $x = $startX-($interval*$i);
            }
//            $x = $startX-$interval+($interval/$i);
//            $x = ($startX/$i)+$startY-$interval;
            for($n = 1; $n <= $i; $n++)
            {
                if ($n > 3)
                {
                    $x = $x-$interval;
                    $result[$i][] = ['x' => $x, 'y' => $startY-15];
                }
                else
                {
                    $x = $x+$interval;
                    $result[$i][] = ['x' => $x, 'y' => $startY-3];
                }

            }
        }

        return $result;
    }


    public function allCoords()
    {
        $arr = [
            1 => [100, 50],
            2 => [50, 20],
            3 => [25, 47],
            4 => [50, 100],
            5 => [20, 150],
            6 => [50, 180],
            7 => [100, 145],
            8 => [150, 180],
            9 => [180, 150],
            10 => [150, 100],
            11 => [180, 50],
            12 => [150, 20],
        ];

        $result = [];
        foreach($arr as $house => $coords)
        {
            $result[$house] = $this->coordsCalcHouse($coords[0], $coords[1], 10, 12);
        }

        return $result;
    }



    
    /**
     * Draw body labels.
     * 
     * @param int $x
     * @param int $y
     */
    private function drawBodyLabel($x, $y)
    {
        $Data = $this->Data;
        $options = $this->getOptions();
        $bodyLabelPoints = $this->Chakra->getBodyLabelPoints($x, $y, $options);

        $Analysis = new Analysis($Data);
        $graha = $Analysis->getVargaData($_GET['varga'])['graha'];
        $lagna = $Analysis->getVargaData($_GET['varga'])['lagna'];
        $degrees = array_merge($graha, $lagna);


       // $aspect = $this->isAspectedByGraha($options);
        $aspectArr = $this->isAspectArr($options);
        $aspectsCoords = $this->aspectsCoords($aspectArr);
        $aspectsCoordsNew = $this->aspectsCoordsNew($aspectArr);

        $isVarotama = $this->isVargottama();


        $exaltation = $this->isExaltationByGraha($options);
        $debilitation = $this->isDebilitationByGraha($options);

        $counts = [];
        foreach ($bodyLabelPoints as $body => $point) {
            $bodyLabel = $this->getBodyLabel($body, [
                'labelGrahaType' => $this->optionLabelGrahaType,
                'labelGrahaCallback' => $this->optionLabelGrahaCallback
            ]);

            $counts[$point['house']][] =  $bodyLabel;
        }




        $fixedCoords = $this->allCoords();
        $xy = [];
        foreach($counts as $h => $planets)
        {
            $quan = count($planets);
            $n = 0;
            foreach($planets as $planet)
            {
                $xy[$h][$planet] = [
                    'x' => $fixedCoords[$h][$quan][$n]['x'],
                    'y' => $fixedCoords[$h][$quan][$n]['y'],
                ];
                $n++;
            }

        }



//        echo '<pre>';
//        var_export($xy);
//        echo '</pre>';




        $i = 1;
        $counted = [];
        foreach ($bodyLabelPoints as $body => $point) {
            if (!array_key_exists($body, Graha::$graha) && isset($options['labelExtraFont'])) {
                $this->Renderer->setOptions($options['labelExtraFont']);
            } elseif (isset($options['labelGrahaFont'])) {
                $this->Renderer->setOptions($options['labelGrahaFont']);
            }
            
            $bodyLabel = $this->getBodyLabel($body, [
                'labelGrahaType' => $this->optionLabelGrahaType, 
                'labelGrahaCallback' => $this->optionLabelGrahaCallback
            ]);


            $plaentKey = str_replace('|r', '', $bodyLabel);





            $this->Renderer->drawText(
                $bodyLabel,
                $xy[$point['house']][$bodyLabel]['x'], //$point['x'],
                $xy[$point['house']][$bodyLabel]['y'], //$point['y'],
                [
                    'label' => $bodyLabel,
                    'textAlign' => $point['textAlign'],
                    'textValign' => $point['textValign'],
                    'house' => $point['house'],
                    'type' => 'text',
                    //'aspect' => !empty($aspectsCoords[$plaentKey]) ? $aspectsCoords[$plaentKey] : 0,
                     'aspect_new' => !empty($aspectsCoordsNew) ? $aspectsCoordsNew : 0,
                    'exalted' => !empty($exaltation[$plaentKey]) ? $exaltation[$plaentKey] : 0,
                    'debilitation' => !empty($debilitation[$plaentKey]) ? $debilitation[$plaentKey] : 0,
                    'degree' => round($degrees[$plaentKey]['degree'], 1),
                    'isVargotama' => $isVarotama,
                ]
            );

            $i++;
        }
    }
    
    /**
     * Return body label.
     * 
     * @param string $body
     * @param array $options
     * @return string
     */
    private function getBodyLabel($body, array $options)
    {

//        echo "<pre>";
//print_r($body);
//echo "</pre>";

        switch ($options['labelGrahaType']) {
            case 0:
                $label = $body;
                break;
            case 1:
                if (array_key_exists($body, Graha::$graha)) {
                    $grahaObject = Graha::getInstance($body);
                    $label = Utility::unicodeToHtml($grahaObject->grahaUnicode);
                } else {
                    $label = $body;
                }
                break;
            case 2:
                $label = call_user_func($options['labelGrahaCallback'], $body);
                break;
            default:
                $label = $body;
                break;
        }
        
        $data = $this->Data->getData();

        if (array_key_exists($body, Graha::listGraha(Graha::LIST_SAPTA))) {
            $vakraCheshta = $data['graha'][$body]['speed'] < 0 ? true : false;
        } else {
            $vakraCheshta = false;
        }
        
        $grahaLabel = $vakraCheshta ? $label . '|r' : $label;
//        $degree = number_format($data['graha'][$body]['degree'], 0, '','');
//        var_export($grahaLabel);
        return $grahaLabel;
    }
    
    
    
    
    
    /**
     * Set chakra varga.
     * 
     * @param string $value
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     * @throws Exception\UnexpectedValueException
     */
    public function setOptionChakraVarga($value)
    {
        $valueUcf = ucfirst($value);
        if (!array_key_exists($valueUcf, Varga::$varga)) {
            throw new Exception\UnexpectedValueException(
                'Varga key is wrong.'
            );
        }
        $this->optionChakraVarga = $valueUcf;
        return $this;
    }

    /**
     * Set chakra size. Chakra size should be greater than or equals 100.
     * 
     * @param int $value
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     * @throws Exception\OutOfRangeException
     */
    public function setOptionChakraSize($value)
    {
        if (!is_numeric($value) || intval($value) < 100) {
            throw new Exception\OutOfRangeException(
                'Chakra size should be greater than or equals 100.'
            );
        }
        $this->optionChakraSize = intval($value);
        return $this;
    }

    /**
     * Set chakra style. Chakra style provided should be 'north', 'south' or 'east'.
     * 
     * @param string $value
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     * @throws Exception\UnexpectedValueException
     */
    public function setOptionChakraStyle($value)
    {
        if (!in_array($value, Chakra::$style)) {
            throw new Exception\UnexpectedValueException(
                "Invalid chakra style provided should be 'north', 'south' or 'east'."
            );
        }
        $this->optionChakraStyle = strtolower($value);
        return $this;
    }
    
    /**
     * Set border offset. Border offset should be greater than or equals 0.
     * 
     * @param int $value
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     * @throws Exception\OutOfRangeException
     */
    public function setOptionOffsetBorder($value)
    {
        if (!is_numeric($value) || intval($value) < 0) {
            throw new Exception\OutOfRangeException(
                'Border offset should be greater than or equals 0.'
            );
        }
        $this->optionOffsetBorder = intval($value);
        return $this;
    }
    
    /**
     * Set display of rashi labels.
     * 
     * @param bool $showLabel
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     */
    public function setOptionLabelRashiShow($showLabel)
    {
        $this->optionLabelRashiShow = boolval($showLabel);
        return $this;
    }

    /**
     * Set graha label type. Label type provided should be 0, 1 or 2.
     * 
     * @param int $value
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     * @throws Exception\UnexpectedValueException
     */
    public function setOptionLabelGrahaType($value)
    {
        if (!in_array($value, [0, 1, 2])) {
            throw new Exception\UnexpectedValueException(
                "Invalid label type provided should be 0, 1 or 2."
            );
        }
        $this->optionLabelGrahaType = $value;
        return $this;
    }

    /**
     * Set callable function.
     * 
     * @param callable $value
     * @return \Jyotish\Draw\Plot\Chakra\Renderer
     * @throws Exception\RuntimeException
     */
    public function setOptionLabelGrahaCallback($value)
    {
        if (!is_callable($value)) {
            throw new Exception\RuntimeException("Function $value not supported.");
        }
        $this->optionLabelGrahaCallback = $value;
        return $this;
    }
}