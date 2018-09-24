<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d", strtotime("-1 days")); else $dt = $_GET['dt'];

if (!isset($_SESSION['dt_ini'])) $_SESSION['dt_ini'] = date("Y-m-d", strtotime("-14 days"));
if (is_null($_POST['dt_ini'])) $dt_ini = $_SESSION['dt_ini']; else $dt_ini = $_POST['dt_ini'];
if (!isset($_SESSION['dt_ter'])) $_SESSION['dt_ter'] = date("Y-m-d", strtotime("-1 days"));
if (is_null($_POST['dt_ter'])) $dt_ter = $_SESSION['dt_ter']; else $dt_ter = $_POST['dt_ter'];

//$data1 = new DateTime( $dt_ini );
//$data2 = new DateTime( $dt_ter );

//$intervalo = $data1->diff( $data2 );
//$intervalo = $intervalo->format('%a');

//if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

if (!is_null($_GET['act'])) {
	$sql2 = "UPDATE `".$_GET['act']."` SET ".$_GET['act']."_ok = '1' WHERE idmembro = '".$_GET['mid']."' AND data = '".$_GET['rdt']."'";
	$PDO->query( $sql2 );
}


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

$_SESSION["dt_ini"] = $dt_ini;
$_SESSION["dt_ter"] = $dt_ter;
$_SESSION["ord"] = $ord;

if(strtotime($dt_ter) > strtotime("-1 days")) { $dt_ter = date("Y-m-d", strtotime("-1 days")); }

$data1 = new DateTime( $dt_ini );
$data2 = new DateTime( $dt_ter );

$intervalo = $data1->diff( $data2 );
$intervalo = $intervalo->format('%a');

$op = $_POST['op'];

//print $num_atual[total]." - ".$usuarioatual[1]."<br>";
$ok = ($_POST[$membro_nome] > 0) ? 1 : 0;

// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT membros.id, membros.nome, membros.idguilda, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";

// Gera os registros caso nenhum exista ainda para o print
$result = $PDO->query( $sql );

while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$atual = $PDO->query("SELECT COUNT(*) AS total FROM print WHERE idmembro = ".$membro['id']." AND data  = '".$dt."'");
	$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
	if ($num_atual[total] > 0) {
		//$sql2 = "UPDATE `print` SET print_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
	} else {
		$sql2 = "INSERT INTO `print` VALUES (NULL, '".$membro['id']."', '".$dt."', 0)";
		$PDO->query( $sql2 );
	}
	
	$atual = $PDO->query("SELECT COUNT(*) AS total FROM fosso WHERE idmembro = ".$membro['id']." AND data  = '".$dt."'");
	$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
	if ($num_atual[total] > 0) {
		//$sql2 = "UPDATE `fosso` SET fosso_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
	} else {
		$sql2 = "INSERT INTO `fosso` VALUES (NULL, '".$membro['id']."', '".$dt."', 1)";
		$PDO->query( $sql2 );
	}
	
	$atual = $PDO->query("SELECT COUNT(*) AS total FROM tanque WHERE idmembro = ".$membro['id']." AND data  = '".$dt."'");
	$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
	if ($num_atual[total] > 0) {
		//$sql2 = "UPDATE `tanque` SET tanque_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
	} else {
		$sql2 = "INSERT INTO `tanque` VALUES (NULL, '".$membro['id']."', '".$dt."', 1)";
		$PDO->query( $sql2 );
	}
}

$guilda = 0;

// Busca os dados para exibição
$sql = "SELECT print.*, membros.id, membros.nome, membros.idguilda, membros.telegram, guildas.nome AS guilda, fosso.id AS id_fosso, fosso.fosso_ok, tanque.id AS id_tanque, tanque.tanque_ok
	FROM print
	INNER JOIN fosso ON fosso.idmembro = print.idmembro AND fosso.data = print.data 
	INNER JOIN tanque ON tanque.idmembro = print.idmembro AND tanque.data = print.data 
	INNER JOIN membros ON membros.id = print.idmembro
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda 		= ".$gd."
	AND print.data 				= '".$dt."'
	ORDER BY membros.nome
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
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='".(4+$intervalo)."'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>
		<form action='' method='post'>
		<tr>
			<td colspan='".(4+$intervalo)."'>
				<b>Ciclo: </b> 
				<select name='ciclo' id='ciclo'>".$opcao;
/*
		echo "
				Início <input type='date' value='".$dt_ini."' name='dt_ini'>
				e Término <input type='date' value='".$dt_ter."' name='dt_ter'>
				<input type='submit' value='Filtrar'>
			";
*/
		echo "	</select>
			</td>
		</tr>
		</form>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px'><b>Nomes</b></td>
		";
			for ($d = 0; $d <= $intervalo; $d++) {
				$dia = "+".$d." days";
				$dia = date('d/m', strtotime( $dia, strtotime($dt_ini)));
				echo "<td style='text-align: center;'><b>".$dia."</b></td>";
			}
		echo "
			<td style='text-align: center;'><b>Warning</b></td>
			<td width='80%' style='text-align: center;'><b></b></td>
		</tr>
		";
	}
	$membro_nome = "membro_".$membro['id'];
	
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".++$x." - ".$membro['nome']." (".$membro['telegram'].")</td>";

	$total = 0;
	for ($d = 0; $d <= $intervalo; $d++) {
		$dia = "+".$d." days";
		$dia = date('Y-m-d', strtotime( $dia, strtotime($dt_ini)));
		
		// dados do print 600
		$sql_print = "SELECT print.* FROM print WHERE idmembro = ".$membro['id']." AND data = '".$dia."' ";
		$result_print = $PDO->query( $sql_print );
		$membro_print = $result_print->fetch(PDO::FETCH_ASSOC);
		if ($membro_print['id'] == 0) {
			$PDO->query( "INSERT INTO `print` VALUES (NULL, '".$membro['id']."', '".$dia."', 0)" );
			$result_print = $PDO->query( $sql_print );
			$membro_print = $result_print->fetch(PDO::FETCH_ASSOC);
		}

		// dados do print fosso
		$sql_fosso = "SELECT fosso.* FROM fosso WHERE idmembro = ".$membro['id']." AND data = '".$dia."'";
		$result_fosso = $PDO->query( $sql_fosso );
		$membro_fosso = $result_fosso->fetch(PDO::FETCH_ASSOC);
		if ($membro_fosso['id'] == 0) {
			$PDO->query( "INSERT INTO `fosso` VALUES (NULL, '".$membro['id']."', '".$dia."', 1)" );
			$result_fosso = $PDO->query( $sql_fosso );
			$membro_fosso = $result_fosso->fetch(PDO::FETCH_ASSOC);
		}

		// dados do print tanque
		$sql_tanque = "SELECT tanque.* FROM tanque WHERE idmembro = ".$membro['id']." AND data = '".$dia."'";
		$result_tanque = $PDO->query( $sql_tanque );
		$membro_tanque = $result_tanque->fetch(PDO::FETCH_ASSOC);
		if ($membro_tanque['id'] == 0) {
			$PDO->query( "INSERT INTO `tanque` VALUES (NULL, '".$membro['id']."', '".$dia."', 1)" );
			$result_tanque = $PDO->query( $sql_tanque );
			$membro_tanque = $result_print->fetch(PDO::FETCH_ASSOC);
		}

		echo "<td style='text-align: center;'>";
		echo ($membro_print['print_ok']   == 0 ? "<a href=\"#\" onClick='javascript:if (confirm(\"Remover este warning?\")){location.href=\"?pg=".$pg."&tp=600&gd=".$gd."&act=print&mid=".$membro['id']."&rdt=".$dia."\"}'><img src='images/p_600.png'></a>&nbsp;" : '' ) ;
		echo ($membro_fosso['fosso_ok']   == 0 ? "<a href=\"#\" onClick='javascript:if (confirm(\"Remover este warning?\")){location.href=\"?pg=".$pg."&tp=600&gd=".$gd."&act=fosso&mid=".$membro['id']."&rdt=".$dia."\"}'><img src='images/p_fosso.png'></a>&nbsp;" : '' ) ;
		echo ($membro_tanque['tanque_ok'] == 0 ? "<a href=\"#\" onClick='javascript:if (confirm(\"Remover este warning?\")){location.href=\"?pg=".$pg."&tp=600&gd=".$gd."&act=tanque&mid=".$membro['id']."&rdt=".$dia."\"}'><img src='images/p_tanque.png'></a>" : '' ) ;
		echo (($membro_print['print_ok']  == 1 AND $membro_print['fosso_ok'] == 1 AND $membro_print['tanque_ok'] == 1) ? "<img src='images/p_ok.png'>" : '' ) ;
		echo "</td>";
		$total += $membro_print['print_ok'];
		$total += $membro_fosso['fosso_ok'];
		$total += $membro_tanque['tanque_ok'];
	}

	$int_dias = 3*(1+$intervalo);
	echo "
		<td style='text-align: center;'>"
			.($int_dias-$total == 0 ? "" : $int_dias-$total)
			.($int_dias-$total == 1 ? " <img src='images/warning.png'>" : "")
			.($int_dias-$total > 1  ? " <img src='images/stop.png'>" : "")
			."
		</td>
		<td></td>
	</tr>";
}
echo "
</table>
</form>";
?>

<script>
document.getElementById("ciclo").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("ciclo");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=<?=$pg;?>&gd=<?=$gd;?>&ciclo="+x.value
}
</script>