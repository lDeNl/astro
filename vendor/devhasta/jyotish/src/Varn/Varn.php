<?php
namespace Jyotish\Varn;

class Varn
{
    protected $BirthDate = null;

    public $Varns = [
        [
            'title' => 'Учитель',
            'values' => [3,6],
        ],
        [
            'title' => 'Воин',
            'values' => [1,9],
        ],
        [
            'title' => 'Торговец',
            'values' => [2,5],
        ],
        [
            'title' => 'Ремесленник',
            'values' => [4,7,8],
        ]
    ];



    /**
     * __construct
     * @param $BirthDate
     */
    public function __construct($BirthDate)
    {
        $this->setBirthDateInstance(date('d.m.Y', strtotime($BirthDate)));
    }

    /**
     * setBirthDateInstance
     * @param $BirthDate
     * @return $this
     */
    public function setBirthDateInstance($BirthDate)
    {
        $this->BirthDate = $BirthDate;
        return $this;
    }


    /**
     * @return false|string[]
     */
    public function DateArr()
    {
        return explode('.', $this->BirthDate);
    }


    /**
     * DayCalculation
     * @return float|int
     */
    public function DayCalculation()
    {
        $arr = str_split($this->DateArr()[0]);
        $out = array_sum($arr);
        if(strlen($out) > 1)
        {
            $arr = str_split($out);
            $out = array_sum($arr);
        }
        return $out;
    }

    /**
     * MonthCalculation
     * @return float|int
     */
    public function MonthCalculation()
    {
        $arr = str_split($this->DateArr()[1]);
        $out = array_sum($arr);
        if(strlen($out) > 1)
        {
            $arr = str_split($out);
            $out = array_sum($arr);
        }

        return $out;

    }


    /**
     * YearCalculation
     * @return float|int
     */
    public function YearCalculation()
    {
        $arr = str_split($this->DateArr()[2]);
        $out = array_sum($arr);
        if(strlen($out) > 1)
        {
            $arr = str_split($out);
            $out = array_sum($arr);

        }
        return $out;
    }

    /**
     * SumAll
     * @return float|int
     */
    public function SumAll()
    {
        $arr = [
            $this->DayCalculation(),
            $this->MonthCalculation(),
            $this->YearCalculation(),
        ];
        $out = array_sum($arr);
        if(strlen($out) > 1)
        {
            $arr = str_split($out);
            $out = array_sum($arr);
        }
        return $out;
    }

    /**
     * DefineExpression
     * @return float|int
     */
    public function DefineExpression()
    {
        $arr = [
            $this->DayCalculation(),
            $this->MonthCalculation(),
        ];
        $out = array_sum($arr);
        if(strlen($out) > 1)
        {
            $arr = str_split($out);
            $out = array_sum($arr);

        }
        return $out;
    }


    /**
     * VarnNameArr
     * @return array
     */
    public function VarnNameArr()
    {
        $arr = [];
        foreach ($this->Varns as $varn)
        {

            if (in_array($this->DayCalculation(), $varn['values']))
            {
                $prc = isset($arr[$varn['title']]) ? $arr[$varn['title']][0] : 0;
                $arr[$varn['title']]  = [40+$prc, $varn['title']];
            }

            if (in_array($this->MonthCalculation(), $varn['values']))
            {
                $prc = isset($arr[$varn['title']]) ? $arr[$varn['title']][0] : 0;
                $arr[$varn['title']]  = [10+$prc, $varn['title']];
            }

            if (in_array($this->YearCalculation(), $varn['values']))
            {
                $prc = isset($arr[$varn['title']]) ? $arr[$varn['title']][0] : 0;
                $arr[$varn['title']] =  [10+$prc, $varn['title']];
            }

            if (in_array($this->SumAll(), $varn['values']))
            {
                $prc = isset($arr[$varn['title']]) ? $arr[$varn['title']][0] : 0;
                $arr[$varn['title']] =  [40+$prc, $varn['title']];
            }

            if (in_array($this->DefineExpression(), $varn['values']))
            {
                $arr['Экспрессия'] = [$varn['title'], 'Экспрессия'];
            }
        }

//        dd($arr);

        $result = [];
        foreach($arr as $value)
        {
            $prc = $value[1] !== 'Экспрессия' ? '%' : '';
            $result[$value[0]] = $value[1] . ' - ' . $value[0] . $prc;
        }

        krsort($result);

        return $result;

    }
}