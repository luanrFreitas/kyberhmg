<?php
/*
$url = file_get_contents("https://swgoh.gg/api/abilities/");
$characters = json_decode($url,true);

echo "<pre>";
print_r($characters);
echo "</pre>";

foreach ($characters as &$char) {
    //print_r ($char);
	print $char->{'name'}." / ".$char->{'base_id'}." / ".$char->{'pk'}."<br>";
	//print $char->{'url'}." / ".$char->{'image'}." / ".$char->{'power'}."<br>";
	//print $char->{'description'}." / ".$char->{'combat_type'}."<br>";
	//print $char->{'alignment'}."<br>";
	//print $char->{'categories'}."<br>";
	//print $char->{'ability_classes'}."<br>";
	//print $char->{'role'}."<br>";
}
*/

/*
$url = file_get_contents("https://swgoh.gg/api/characters/");
$characters = json_decode($url);

foreach ($characters as &$char) {
    //print_r ($char);
	print $char->{'name'}." / ".$char->{'base_id'}." / ".$char->{'pk'}."<br>";
	//print $char->{'url'}." / ".$char->{'image'}." / ".$char->{'power'}."<br>";
	//print $char->{'description'}." / ".$char->{'combat_type'}."<br>";
	//print $char->{'alignment'}."<br>";
	//print $char->{'categories'}."<br>";
	//print $char->{'ability_classes'}."<br>";
	//print $char->{'role'}."<br>";
}
*/

/*
$url = file_get_contents("https://swgoh.gg/api/ships/");
$ships = json_decode($url);

foreach ($ships as &$ship) {
    //print_r ($char);
	print $ship->{'name'}." / ".
	$ship->{'base_id'}." / ".
	$ship->{'url'}." / ".
	$ship->{'image'}." / ".
	$ship->{'power'}." / ".
	$ship->{'description'}." / ".
	$ship->{'combat_type'}." / ".
	$ship->{'alignment'}." / ".
	//$ship->{'categories'}." / ".
	//$ship->{'ability_classes'}." / ".
	//$ship->{'role'}." / ".
	//$ship->{'capital_ship'}."<br>";
	print "<br>";
}
*/

$guildas = array("41866");
//$guildas = array("41866", "42468", "42459", "42570", "44601", "21466", "47355");
// Bnar, Manto, Solari, Aquamarine, Kaiburr, Dagobah, Lambent

echo "<br><br><b>UNIDADES</b><br>";
$count = 0;
//$PDO->query( "TRUNCATE `units`" );

for ($u = 0; $u <= count($guildas)-1; $u++) {
	$url = file_get_contents("https://swgoh.gg/api/guild/".$guildas[$u]);
	$units = json_decode($url, True);

echo "<pre>";
print_r($units);
echo "</pre>";
/*
	for ($p = 0; $p < count($units['players']); $p++) {
		$player = $units['players'][$p]['data']['name'];
		foreach ($units['players'][$p]['units'] as &$unit) {
			foreach ($unit['data']['zeta_abilities'] as &$zeta) {
				print $player." - ".$zeta."<br>";
			}
			*/
/*
			print $unit['data']['base_id']."<br>";
			
			
			//$result = $PDO->query( "SELECT combat_type FROM `ships` WHERE `base_id` LIKE '".$unit['data']['base_id']."'", PDO::FETCH_ASSOC);
			//$combat_type = $result->fetch( PDO::FETCH_ASSOC );
	
			$sql = "INSERT INTO units (base_id, gear_level, power, level, url, combat_type, rarity, player, guilda) 
				VALUES (
					'".$unit['data']['base_id']."', 
					".$unit['data']['gear_level'].", 
					".$unit['data']['power'].", 
					".$unit['data']['level'].", 
					'".$unit['data']['url']."', 
					".$combat_type['combat_type'].", 
					".$unit['data']['rarity'].", 
					'".str_replace("'", "\'", $player)."',
					".$guildas[$u]."
				)";
				echo $sql."<br>";
			//$PDO->query( $sql );
			++$count;
*/
/*
		}
	}
	*/
}


?>