<?

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$lon_lat_arr = explode(', ', $_POST['lon_lat']);

$per_name = $_POST["name"];
$per_date = $_POST["date"] ? date('d.m.Y', strtotime($_POST['date'])) : date('d.m.Y');
$per_time = join(':', $_POST["time"]);
$per_utc = $_POST["utc"];
$per_longitude = trim($lon_lat_arr[0]);
$per_latitude = trim($lon_lat_arr[1]);

$per_date_y = date("Y", strtotime($per_date));
$per_date_m = date("m", strtotime($per_date));
$per_date_d = date("d", strtotime($per_date));

$per_time_h = date("H", strtotime($per_time));
$per_time_m = date("i", strtotime($per_time));

require __DIR__ . '/../vendor/autoload.php';
use Jyotish\Base\Data;
use Jyotish\Base\Utility;
use Jyotish\Base\Locality;
use Jyotish\Base\Analysis;
use Jyotish\Ganita\Method\Swetest;
use Jyotish\Dasha\Dasha;
use Jyotish\Draw\Draw;
use Jyotish\Graha\Graha;
use Jyotish\Graha\Lagna;
use Jyotish\Tattva\Karaka;
use Jyotish\Panchanga\Nakshatra\Nakshatra;

/**
 * @param $dec
 * @return string
 */
function DDtoDMS($dec)
{
    $vars = explode(".",$dec);
    $deg = $vars[0];
    $tempma = "0.".$vars[1];
    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = number_format($tempma-($min*60),0,".","");
    return Utility::dmsToStirng(["d"=>$deg,"m"=>$min,"s"=>$sec]);
}

/**
 * getSklo
 * @param $number
 * @return string
 */
function getSklo($number)
{
    $one =' год';
    $two =' года';
    $three =' лет';
    $first = substr( $number , -1);
    $last = substr( $number , -2);
    if($first =='1' and $last !='11') { return $number.$one;}
//    elseif($first > 0 && $last > 0 && $first == $last) { return $number.$two;}
    elseif($first =='2' and $last !='12') { return $number.$two;}
    elseif($first =='3' and $last !='13') { return $number.$two;}
    elseif($first =='4' and $last !='14') { return $number.$two;}
    else{ return $number. $three; }


}


$Locality = new Locality([
            'longitude' => $per_longitude,
            'latitude' => $per_latitude,
            'altitude' => 0,
            ]);
$DateTime = new DateTime();
$DateTime->setTimezone(new DateTimeZone($per_utc));
$DateTime->setDate($per_date_y,$per_date_m,$per_date_d);
$DateTime->setTime($per_time_h,$per_time_m);
$Ganita = new Swetest(["swetest" => __DIR__ . "/../vendor/devhasta/swetest/sweph/"]);

#=========================VARIABLES===============================

$outputPlanets = [
    /* English*/
//        Graha::KEY_SY => 'Sun',
//        Graha::KEY_CH => 'Moon',
//        Graha::KEY_MA => 'Mars',
//        Graha::KEY_BU => 'Mercury',
//        Graha::KEY_GU => 'Jupiter',
//        Graha::KEY_SK => 'Venus',
//        Graha::KEY_SA => 'Saturn',
//        Graha::KEY_RA => 'Rahu',
//        Graha::KEY_LG => 'Ascendant',
//        Graha::KEY_KE => 'Ketu',
 
    /* Russian*/
        Graha::KEY_SY => 'Su - Солнце',
        Graha::KEY_CH => 'Mo - Луна',
        Graha::KEY_MA => 'Ma - Марс',
        Graha::KEY_BU => 'Me - Меркурий',
        Graha::KEY_GU => 'Ju - Юпитер',
        Graha::KEY_SK => 'Ve - Венера',
        Graha::KEY_SA => 'Sa - Сатурн',
        Graha::KEY_RA => 'Ra - Раху',
        Graha::KEY_KE => 'Ke - Кету',
        Graha::KEY_LG => 'As - Асцендент',
    ];

$outputLagna = [
        'Ascendant' => Lagna::KEY_LG,
    ];

$rashi = [
     /* English*/
//  1 => 'Aries',
//  2 => 'Taurus',
//  3 => 'Gemini',
//  4 => 'Cancer',
//  5 => 'Leo',
//  6 => 'Virgo',
//  7 => 'Libra',
//  8 => 'Scorpio',
//  9 => 'Sagittarius',
//  10 => 'Capricorn',
//  11 => 'Aquarius',
//  12 => 'Pisces',
 
  /* Russian*/
  1 => 'Овен',
  2 => 'Телец',
  3 => 'Близнецы',
  4 => 'Рак',
  5 => 'Лев',
  6 => 'Дева',
  7 => 'Весы',
  8 => 'Скорпион',
  9 => 'Стрелец',
  10 => 'Козерог',
  11 => 'Водолей',
  12 => 'Рыбы',

  
];

$varga = ["D1","D2","D3","D4","D7","D9","D10","D12","D16","D20","D24","D27","D30","D40","D45","D60"];

#==================================================================



$data = new Data($DateTime, $Locality, $Ganita);
$data->calcParams();
$data->calcRising();
#$data->calcPanchanga();
// To calculate Upagraha
#$data->calcUpagraha();



$data->calcDasha("vimshottari", null);

// $data->calcYoga(['mahapurusha','Dhana','RAJA']);
// $data->calcHora();
$data->calcExtraLagna();
// $data->calcBhavaArudha();
$data->calcNakshatra($outputPlanets);


$Analysis = new Analysis($data);
$Varn = new \Jyotish\Varn\Varn($per_date);
//
//dd($Varn->DayCalculation());
//dd($Varn->MonthCalculation());
//dd($Varn->YearCalculation());
//dd($Varn->SumAll());
//dd($Varn->DefineExpression());
//dd($Varn->VarnNameArr());

$new = [
"data" => $data,
"Analysis" => $Analysis,
];

$dasha = $data->getData()['dasha']['vimshottari']['periods'];
//$nasting2 = array_column($dasha, 'periods');
//$nasting3 = array_column($nasting2[0], 'periods');
//
//echo '<pre>';
//var_export($dasha_test);
////var_export($nasting3);
//echo '</pre>';

$karaka_key = array_flip(Karaka::listKaraka());
$karaka = [];
foreach(array_flip($Analysis->getCharaKaraka()) as $key=>$value){
    $karaka[$value] = $karaka_key[$key];
}

/*

$day=9;
$month=6;
$year=1985;
$hours=10;
$minute=45;
  $julianDate = gregoriantojd($month, $day, $year);

  //correct for half-day offset
  $dayfrac = date('G') / 24 - .5;
  if ($dayfrac < 0) $dayfrac += 1;

  $julianTime = ($hours*60+$minute)/60/24;
  //now set the fraction of a day
  $frac = $dayfrac + (date('i') + date('s') / 60) / 60 / 24;

  $julianDate = $julianDate + $frac + $julianTime; 
  
echo $julianDate;


echo "<pre>";
print_r($new);
echo "<pre>";
*/
$nast2 = [];
$nast3 = [];
?>
<!--
   Panchanga * Thiti: getData()['panchanga']['tithi']['paksha'] . ' ' .$data->getData()['panchanga']['tithi']['name'] . ' ' .round($data->getData()['panchanga']['tithi']['left'], 2) . ' % left'; ?> * Nakshatra: getData()['panchanga']['nakshatra']['name']; ?> * Yoga: getData()['panchanga']['yoga']['name']; ?> * Vara: getData()['panchanga']['vara']['name']; ?> Lord: getData()['panchanga']['vara']['key']; ?> * Karana: getData()['panchanga']['karana']['name']; ?>
-->
<div id="varga" class="table-wrapper">
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Главная</a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" data-year="<?= date('Y')?>"  data-month="<?= date('m')?>" data-time="<?= strtotime(date('Y-m-d'))?>">Периоды</a></li>
            <li role="presentation"><a href="#varns" aria-controls="varns" role="tab" data-toggle="tab">Варны</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
            <select id="change_varga" name="varga" class="form-control" style=" width: 100%; margin: auto; margin-bottom: 15px; ">
                <?php foreach($varga as $varga_key): ?>
                    <option value="<?=$varga_key?>"><?=$varga_key?></option>
                <?php endforeach; ?>
            </select>

            <?php foreach($varga as $varga_key): ?>
                <div class="col-md-12 varga_table <?=$varga_key?> <?php if($varga_key == "D1"){echo "show";}?>">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <!--
                                
                                <th>Planets</th>
                                <th>Degrees</th>
                                <th>Zodiac sign</th>
                                <th>Nakshatra</th>
                                <th>Karaka</th>
                                -->
                                
                                <th>Планеты</th>
                                <th>Градусы</th>
                                <th>Знак зодиака</th>
                                <th>Накшатра</th>
                                <th>Карака</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($Analysis->getVargaData($varga_key)['lagna'] as $key => $lagna): ?>
                            <tr>
                                <td><?php echo $outputPlanets[$key]; ?></td>
                                <td><?php echo DDtoDMS($lagna['degree']); ?></td>
                                <td><?php echo $rashi[$lagna['rashi']]; ?></td>
                                <td><?php echo $data->getData()['nakshatra'][$key]['name']."(".$data->getData()['nakshatra'][$key]['pada'].", ".$data->getData()['nakshatra'][$key]['ruler'].")"; ?></td>
                                <td><?php if(isset($karaka[$key])){echo $karaka[$key];}else{echo "-";} ?></td>
                            </tr>
                            <?php endforeach ?>
                            <?php foreach ($Analysis->getVargaData($varga_key)['graha'] as $key => $graha): ?>
                            <tr>
                                <td><?php echo $outputPlanets[$key]; ?></td>
                                <td><?php echo DDtoDMS($graha['degree']); ?></td>
                                <td><?php echo $rashi[$graha['rashi']]; ?></td>
                                <td><?php echo $data->getData()['nakshatra'][$key]['name']."(".$data->getData()['nakshatra'][$key]['pada'].", ".$data->getData()['nakshatra'][$key]['ruler'].")"; ?></td>
                                <td><?php if(isset($karaka[$key])){echo $karaka[$key];}else{echo "-";} ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            <div style="clear: both;"></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">

                <div class="col-md-4 scroll-bar">

                    <table class="table table-striped table-hover" id="Grid" style="padding: 0 15px;">
                        <thead>
                        <tr>
                            <!--
                            <th>Planet</th>
                            <th>Duration</th>
                            <th>Start</th>
                            <th>Age</th>
                            -->
                            <th>Планета</th>
                            <th>Длительность</th>
                            <th>Начало</th>
                            <th>Возраст</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dasha as $key => $value): ?>
                        <?php
                            if(!empty($dasha[$key]))
                            {
                                $nast2[] = $dasha[$key]['periods'];
                            }
                        ?>
                            <?php
                            $prefix = 'date_1';
                            $input_year = date('Y', strtotime($value['start']));
                            $input_month = date('m', strtotime($value['start']));
                            $input_day = date('d', strtotime($value['start']));
                            $current_year = date('Y');
                            $current_month = date('m');
                            $current_day = date('d');

                            $input_time = strtotime(date('Y-m-d', strtotime($value['start'])));
                            $current_time = strtotime(date('Y-m-d'));

                            $id = join('_', [$prefix, $input_year]);
                            $class = join('_', [$prefix, $input_year, $input_month]);
                            $class_condition = $current_time == $input_time ? join('_', [$prefix, 'current']) : ($input_time >  $current_time ? join('_', [$prefix, 'next']) : join('_', [$prefix, 'before']));
                            ?>
                            <tr id="<?= $id?>"  class="<?= $id?> <?= $class?> <?= $class_condition?>">
                                <td><?= $value['key']?></td>
                                <td><?= getSklo(floor($value['duration'] / 31536000)) ?></td>
                                <td><?= date('d.m.Y H:i:s', strtotime($value['start']))?></td>
                                <td><?php
                                    $age = floor((strtotime($value['start']) - strtotime($per_date)) / 31536000);
                                    echo $age <= 0 ? '&mdash;' : $age;
                                    ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="clear: both;"></div>
                </div>
                <div class="col-md-4 scroll-bar">

                    <table class="table table-striped table-hover" id="Grid" style="padding: 0 15px;">
                        <thead>
                        <tr>
                            <!--
                            <th>Planet</th>
                            <th>Duration</th>
                            <th>Start</th>
                            <th>Age</th>
                            -->
                            <th>Планета</th>
                            <th>Длительность</th>
                            <th>Начало</th>
                            <th>Возраст</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($nast2 as $k => $n2): ?>
                            <?php foreach ($n2 as $key => $value): ?>
                                <?php
                                if(!empty($n2[$key]))
                                {
                                    $nast3[] = $n2[$key]['periods'];
                                }
                                ?>
                                <?php
                                $prefix = 'date_2';
                                $input_year = date('Y', strtotime($value['start']));
                                $input_month = date('m', strtotime($value['start']));
                                $input_day = date('d', strtotime($value['start']));
                                $current_year = date('Y');
                                $current_month = date('m');
                                $current_day = date('d');

                                $input_time = strtotime(date('Y-m-d', strtotime($value['start'])));
                                $current_time = strtotime(date('Y-m-d'));

                                $id = join('_', [$prefix, $input_year]);
                                $class = join('_', [$prefix, $input_year, $input_month]);
                                $class_condition = $current_time == $input_time ? join('_', [$prefix, 'current']) : ($input_time >  $current_time ? join('_', [$prefix, 'next']) : join('_', [$prefix, 'before']));
                                ?>
                                <tr id="<?= $id?>"  class="<?= $id?> <?= $class?> <?= $class_condition?>" data-time="<?= $input_time?>">
                                    <td><?= $value['key']?></td>
                                    <td><?= getSklo(floor($value['duration'] / 31536000)) ?></td>
                                    <td><?= date('d.m.Y H:i:s', strtotime($value['start']))?></td>
                                    <td><?php
                                        $age = floor((strtotime($value['start']) - strtotime($per_date)) / 31536000);
                                        echo $age <= 0 ? '&mdash;' : $age;
                                        ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="clear: both;"></div>
                </div>
                <div class="col-md-4 scroll-bar">

                    <table class="table table-striped table-hover" id="Grid" style="padding: 0 15px;">
                        <thead>
                        <tr>
                            <!--
                            <th>Planet</th>
                            <th>Duration</th>
                            <th>Start</th>
                            <th>Age</th>
                            -->
                            <th>Планета</th>
                            <th>Длительность</th>
                            <th>Начало</th>
                            <th>Возраст</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($nast3 as $k => $n3): ?>
                            <?php foreach ($n3 as $key => $value): ?>
                                <?php
                                    $prefix = 'date_3';
                                    $input_year = date('Y', strtotime($value['start']));
                                    $input_month = date('m', strtotime($value['start']));
                                    $input_day = date('d', strtotime($value['start']));
                                    $current_year = date('Y');
                                    $current_month = date('m');
                                    $current_day = date('d');

                                    $input_time = strtotime(date('Y-m-d', strtotime($value['start'])));
                                    $current_time = strtotime(date('Y-m-d'));

                                    $id = join('_', [$prefix, $input_year]);
                                    $class = join('_', [$prefix, $input_year, $input_month]);
                                    $class_condition = $current_time == $input_time ? join('_', [$prefix, 'current']) : ($input_time >  $current_time ? join('_', [$prefix, 'next']) : join('_', [$prefix, 'before']));
                                ?>
                                <tr id="<?= $id?>"  class="<?= $id?> <?= $class?> <?= $class_condition?>" data-time="<?= $input_time?>">
                                    <td><?= $value['key']?></td>
                                    <td><?= getSklo(floor($value['duration'] / 31536000)) ?></td>
                                    <td><?= date('d.m.Y H:i:s', strtotime($value['start']))?></td>
                                    <td><?php
                                        $age = floor((strtotime($value['start']) - strtotime($per_date)) / 31536000);
                                        echo $age <= 0 ? '&mdash;' : $age;
                                        ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="clear: both;"></div>
                </div>

                <div style="clear: both;"></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="varns">
                <table class="table table-striped table-hover" id="Grid" style="padding: 0 15px;">
                    <thead>
                    <tr>
                        <th>Варны</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($Varn->VarnNameArr() as $item): ?>
                            <tr>
                                <td><?= $item?></td>
                            </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // $(document).ready( function () {
    //     $('.table').DataTable();
    // } );
</script>



<?php
//
//echo '<pre>';
////var_export($nast2);
////var_export($nasting3);
//echo '</pre>';

?>
		
		
		
		
		
		
		
		
		