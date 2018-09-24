<style>
tr { text-align: left; }
</style>
<?php
$i = 0;
$readPlayer = $PDO->query("SELECT DISTINCT `player` FROM `units` ORDER BY `player`");
foreach ($readPlayer as $player){
	$i++;
	$rank[$i]['player'] = $player['player'];
	
	$o = 0;
	$readUnit = $PDO->query("SELECT * FROM `units` WHERE `player` = '".$player['player']."'");
	foreach ($readUnit as $unit){
		$o++;
		//$rank[$i][$unit['base_id']] = 5;
		$rank[$i][$unit['base_id']] = ($unit['gear_level']/12)+($unit['level']/85)+($unit['rarity']/7);
		$rank[$i][$unit['base_id']] = round($rank[$i][$unit['base_id']]*100/3, 1);
		//(($unit['gear_level']/12)+($unit['level']/85)+($unit['rarity']/12))100/3;
	}
}

$i = 0;
$readBase = $PDO->query("SELECT DISTINCT `characters`.`name`, `characters`.`base_id`, `characters`.`image` FROM `units` INNER JOIN `characters` ON `units`.`base_id` = `characters`.`base_id` ORDER BY `characters`.`name`");
foreach ($readBase as $base_id){
	$chars[$i]['name'] 		= $base_id['name'];
	$chars[$i]['base_id'] 	= $base_id['base_id'];
	$chars[$i]['image'] 	= $base_id['image'];
	++$i;
}

print "<TABLE>";
for ($i = 0; $i <= count($rank); $i++) {
	if ( $i == 0 ) {
		print "<TR><TD></TD>";
		for ($o = 0; $o <= count($chars)-1; $o++) {
			print "<TD><img src='".$chars[$o]['image']."' alt='".$chars[$o]['name']."' title='".$chars[$o]['name']."' height='30' width='30'></TD>";
		}
		print "</TR>";
	} else {
		print "<TR><TD>".$rank[$i]['player']."</TD>";
		for ($o = 0; $o <= count($chars)-1; $o++) {
			print "<TD".($rank[$i][$chars[$o]['base_id']] > 90 ? "  bgcolor='#00FF00'" : "").">".$rank[$i][$chars[$o]['base_id']]."</TD>";
		}
		print "</TR>";
	}
}
print "</TABLE>";

?>