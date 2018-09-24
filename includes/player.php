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
// =====================
// UNITS
// =====================

	echo "https://swgoh.gg/api/player/".$_GET['ally'];
	$url = file_get_contents("https://swgoh.gg/api/player/".$_GET['ally']);
	$unit = json_decode($url, True);
	$units['players'][0] = $unit;
	
	//echo "<pre>";
	//print_r ($units);
	//echo "</pre>";
	
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
					44601
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

echo "<br><br><b>UNIDADES</b><br>";
print $count." Unidades<br>";
$total += $count;
echo "<br><br><b>ZETAS</b><br>";
print $count_z." Zetas<br>";
$total += $count_z;
// =====================
// UNITS (FIM)
// =====================

print "--------- <br>";
print $total." Registros Atualizados<br>";

?>
			</div>
		</div>
	</div>