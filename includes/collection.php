<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Coleções</h1>
							<h2><span>Dados | swgoh.gg</span></h2>
						</div>
					</div>
				</div>
			</div>
		</li>
		</ul>
	</div>
</aside>
	<div class="colorlib-event">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Dados Recuperados</h2>
					<p>Recuperação de dados do swgoh.gg</p>
				</div>
			</div>
			<div class="row row-pb-sm">

<?php

//$url = file_get_contents("https://swgoh.gg/api/guilds/9854/units/");
//$url = file_get_contents('https://swgoh.gg/api/characters/');
//$url = file_get_contents("https://swgoh.gg/api/ships/");
$total = 0;

// =====================
// ABILITIES
// =====================
echo "<b>HABILIDADES</b><br>";
$count = 0;
$PDO->query( "TRUNCATE `abilities`" );

$url = file_get_contents("https://swgoh.gg/api/abilities/");
$abilities = json_decode($url);

foreach ($abilities as &$abilitie) {
	$is_zeta = ($abilitie->{'is_zeta'}) ? 1 : 0;
	$is_omega = ($abilitie->{'is_zeta'}) ? 1 : 0;
	$sql = "INSERT INTO abilities (base_id, name, image, url, tier_max, is_zeta, is_omega, combat_type, type, character_base_id) 
		VALUES ('".$abilitie->{'base_id'}."', 
			'".str_replace("'", "\'", $abilitie->{'name'})."', 
			'".$abilitie->{'image'}."', 
			'".$abilitie->{'url'}."', 
			".$abilitie->{'tier_max'}.", 
			".$is_zeta.", 
			".$is_omega.", 
			".$abilitie->{'combat_type'}.", 
			".$abilitie->{'type'}.", 
			'".$abilitie->{'character_base_id'}."')
		ON DUPLICATE KEY UPDATE base_id='".$abilitie->{'base_id'}."'";
	$PDO->query( $sql );
	++$count;
	//print ($i+1)." - ".$chars[$i][name]."<br>";
}
print $count." habilidades<br>";
$total += $count;
// =====================
// ABILITIES (FIM)
// =====================


// =====================
// CHARACTERS
// =====================
echo "<br><br><b>PERSONAGENS</b><br>";
$count = 0;
$PDO->query( "TRUNCATE `characters`" );

$url = file_get_contents("https://swgoh.gg/api/characters/");
$characters = json_decode($url);

foreach ($characters as &$char) {
	$sql = "INSERT INTO characters (name, base_id, pk, url, image, power, description, combat_type) 
		VALUES ('".str_replace("'", "\'", $char->{'name'})."', 
			'".$char->{'base_id'}."', 
			".$char->{'pk'}.", 
			'".$char->{'url'}."', 
			'".$char->{'image'}."', 
			".$char->{'power'}.", 
			'".str_replace("'", "\'", $char->{'description'})."', 
			".$char->{'combat_type'}.") 
		ON DUPLICATE KEY UPDATE base_id='".$char->{'base_id'}."'";
	$PDO->query( $sql );
	++$count;
	//print ($i+1)." - ".$chars[$i][name]."<br>";
}
print $count." Chars<br>";
$total += $count;
// =====================
// CHARACTERS (FIM)
// =====================


// =====================
// SHIPS
// =====================
echo "<br><br><b>NAVES</b><br>";
$count = 0;
$PDO->query( "TRUNCATE `ships`" );
$url = file_get_contents("https://swgoh.gg/api/ships/");
$ships = json_decode($url);

foreach ($ships as &$ship) {
	$sql = "INSERT INTO ships (name, base_id, url, image, power, description, combat_type) 
		VALUES (
			'".str_replace("'", "\'", $ship->{'name'})."', 
			'".$ship->{'base_id'}."', 
			'".$ship->{'url'}."', 
			'".$ship->{'image'}."', 
			".$ship->{'power'}.", 
			'".str_replace("'", "\'", $ship->{'description'})."', 
			".$ship->{'combat_type'}."
		) 
		ON DUPLICATE KEY UPDATE base_id='".$ship->{'base_id'}."'";
	$PDO->query( $sql );
	++$count;
	//print ($i+1)." - ".$ships[$i][name]."<br>";
}
print $count." Naves<br>";
$total += $count;
// =====================
// SHIPS (FIM)
// =====================


// =====================
// UNITS
// =====================

$guildas = array("41866", "42468", "42459", "42570", "44601", "21466", "47355");
// Bnar, Manto, Solari, Aquamarine, Kaiburr, Dagobah, Lambent

$count = 0;
$count_z = 0;
$PDO->query( "TRUNCATE `units`" );
$PDO->query( "TRUNCATE `zetas`" );

for ($u = 0; $u <= count($guildas)-1; $u++) {
	$url = file_get_contents("https://swgoh.gg/api/guild/".$guildas[$u]);
	$units = json_decode($url, True);

	for ($p = 0; $p < count($units['players']); $p++) {
		$player = $units['players'][$p]['data']['name'];
		$url = $units['players'][$p]['data']['url'];
		$allycode = $units['players'][$p]['data']['ally_code'];
		
		//$PDO->query( "UPDATE jogadores SET url='".$url."' WHERE allycode=".$allycode."" );
		$PDO->query( "INSERT INTO jogadores (url) SELECT DISTINCT url FROM units WHERE not exists (SELECT url FROM jogadores WHERE jogadores.url = units.url)" );
		$PDO->query( "UPDATE jogadores SET allycode=".$allycode." WHERE url='".$url."'" );
		
		foreach ($units['players'][$p]['units'] as &$unit) {
			//print $unit['data']['base_id']."<br>";
			
			$result = $PDO->query( "SELECT combat_type FROM `ships` WHERE `base_id` LIKE '".$unit['data']['base_id']."'", PDO::FETCH_ASSOC);
			$combat_type = $result->fetch( PDO::FETCH_ASSOC );
			$is_combat_type = ($combat_type['combat_type']) ? 2 : 1;
			
			$sql = "INSERT INTO units (base_id, gear_level, power, level, url, combat_type, rarity, player, guilda) 
				VALUES (
					'".$unit['data']['base_id']."', 
					".$unit['data']['gear_level'].", 
					".$unit['data']['power'].", 
					".$unit['data']['level'].", 
					'".$url."', 
					".$is_combat_type.", 
					".$unit['data']['rarity'].", 
					'".str_replace("'", "\'", $player)."',
					".$guildas[$u]."
				)";
			//echo $sql."<br>";
			$PDO->query( $sql );
			++$count;
			
			foreach ($unit['data']['zeta_abilities'] as &$zeta) {
				//print $player." - ".$zeta."<br>";
				$sql_z = "INSERT INTO zetas (player, zeta) VALUES ('".$player."', '".$zeta."')";
				$PDO->query( $sql_z );
				++$count_z;
			}
		}
	}
}
echo "<br><br><b>UNIDADES</b><br>";
print $count." Unidades<br>";
$total += $count;
echo "<br><br><b>ZETAS</b><br>";
print $count_z." Zetas<br>";
$total += $count_z;
// =====================
// UNITS (FIM)
// =====================

/*
// =====================
// ZETAS
// =====================
echo "<br><br><b>ZETAS</b><br>";
$count = 0;
$PDO->query( "TRUNCATE `zetas`" );

$guildas = array(
	"https://swgoh.gg/g/41866/kyber-sacrificio-de-bnar/zetas/",
	"https://swgoh.gg/g/42468/kyber-o-manto-da-forca/zetas/",
	"https://swgoh.gg/g/42459/kyber-solari/zetas/",
	"https://swgoh.gg/g/42570/kyber-aquamarine/zetas/",
	"https://swgoh.gg/g/44601/kyber-kaiburr/zetas/",
	"https://swgoh.gg/g/21466/brazil-dagobah/zetas/",
	"https://swgoh.gg/g/47355/kyber-lambent/zetas/"
);

for ($i = 0; $i <= count($guildas)-1; $i++) {
	$url = file_get_contents($guildas[$i]);
	$players = explode("<strong>", $url);
	
	for ($o = 2; $o <= count($players)-1; $o++) {
		preg_match_all("/(.*)<\/strong>/", $players[$o], $player);
		preg_match_all("/g\" data-toggle=\"tooltip\" data-container=\"body\" title=\"(.*)\">/", $players[$o], $zetas);
		
		for ($u = 0; $u <= count($zetas[1])-1; $u++) {
			$sql = "INSERT INTO zetas (player, zeta) 
				VALUES ('".$player[1][0]."', '".$zetas[1][$u]."')";
				
			$PDO->query( $sql );
			++$count;

			//print "<pre>";
			//print ($o-1)." - ".$player[1][0]."<br>";
			//print_r($zetas[1]);
			//print "</pre>";
		}
	}
}

$PDO->query( "INSERT INTO jogadores (url) SELECT DISTINCT url FROM units WHERE not exists (select url from jogadores where jogadores.url = units.url)" );

print $count." Zetas<br>";
$total += $count;
// =====================
// ZETAS (FIM)
// =====================
*/

print "--------- <br>";
print $total." Registros Atualizados<br>";

/*
echo "<pre>";
print_r ($units);
echo "</pre>";
*/
?>
			</div>
		</div>
	</div>