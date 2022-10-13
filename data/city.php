<?php

$city_cnt = 10; //сколько городов извлекать


try {
    $dbh = new PDO('mysql:host=den9110s.beget.tech;dbname=den9110s_geo', 'den9110s_geo', '5y35IaP&');
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$q = $_GET['term'];
 if ($is_cyr = (bool) preg_match('/[\p{Cyrillic}]/u', $q)){
 	$sql = "SELECT * FROM `geodb` WHERE `city_ru` LIKE :pattern ORDER BY `population` DESC LIMIT $city_cnt";
 	$txt_r = 'city_ru';
 }else{
 	$sql = "SELECT * FROM `geodb` WHERE `city_ascii` LIKE :pattern ORDER BY `population` DESC LIMIT $city_cnt";
 	$txt_r = 'city_ascii';
 }
$q = $q.'%';
$get = $dbh->prepare($sql);
$get->execute([':pattern'=>$q]);
while($a = $get->fetch()){
	$data = $a;
	$data['text'] = $a[$txt_r];	
	if ($txt_r == 'city_ru'){
		$data['admin'] = !$a['admin_name_ru'] ? $a['admin_name'] : $a['admin_name_ru'];
	}else{
		$data['admin'] = $a['admin_name'];
	}
	$data['res_cntr'] = ($txt_r == 'city_ru') ? $a['country_ru'] : $a['country'];	

	$s[] = $data;
}
$s[] = ['text'=>'wiki','admin'=>'wiki'];
//print_r($s);
echo json_encode($s);