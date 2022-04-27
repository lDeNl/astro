<?
#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);

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
use Jyotish\Base\Locality;
use Jyotish\Base\Analysis;
use Jyotish\Ganita\Method\Swetest;
use Jyotish\Dasha\Dasha;
use Jyotish\Draw\Draw;

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




if(!empty($_GET["varga"])) {
    $varga = $_GET["varga"];
    $data = new Data($DateTime, $Locality, $Ganita);
    $data->calcVargaData([$varga]);
    $imp = array_merge($data->getData(),$data->getData()["varga"][$varga]);
    $data->setData($imp);
    $data->calcRising();
    #$data->calcPanchanga();
    #$data->calcUpagraha();
} else {
    $data = new Data($DateTime, $Locality, $Ganita);
    $data->calcParams();
    $data->calcRising();
    #$data->calcPanchanga();
   # $data->calcUpagraha();
}

#echo "<pre>";
#print_r($data->getData());
#echo "</pre>"; 

$varga = ["D1","D2","D3","D4","D7","D9","D10","D12","D16","D20","D24","D27","D30","D40","D45","D60"];

echo '<select id="change_vdata" name="varga">';
foreach($varga as $varga_item) {
    if(!empty($_GET["varga"]) && $_GET["varga"] == $varga_item){
        echo'<option selected value="'.$varga_item.'">'.$varga_item.'</option>';
    } else {
        echo'<option value="'.$varga_item.'">'.$varga_item.'</option>';
    }
}
echo '</select>';

$draw = new Draw(202,202, "svg");
$draw->drawChakra($data,0,0);
$draw->render();

?>