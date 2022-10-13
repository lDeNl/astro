<?php

/**
 * @link      https://github.com/lDeNl/astro for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Renderer;
use Jyotish\Base\Utility;
use DOMDocument;
use DOMElement;
use DOMText;
use Jyotish;



/**
 * Class for rendering basic elements as svg.
 *
 * 
 */
class Svg extends AbstractRenderer
{
    public $lagnesh = false;
    public $rashiHouseArr = [];

    public $psigns = [
        '1' => 'Ma',
        '2' => 'Ve',
        '3' => 'Me',
        '4' => 'Mo',
        '5' => 'Su',
        '6' => 'Me',
        '7' => 'Ve',
        '8' => 'Ma',
        '9' => 'Ju',
        '10' => 'Sa',
        '11' => 'Sa',
        '12' => 'Ju',
    ];



    public function digbala($planet, $house)
    {
        $data = [
            'ju' => 1,
            'me' => 1,
            'mo' => 4,
            've' => 4,
            'sa' => 7,
            'ma' => 10,
            'su' => 10,
        ];


        if(!empty($data[strtolower($planet)]) && $data[strtolower($planet)] == $house)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public  function bhkar($planet, $house)
    {
        $data = [
            '1' => ['Su'],
            '2' => ['Ju'],
            '3' => ['Ma'],
            '4' => ['Mo', 'Me'],
            '5' => ['Ju'],
            '6' => ['Ma', 'Sa'],
            '7' => ['Ve'],
            '8' => ['Sa'],
            '9' => ['Su', 'Ju'],
            '10' => ['Me', 'Ju', 'Sa'],
            '11' => ['Ju'],
            '12' => ['Sa'],
        ];

        if(!empty($data[$house]) && in_array($planet, $data[$house]))
        {
            return true;
        }
        else
        {
            return false;
        }

    }




    /**
     * Renderer name.
     * 
     * @var string
     */
    protected $rendererName = \Jyotish\Draw\Draw::RENDERER_SVG;
    
    /**
     * SVG eletment.
     * 
     * @var DOMElement
     */
    protected $svg;

    protected $optionAttributes = [];
    
    /**
     * Constructor
     * 
     * @param int $width Width of drawing
     * @param int $height Height of drawing
     */
    public function __construct($width, $height)
    {
        $this->Resource = new DOMDocument('1.0', 'utf-8');
        $this->Resource->formatOutput = true;
        
        $this->svg = $this->Resource->createElement('svg');
        $this->svg->setAttribute('xmlns', "http://www.w3.org/2000/svg");
        $this->svg->setAttribute('version', '1.1');
        $this->svg->setAttribute('width', $width);
        $this->svg->setAttribute('height', $height);
        $this->svg->setAttribute('class', 'chakra');
        $this->svg->setAttribute('viewBox', "0 0 {$width} {$height}");

        $this->Resource->appendChild($this->svg);

        $this->appendRootElement('style', ['type' => 'text/css'], '
            polygon:hover {fill: #eee;}
            text {font-family: Arial; color: green;}
            text.rgrad {}
            text.rgrad > tspan {font-size: 7px;}
            text.aspect {color: #aaa; font-size: .3em;}
            text.exalted {font-size: .6em; font-weight: 700;}
            svg text.degree {font-size: .21em;}
        ');
        $this->appendRootElement('rect', ['width' => $width, 'height' => $height, 'fill' => 'white']);
        $this->appendRootElement('polygon', ['width' => 50, 'height' => 50, 'fill' => 'rbg(0,0,0)']);
//        <marker id="arrow" viewBox="0 0 10 10" refX="5" refY="5" style="stroke:red;fill:red;"
//        markerWidth="3" markerHeight="2.2"
//        orient="auto-start-reverse">
//        <path d="M 0 0 L 10 5 L 0 10 z" />
//      </marker>

        $this->appendRootElement('marker', [
            'id' => "red-up-arrow",
            'viewBox' => "0 0 10 10",
            'refX' => 5,
            'refY' => 5,
            'style' => 'fill:red;',
            'markerWidth' => '3',
            'markerHeight' => '3',
        ], '<path d="M 0 0 L 10 5 L 0 10 z" />');
    }

    /**
     * Draw polygon.
     * 
     * @param array $points An array containing the polygon's vertices.
     * @param null|array $options
     */
    public function drawPolygon(array $points, array $options = null)
    {
        $this->setOptions($options);
        
        $colorSrokeRgb = Utility::htmlToRgb($this->optionStrokeColor);
        $colorStrokeString = 'rgb(' . implode(', ', $colorSrokeRgb) . ')';

        $colorFillRgb = Utility::htmlToRgb($this->optionFillColor);
        $colorFillString = 'rgb(' . implode(', ', $colorFillRgb) . ')';

        $pointsString = implode(' ', $points);

        $attributes['points'] = $pointsString;
        $attributes['fill'] = $colorFillString;
        $attributes['stroke'] = $colorStrokeString;
        $attributes['stroke-width'] = $this->optionStrokeWidth;
        $attributes['stroke-linejoin'] = 'round';
        
        if (isset($this->optionAttributes) && is_array($this->optionAttributes)) {
            foreach ($this->optionAttributes as $name => $value) {
                $attributes[$name] = $value;
            }
        }

        $this->appendRootElement('polygon', $attributes);
    }

    /**
     * Draw text string.
     * 
     * @param string $text Text for drawing
     * @param int $x x-coordinate
     * @param int $y y-coordinate
     * @param null|array $options
     */
    public function drawText($text, $x = 0, $y = 0, array $options = null, $house = null)
    {

        $this->setOptions($options);

//        echo '<pre>';
//        var_export($options);
//        echo '</pre>';


        $colorRgb = Utility::htmlToRgb($this->optionFontColor);
        $color = 'rgb(' . implode(', ', $colorRgb) . ')';


        if(!empty($options['type']) && $options['type'] == 'number' && $options['house'] == 1 && !$this->lagnesh)
        {
            $this->lagnesh = $this->psigns[$text];

        }


        if(is_numeric($text))
        {
            $attributes['class'] = 'psign p_' . $this->psigns[$text];
            $attributes['data-sign'] = $this->psigns[$text];

//            $this->appendRootElement('text', [
//                'x' => $x,
//                'y' => $y,
//                'fill' => 'rgb(171, 72, 106)',
//                'style' => 'font-size: .2em;',
//                'font-size' => '.5em'
//            ], $this->psigns[$text]);


            $color = 'rgb(94, 74, 35)'; // purple
        }

        $retrograde = explode('|', $text);
        $add_style = '';
        if(!empty($retrograde[1]))
        {
            $text = $retrograde[0];
            $attributes['class'] = 'rgrad';
            $add_style = ' ';
        }

        if(!empty($options['type']) && $options['type'] == 'text')
        {
//            $attributes['data-digbala'] = $this->digbala($text, $options['house']) ? 1 : 0;

            // digbala
            if($this->digbala($text, $options['house']))
            {

                $this->appendRootElement('rect', [
                    'class' => 'green-sq',
                    'x' => $x-5,
                    'y' => $y-2.5,
                    'width' => '10',
                    'height' => '10',
                    'style' => 'fill:rgb(255,255,255, 0); stroke-width: 0.2; stroke:rgb(0,128,0)',
                ], html_entity_decode($text, ENT_COMPAT | ENT_HTML5, 'UTF-8'));
            }



//            $attributes['data-bhkar'] = $this->bhkar($text, $options['house']) ? 1 : 0;

            // bhkar
            if($this->bhkar($text, $options['house']))
            {

                $this->appendRootElement('circle', [
                    'class' => 'bhkar',
                    'cx' => $x-1,
                    'cy' => $y+3,
                    'r' => '5',
                    'width' => '10',
                    'height' => '10',
                    'style' => 'fill:rgb(255,255,255, 0); stroke-width: 0.2; stroke:rgb(185,64,21)',
                ], html_entity_decode($text, ENT_COMPAT | ENT_HTML5, 'UTF-8'));
            }






            if($this->lagnesh == $text)
            {
                $color = 'rgb(74, 175, 80)'; // Green
            }

            // Degrees
            $attributes['data-degree'] = $options['degree'];
        }

        // aspect
        if(!empty($options['type']) && $options['type'] == 'text' && is_array($options['aspect_new']))
        {

            $aspectTexts = join(' ', $options['aspect_new'][$options['house']]);

            $tmp = [];
            if(!empty($options['aspect_new'][$options['house']]))
            {
                foreach ($options['aspect_new'][$options['house']] as $key => $coords)
                {
//                    $this->appendRootElement('text', [
//                        'class' => 'aspect',
//                        'x' => $coords['x'],
//                        'y' => $coords['y'],
//                        'fill' => '#918a8a'
//                    ], html_entity_decode($text, ENT_COMPAT | ENT_HTML5, 'UTF-8'));
//


                    if(!in_array($options['house'], $tmp)) {

                        //dd($options['aspect_new'][$options['house']]);
                        $this->appendRootElement('text', [
                            'class' => 'aspect',
                            'x' => $coords['x'],
                            'y' => $coords['y'],
                            'fill' => '#918a8a'
                        ], html_entity_decode($aspectTexts, ENT_COMPAT | ENT_HTML5, 'UTF-8'));
                        $tmp[] = $options['house'];
                    }

                }

                $tmp[] = $text;
            }
        }

       // dd($options);


        if(is_numeric($text))
        {
            $this->rashiHouseArr[$options['house']] = $text;
        }

        // Exalted
        if(!empty($options['type']) && $options['type'] == 'text' && $options['exalted'] == $this->rashiHouseArr[$options['house']] )
        {


            $attributes['data-exalted'] = 1;
            // <line x1="15" y1="15" x2="15" y2="0" marker-start="url(#arrow)" stroke="red" stroke-width="1"/>
            $this->appendRootElement('line', [
                'class' => 'exalted-arrow',
                'x1' => $x-5,
                'y1' => $y,
                'x2' => $x-5,
                'y2' => $y+5,
                'stroke' => 'green',
                'stroke-width' => '0.5',
                'marker-start' => 'url(#arrow-up)'
            ]);

        }

        // Debilitation
        if(!empty($options['type']) && $options['type'] == 'text' && $options['debilitation'] == $this->rashiHouseArr[$options['house']])
        {
            $attributes['data-debilitation'] = 1;
            $this->appendRootElement('line', [
                'class' => 'debilitation-arrow',
                'x1' => $x-5,
                'y1' => $y,
                'x2' => $x-5,
                'y2' => $y+5,
                'stroke' => 'red',
                'stroke-width' => '0.5',
                'marker-end' => 'url(#arrow-down)'
            ]);
        }






        $attributes['x'] = $x;
        $attributes['y'] = $y;
        $attributes['fill'] = $color;
        $attributes['font-size'] = $this->optionFontSize * 1.2;
        $attributes['data-house'] = $options['house'];
        
        switch ($this->optionTextAlign) {
            case 'center':
                $textAnchor = 'middle';
                break;
            case 'right':
                $textAnchor = 'end';
                break;
            case 'left':
            default:
                $textAnchor = 'start';
                break;
        }

        switch ($this->optionTextValign) {
            case 'top':
                $attributes['y'] += $this->optionFontSize;
                break;
            case 'middle':
                $attributes['y'] += $this->optionFontSize / 2;
                break;
            case 'bottom':
            default:
                $attributes['y'] += 0;
                break;
        }

        $attributes['style'] = 'text-anchor: ' . $textAnchor . $add_style;


        $attributes['transform'] = 'rotate('
                . (- $this->optionTextOrientation)
                . ', '
                . ($x)
                . ', ' . ($y)
                . ')';
//        print_r($text."&#176;");
        $this->appendRootElement('text', $attributes, html_entity_decode($text, ENT_COMPAT | ENT_HTML5, 'UTF-8'));




//        $this->appendRootElement('text', [
//            'class' => 'xurma',
//            'x' => '32',
//            'y' => '75',
//        ], html_entity_decode('X', ENT_COMPAT | ENT_HTML5, 'UTF-8'));
    }
    
    /**
     * Render the drawing.
     */
    public function render()
    {
        header("Content-Type: image/svg+xml");
        echo $this->Resource->saveXML();
    }

    /**
     * @param $tagName
     * @param array $attributes
     * @param $textContent
     * @return void
     * @throws \DOMException
     */
    private function appendRootElement($tagName, array $attributes = [], $textContent = null)
    {
        $newElement = $this->createElement($tagName, $attributes, $textContent);
        $this->svg->appendChild($newElement);
    }

    /**
     * @param $tagName
     * @param array $attributes
     * @param $textContent
     * @return DOMElement|false
     * @throws \DOMException
     */
    private function createElement($tagName, array $attributes = [], $textContent = null)
    {
        $element = $this->Resource->createElement($tagName);

        foreach ($attributes as $k => $v) {
            $element->setAttribute($k, $v);
        }
        if ($textContent !== null) {

            $element->appendChild(new DOMText((string) $textContent));
        }
        return $element;
    }
}