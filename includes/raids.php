<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d", strtotime("-1 days")); else $dt = $_GET['dt'];
if (is_null($_GET['tp'])) $tp = "fosso"; else $tp = $_GET['tp'];

if (!isset($_SESSION['dt_ini'])) $_SESSION['dt_ini'] = date("Y-m-d", strtotime("-14 days"));
if (is_null($_POST['dt_ini'])) $dt_ini = $_SESSION['dt_ini']; else $dt_ini = $_POST['dt_ini'];
if (!isset($_SESSION['dt_ter'])) $_SESSION['dt_ter'] = date("Y-m-d", strtotime("-1 days"));
if (is_null($_POST['dt_ter'])) $dt_ter = $_SESSION['dt_ter']; else $dt_ter = $_POST['dt_ter'];

if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

$dt = date("Y-m-d", strtotime("now"));

$op = $_POST['op'];



$sqlCiclo = "SELECT * FROM ciclos ORDER BY inicio DESC LIMIT 10";
$readUser = $PDO->query( $sqlCiclo );
foreach ($readUser as $rows){
	if (is_null($_GET['ciclo'])) {
		$date1 = new DateTime($rows['inicio']);
		$date2 = new DateTime($rows['termino']);
		$hoje  = new DateTime("now");

		if ($date1 < $hoje AND $date2 > $hoje) $_GET['ciclo'] = $rows['id'];
	}

	if ($_GET['ciclo'] ==  $rows['id']) {
		$dt_ini = $rows['inicio'];
		$dt_ter = $rows['termino'];
	}
	$opcao .= "<option value='".$rows['id']."' ".(($_GET['ciclo'] ==  $rows['id']) ? "selected" : "").">De ".date("d/m/Y", strtotime($rows['inicio']))." a ".date("d/m/Y", strtotime($rows['termino']))."</option>";
}


// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT membros.id, membros.nome, membros.idguilda, guildas.nome AS guilda, guildas.tipo AS nivel
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";

// Gera os registros caso nenhum exista ainda para o print
$result = $PDO->query( $sql );

$ini = 0;
$r_dias = ""; $t_r_dias = 0;
if ($tp == "fosso") 	{ $r_tipo = "rancor"; $n_tipo = "fosso"; }
if ($tp == "tanque") 	{ $r_tipo = "tanque"; $n_tipo = "tanque"; }
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$nivel = $membro['nivel'];
	//echo $nivel;
	$date = $dt_ini;
	while (strtotime($date) <= strtotime($dt_ter)) {
		$check_raid = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = '".$r_tipo."'");
		$num_raid = $check_raid->fetch(PDO::FETCH_ASSOC);
		if ($num_raid[total] > 0) {
			$atual = $PDO->query("SELECT COUNT(*) AS total FROM raid INNER JOIN membros ON raid.idmembro = membros.id WHERE idmembro = ".$membro['id']." AND membros.idguilda = '".$gd."' AND data = '".$date."' AND tipo = '".$n_tipo."'");
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			if ($num_atual[total] > 0) {
			} else {
				$sql2 = "INSERT INTO `raid` VALUES (NULL, '".$membro['id']."', '".$date."',  '".$n_tipo."', 0, '".$membro['nivel']."')";
				$PDO->query( $sql2 );
			}
			if ($ini == 0) {
				$r_dias .= "<td style='text-align: center'><b>".date("d", strtotime($date))."</b></td>";
				$t_r_dias += 1;
			}
		}
		
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	$ini += 1;
}

$guilda = 0;

// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT membros.id, membros.nome, membros.telegram, membros.idguilda, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";
	
$result = $PDO->query( $sql );
$x = 0;

echo "
	<table width='100%'>";
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	if ($membro['idguilda'] <> $guilda and $guilda != 0) {
		echo "
		</table>
		<br><br>
		<table width='100%'>";
	}
	if ($membro['idguilda'] <> $guilda) {
		$guilda = $membro['idguilda'];
		echo "
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='".(7+$t_r_dias)."'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>
		<form action='' method='post'>
		<tr>
			<td colspan='".(6+$t_r_dias)."'>
				<b>Ciclo: </b> 
				<select name='ciclo' id='ciclo'>".$opcao."</select>
			</td>
		</tr>
		</form>
		<form action='' method='post'>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='100px'><b>Nick</b></td>
			<td width='100px'><b>Nome no Telegram</b></td>
			".($r_dias == "" ? "<td></td>" : $r_dias)."
			<td width='80%'></td>
		</tr>
		";
	}
	
	$membro_nome = "membro_".$membro['id'];	
	
	if ($op == 'salvar'){
		$date = $dt_ini;
		while (strtotime($date) <= strtotime($dt_ter)) {
			
			$check_rancor = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = '".$r_tipo."'");
			$num_rancor = $check_rancor->fetch(PDO::FETCH_ASSOC);
			if ($num_rancor[total] > 0) {
				$atual = $PDO->query( "SELECT COUNT(*) AS total FROM raid WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo = '".$n_tipo."'" );
				$num_atual = $atual->fetch(PDO::FETCH_ASSOC);

				$ok = $_POST[$membro_nome."_".$date];
				
				if ($num_atual[total] > 0) {
					$sql2 = "UPDATE `raid` SET 
						valor		 	= '".$ok."', 
						nivel		 	= '".$nivel."' 
						WHERE idmembro 	= '".$membro['id']."' 
						AND data 		= '".$date."'
						AND tipo 		= '".$n_tipo."'
						";
					$acao = "Atualizado";
				} else {
					$sql2 = "INSERT INTO `raid` VALUES (NULL, '".$membro['id']."', '".$date."',  '".$n_tipo."', ".$ok.", '".$nivel."')";
					$acao = "<b>Incluído</b>";
				}
				
				$PDO->query( $sql2 );
			}
			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	}
	
	$x++;
	
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x." - ".$membro['nome']."</td>
		<td>".$membro['telegram']."</td>";
		
	$date = $dt_ini;
	$nenhum = 0;
	while (strtotime($date) <= strtotime($dt_ter)) {
		$check_raid = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = '".$r_tipo."'");
		$num_raid = $check_raid->fetch(PDO::FETCH_ASSOC);
		if ($num_raid[total] > 0) {
			$atual = $PDO->query( "SELECT valor FROM raid WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo = '".$n_tipo."'" );
			$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
			echo "
			<td><input type='text' name='".$membro_nome."_".$date."' value='".$m_atual['valor']."' style='text-align:right;width: 70px;' onClick='this.select();'></td>";
			$nenhum = 1;
		} 

		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	if ($nenhum == 0) echo "<td></td>";
	
	//		<td><input type='text' name='".$membro_nome."' value='".$m_atual['valor']."' style='text-align:right;width: 70px;' onClick='this.select();'></td>
	echo "
	</tr>";
}
echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td colspan= '2'><input type='hidden' name='op' value='salvar'></td>
		<td colspan='".$t_r_dias."'><input type='Submit' value='Salvar'></td>
		</td>
	</tr>
</table>
</form><br><br>";
?>

<script>
document.getElementById("ciclo").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("ciclo");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=<?=$pg;?>&tp=<?=$tp;?>&gd=<?=$gd;?>&ciclo="+x.value
}
</script>