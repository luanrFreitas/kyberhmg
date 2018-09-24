<?php
// ==================================
// FRASES DO STAR WARS - CLONE WARS
// ==================================
if ($_GET['acao'] == "frase") {
	$result = $PDO->query( "SELECT frase FROM frases ORDER BY RAND() LIMIT 1", PDO::FETCH_ASSOC);
	$frase = $result->fetch( PDO::FETCH_ASSOC );
	$low=array("Á" => "á", "É" => "é", "Í" => "í", "Ó" => "ó", "Ú" => "ú", "Ü" => "ü", "não" => "não","Ç" => "ç");
	$frase['frase'] = ucfirst(strtolower(strtr($frase['frase'],$low)));
	print $frase['frase'];
	
// ==================================
// SISTEMA DE NIVEL / EXPERIENCIA
// ==================================
} elseif ($_GET['acao'] == "xp") {
	$result = $PDO->query( "SELECT * FROM `jogadores` WHERE `discord` LIKE '%".$_GET['discord']."%' LIMIT 1", PDO::FETCH_ASSOC);
	$usuario = $result->fetch( PDO::FETCH_ASSOC );
	
	if ($usuario['nivel']){
		if (strtotime("now") - $usuario['tempo'] > 10) {
			$proximo = $usuario['nivel'] + 1;
			$proximo_nivel = ( pow($proximo, 3) - (6 * pow($proximo, 2)) + (17 * ($proximo)) - 12) * 50 / 3;
			$exp = $usuario['xp'] + rand(15, 25);
			if ($exp > $proximo_nivel) {
				$nivel = $usuario['nivel'] + 1;
				
				$atual = ( pow($nivel, 3) - (6 * pow($nivel, 2)) + (17 * ($nivel)) - 12) * 50 / 3;
				$proximo = $nivel + 1;
				$proximo = ( pow($proximo, 3) - (6 * pow($proximo, 2)) + (17 * ($proximo)) - 12) * 50 / 3;
				
				$retorno = array("nivel" => $nivel, "xp" => $exp, "proximo" => $proximo, "atual" => $atual);
			} else $nivel = $usuario['nivel'];
			
			$result = $PDO->query( "UPDATE `jogadores` 
				SET `xp` = ".$exp.", `nivel` = '".$nivel."', tempo = '".date("Y-m-d H:i:s", strtotime("now"))."'
				WHERE `discord` LIKE '%".$_GET['discord']."%'", PDO::FETCH_ASSOC);
		}
	}
	print json_encode($retorno);
} elseif ($_GET['acao'] == "xp_consulta") {
	$result = $PDO->query( "SELECT * FROM `jogadores` WHERE `discord` LIKE '%".$_GET['discord']."%' LIMIT 1", PDO::FETCH_ASSOC);
	$usuario = $result->fetch( PDO::FETCH_ASSOC );
	
	if ($usuario['nivel']){
		$exp = $usuario['xp'];
		$nivel = $usuario['nivel'];
		$atual = ( pow($nivel, 3) - (6 * pow($nivel, 2)) + (17 * ($nivel)) - 12) * 50 / 3;
		$proximo = $usuario['nivel'] + 1;
		$proximo = ( pow($proximo, 3) - (6 * pow($proximo, 2)) + (17 * ($proximo)) - 12) * 50 / 3;
		
		$retorno = array("nivel" => $nivel, "xp" => $exp, "proximo" => $proximo, "atual" => $atual);
	}
	print json_encode($retorno);
	
	//$result = $PDO->query( "SELECT * FROM `jogadores` WHERE `discord` LIKE '%".$_GET['discord']."%' LIMIT 1", PDO::FETCH_ASSOC);
	//$usuario = $result->fetch( PDO::FETCH_ASSOC );
	//echo $usuario['discord']." - xp: ".$usuario['xp']."/".$proximo_nivel." - nivel: ".$usuario['nivel']." - tempo: ".$usuario['tempo'];

	
// ==================================
// TIMES PARA ROTAÇÃO
// ==================================
} elseif ($_GET['acao'] == "times") {
	//	kyber.arcomclube.com.br/?pg=bot&acao=times&discord=405334968022204427
	require 'includes/rank_dados.php'; 
	
	for ($i = 1; $i <= count($rank); $i++) {
		$peso = 0;
		for ($o = 0; $o <= count($times)-1; $o++) { // 1
			//echo "<b>".$times[$o]['nome']."</b><br>";
			$peso += $times[$o]['peso'];
			$time[$o] = 0;
			$power[$o] = 0;
			$max_power[$o] = 0;
			$time_final = 0;
			$item = "";

			for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) { // 2
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) { // 3
					$c_atual = $times[$o]['chars'][$u][$a];
					
					$czeta = " ";
					for ($z = 0; $z < count($c_atual['zeta']); $z++) { $czeta .= "(z)"; }

					if ($rank[$i][$c_atual['nome']]['base_id'] > 0) {
						$reducao = 0;
						$zetas = 0;
						for ($z = 0; $z < count($c_atual['zeta']); $z++) {
							$atual = $PDO->query( "SELECT COUNT(*) AS total FROM `zetas` 
									LEFT JOIN `abilities` ON `abilities`.`base_id` = `zetas`.`zeta` 
									WHERE `zetas`.`player` = '".str_replace("'", "\'", $rank[$i]['player'])."' 
									AND `abilities`.`name` = '".str_replace("'", "\'", $times[$o]['chars'][$u][$a]['zeta'][$z])."'" );
							$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
							if ($num_atual[total] > 0) {
								$zetas++;
							} else {
								if ($rank[$i][$c_atual['nome']]['jafoi'] == 0) {
									$reducao++;
									if ($z == (count($c_atual['zeta'])-1)) $rank[$i][$c_atual['nome']]['jafoi'] = 1;
								}
							}
						}
						if ($reducao > 0)$rank[$i][$c_atual['nome']]['base_id'] = $rank[$i][$c_atual['nome']]['base_id']*((100-$reducao*25)/100);
					}
					if (isset($c_atual['unico'])) {
						if ($c_atual['unico']['extra'] == "principal") {
							if ($rank[$i][$c_atual['nome']]['rarity'] < 7) $time_final = 1;
						}
						if (isset($c_atual['unico']['campo'])) {
							$rank[$i][$c_atual['nome']]['base_id'] = $rank[$i][$c_atual['nome']][$c_atual['unico']['campo']] == $c_atual['unico']['max'] ? 100 : 0;
						}
					}
				}
				
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) { // 3
					$c_atual = $times[$o]['chars'][$u][$a];
					if ($rank[$i][$c_atual['nome']]['base_id'] > 0) {
						if ($rank[$i][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank[$i][$times[$o]['chars'][$u][1]['nome']]['base_id']) {
							if ($rank[$i][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank[$i][$times[$o]['chars'][$u][2]['nome']]['base_id']) 
								$ver = 0; else $ver = 2;
						} else {
							if ($rank[$i][$times[$o]['chars'][$u][1]['nome']]['base_id'] >= $rank[$i][$times[$o]['chars'][$u][2]['nome']]['base_id']) 
								$ver = 1; else $ver = 2;
						}
						
						if ($a == $ver) {
							
							$resultado[$rank[$i]['player']]['t'][$times[$o]['nome']]['time'][$char[$c_atual['nome']]['name']] = array(
								"nome"		=> $char[$c_atual['nome']]['name'].$czeta, 
								"gear"		=> $rank[$i][$c_atual['nome']]['gear_level']+0, 
								"level"		=> $rank[$i][$c_atual['nome']]['level']+0, 
								"rarity"	=> $rank[$i][$c_atual['nome']]['rarity']+0, 
								"power"		=> $rank[$i][$c_atual['nome']]['power']+0, 
								"max_power"	=> $rank[$i][$c_atual['nome']]['max_power']+0, 
								"zeta"		=> $zetas, 
								"rank"		=> number_format($rank[$i][$c_atual['nome']]['base_id'],2)
							);
							
							if($resultado[$rank[$i]['player']]['t'][$times[$o]['nome']]['img'] == "")
								$resultado[$rank[$i]['player']]['t'][$times[$o]['nome']]['img'] = str_replace("//swgoh.gg/static/img/assets/", "http://kyber.arcomclube.com.br/chars/", $char[$c_atual['nome']]['image']);
							$time[$o] += $rank[$i][$c_atual['nome']]['base_id'];
							$power[$o] += $rank[$i][$c_atual['nome']]['power'];
							$max_power[$o] += $rank[$i][$c_atual['nome']]['max_power'];
						}
					}
					//echo $char[$c_atual['nome']]['name']."<br>";
				}
			}	
			$time[$o] = $time_final > 0 ? 0 : $time[$o];
			$final[$i] += ($time[$o] / 5) * $times[$o]['peso'];

			$resultado[$rank[$i]['player']]['t'][$times[$o]['nome']]['time_rank'] = number_format($time[$o] / 5, 2);
			$resultado[$rank[$i]['player']]['t'][$times[$o]['nome']]['time_power'] = $power[$o];
			$resultado[$rank[$i]['player']]['t'][$times[$o]['nome']]['time_max_power'] = $max_power[$o];
		}
		$resultado[$rank[$i]['player']]['final'] = number_format($final[$i] / $peso, 2);
	}
	//print "<pre>";
	//var_dump($resultado);
	//print_r($resultado);
	//print "</pre>";
	print json_encode($resultado);
}
?>