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
// CHARACTERS
// =====================
echo "<b>PERSONAGENS</b><br>";
$count = 0;
$PDO->query( "TRUNCATE `characters`" );

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, "https://swgoh.gg/api/characters/" );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec( $ch );
curl_close($ch);

$chars = json_decode($response, true);
for ($i = 0; $i <= count($chars)-1; $i++) {
	$sql = "INSERT INTO characters (name, base_id, pk, url, image, power, description, combat_type) 
		VALUES ('".str_replace("'", "\'", $chars[$i]['name'])."', '".$chars[$i]['base_id']."', ".$chars[$i]['pk'].", 
		'".$chars[$i]['url']."', '".$chars[$i]['image']."', ".$chars[$i]['power'].", 
		'".str_replace("'", "\'", $chars[$i]['description'])."', ".$chars[$i]['combat_type'].") 
		ON DUPLICATE KEY UPDATE base_id='".$chars[$i]['base_id']."'";
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

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, "https://swgoh.gg/api/ships/" );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec( $ch );
curl_close($ch);

$ships = json_decode($response, true);
for ($i = 0; $i <= count($ships)-1; $i++) {
	$sql = "INSERT INTO ships (name, base_id, url, image, power, description, combat_type) 
		VALUES (
			'".str_replace("'", "\'", $ships[$i][name])."', 
			'".$ships[$i][base_id]."', 
			'".$ships[$i][url]."', 
			'".$ships[$i][image]."', 
			".$ships[$i][power].", 
			'".str_replace("'", "\'", $ships[$i][description])."', 
			".$ships[$i][combat_type]."
		) 
		ON DUPLICATE KEY UPDATE base_id='".$ships[$i][base_id]."'";
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

echo "<br><br><b>UNIDADES</b><br>";
$count = 0;
$PDO->query( "TRUNCATE `units`" );

for ($u = 0; $u <= count($guildas)-1; $u++) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, "https://swgoh.gg/api/guilds/".$guildas[$u]."/units/" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec( $ch );
	curl_close($ch);

	$units = json_decode($response, true);
	$chars = array_keys($units);

	for ($i = 0; $i <= count($chars)-1; $i++) {
		for ($o = 0; $o <= count($units[$chars[$i]])-1; $o++) {
			if ($units[$chars[$i]][$o][combat_type] == 2) { 
				$units[$chars[$i]][$o][gear_level] = 0;
				$units[$chars[$i]][$o][url] = "";
			}
			$sql = "INSERT INTO units (base_id, gear_level, power, level, url, combat_type, rarity, player, guilda) 
				VALUES (
					'".$chars[$i]."', 
					".$units[$chars[$i]][$o][gear_level].", 
					".$units[$chars[$i]][$o][power].", 
					".$units[$chars[$i]][$o][level].", 
					'".$units[$chars[$i]][$o][url]."', 
					".$units[$chars[$i]][$o][combat_type].", 
					".$units[$chars[$i]][$o][rarity].", 
					'".str_replace("'", "\'", $units[$chars[$i]][$o][player])."',
					".$guildas[$u]."
				)";
				
			$PDO->query( $sql );
			++$count;
			//print ($u+1)."/".($i+1)."/".($o+1)." - ".$chars[$i]." - ".$units[$chars[$i]][$o][player]."<br>";
		}
	}
}
print $count." Unidades<br>";
$total += $count;
// =====================
// UNITS (FIM)
// =====================


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

			/*
			print "<pre>";
			print ($o-1)." - ".$player[1][0]."<br>";
			print_r($zetas[1]);
			print "</pre>";
			*/
		}
	}
}

$PDO->query( "INSERT INTO jogadores (url) SELECT DISTINCT url FROM units WHERE not exists (select url from jogadores where jogadores.url = units.url)" );

print $count." Zetas<br>";
$total += $count;
// =====================
// ZETAS (FIM)
// =====================


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