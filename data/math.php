<?

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$per_name = $_POST["name"];
$per_date = $_POST["date"];
$per_time = $_POST["time"];
$per_utc = $_POST["utc"];
$per_longitude = $_POST["longitude"];
$per_latitude = $_POST["latitude"];

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


$Locality = new Locality([
            'longitude' => $per_longitude,
            'latitude' => $per_latitude,
            'altitude' => 0,
            ]);
$DateTime = new DateTime();
$DateTime->setTimezone(new DateTimeZone($per_utc));
$DateTime->setDate($per_date_y,$per_date_m,$per_date_d);
$DateTime->setTime($per_time_h,$per_time_m);
$Ganita = new Swetest(["swetest" => __DIR__ . "/../vendor/swetest/sweph/"]);

#=========================VARIABLES===============================

$outputPlanets = [
        Graha::KEY_SY => 'Sun',
        Graha::KEY_CH => 'Moon',
        Graha::KEY_MA => 'Mars',
        Graha::KEY_BU => 'Mercury',
        Graha::KEY_GU => 'Jupiter',
        Graha::KEY_SK => 'Venus',
        Graha::KEY_SA => 'Saturn',
        Graha::KEY_RA => 'Rahu',
        Graha::KEY_LG => 'Ascendant',
        Graha::KEY_KE => 'Ketu',
        /* Russian
        Graha::KEY_SY => 'Солнце',
        Graha::KEY_CH => 'Луна',
        Graha::KEY_MA => 'Марс',
        Graha::KEY_BU => 'Меркурий',
        Graha::KEY_GU => 'Юпитер',
        Graha::KEY_SK => 'Венера',
        Graha::KEY_SA => 'Сатурн',
        Graha::KEY_RA => 'Раху',
        Graha::KEY_LG => 'Асцендент',
        Graha::KEY_KE => 'Кету',
        */
    ];

$outputLagna = [
        'Ascendant' => Lagna::KEY_LG,
    ];

$rashi = [
  1 => 'Aries',
  2 => 'Taurus',
  3 => 'Gemini',
  4 => 'Cancer',
  5 => 'Leo',
  6 => 'Virgo',
  7 => 'Libra',
  8 => 'Scorpio',
  9 => 'Sagittarius',
  10 => 'Capricorn',
  11 => 'Aquarius',
  12 => 'Pisces',
 
  /* Russian
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
  */
  
];

$varga = ["D1","D2","D3","D4","D7","D9","D10","D12","D16","D20","D24","D27","D30","D40","D45","D60"];

#==================================================================



$data = new Data($DateTime, $Locality, $Ganita);
$data->calcParams();
$data->calcRising();
#$data->calcPanchanga();
// To calculate Upagraha
#$data->calcUpagraha();



#$data->calcDasha("vimshottari", null);
// $data->calcYoga(['mahapurusha','Dhana','RAJA']);
// $data->calcHora();
$data->calcExtraLagna();
// $data->calcBhavaArudha();
$data->calcNakshatra($outputPlanets);


$Analysis = new Analysis($data);

$new = [
"data" => $data,
"Analysis" => $Analysis,
];

#$dasha = $data->getData()['dasha']['vimshottari']['periods'];

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

?>
<!--
   Panchanga * Thiti: getData()['panchanga']['tithi']['paksha'] . ' ' .$data->getData()['panchanga']['tithi']['name'] . ' ' .round($data->getData()['panchanga']['tithi']['left'], 2) . ' % left'; ?> * Nakshatra: getData()['panchanga']['nakshatra']['name']; ?> * Yoga: getData()['panchanga']['yoga']['name']; ?> * Vara: getData()['panchanga']['vara']['name']; ?> Lord: getData()['panchanga']['vara']['key']; ?> * Karana: getData()['panchanga']['karana']['name']; ?>
-->

<select id="change_varga" name="varga">
    <?php foreach($varga as $varga_key): ?>
        <option value="<?=$varga_key?>"><?=$varga_key?></option>
    <?php endforeach; ?>
</select>
<div id="varga">
    <?php foreach($varga as $varga_key): ?>
    	<div class="col-md-4 varga_table <?=$varga_key?> <?php if($varga_key == "D1"){echo "show";}?>">
    		<table class="table table-striped table-hover">
    			<thead>
    				<tr>
    					<th>Planets</th>
    					<th>Degrees</th>
    					<th>Zodiac sign</th>
    					<th>Nakshatra</th>
    					<th>Karaka</th>
    					<!-- Russian
    					<th>Планеты</th>
    					<th>Градусы</th>
    					<th>Знак Зодиака</th>
    					<th>Накшатра</th>
    					<th>Карака</th>
    					-->
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
</div>

		
		
		
		
		
		
		
		
		