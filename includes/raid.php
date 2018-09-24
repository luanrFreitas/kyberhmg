<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d"); else $dt = $_GET['dt'];
if (is_null($_GET['tp'])) $tp = "fosso"; else $tp = $_GET['tp'];

if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

$op = $_POST['op'];

$sql = "SELECT COUNT(*) AS total 
	FROM raid 
	INNER JOIN membros ON raid.idmembro = membros.id
	WHERE membros.idguilda 	= '".$gd."'
	AND data 				= '".$dt."'
	AND tipo 				= '".$tp."'
	";
$atual = $PDO->query( $sql );
$num_atual = $atual->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT DISTINCT membros.id, membros.nome, membros.idguilda, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";

$result = $PDO->query( $sql );
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$atual = $PDO->query("SELECT COUNT(*) AS total FROM raid INNER JOIN membros ON raid.idmembro = membros.id WHERE idmembro = ".$membro['id']." AND membros.idguilda = '".$gd."' AND data = '".$dt."' AND tipo = '".$tp."'");
	$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
	if ($num_atual[total] > 0) {
		//$sql2 = "UPDATE `print` SET print_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
	} else {
		$sql2 = "INSERT INTO `raid` VALUES (NULL, '".$membro['id']."', '".$dt."',  '".$tp."', 0)";
		$PDO->query( $sql2 );
	}
}

$guilda = 0;

$sql = "SELECT raid.*, membros.id, membros.nome, membros.idguilda, membros.telegram, guildas.nome AS guilda
	FROM raid
	INNER JOIN membros ON membros.id = raid.idmembro
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda 	= ".$gd."
	AND data 				= '".$dt."'
	AND tipo 				= '".$tp."'
	ORDER BY membros.nome
	";
	
$result = $PDO->query( $sql );
$x = 0;

echo "
	<form action='' method='post'>
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
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='5'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<input type='date' value='".$dt."' id='data'>
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px'><b>Membro</b></td>
			<td width='80%'><b>Contribuição Total</b></td>
			</td>
		</tr>
		";
	}
	$membro_nome = "membro_".$membro['id'];
	$valor = ($_POST[$membro_nome] > 0) ? $_POST[$membro_nome] : 0;
	
	if ($op == 'salvar'){
		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM raid WHERE idmembro = '".$membro['id']."' AND data = '".$dt."' AND tipo = '".$tp."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
		
		//print $num_atual[total]." - ".$usuarioatual[1]."<br>";
		
		if ($num_atual[total] > 0) {
			$sql2 = "UPDATE `raid` SET 
				valor		 	= '".$valor."' 
				WHERE idmembro 	= '".$membro['id']."' 
				AND data 		= '".$dt."'
				AND tipo 		= '".$tp."'
				";
			$acao = "Atualizado";
		} else {
			$sql2 = "INSERT INTO `raid` VALUES (NULL, '".$membro['id']."', '".$dt."',  '".$tp."', ".$valor.")";
			$acao = "<b>Incluído</b>";
		}
		
		//print $acao ." - ". $membro_nome . " - " . $_POST[$membro_nome] . " - " . $dt . " | <br>";
		$PDO->query( $sql2 );
	}
	
	$atual = $PDO->query( "SELECT valor FROM raid WHERE idmembro = '".$membro['id']."' AND data = '".$dt."' AND tipo = '".$tp."'" );
	$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
	$x++;
	
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x." - ".$membro['nome']." (".$membro['telegram'].")</td>
		<td><input type='text' name='".$membro_nome."' value='".$m_atual['valor']."' style='text-align:right;' onClick='this.select();'></td>
	</tr>";
}
echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td width='200px'><input type='hidden' name='op' value='salvar'></td>
		<td width='90%'><input type='Submit' value='Salvar'></td>
		</td>
	</tr>
</table>
</form>";
?>

<script>
document.getElementById("data").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("data");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=raid&tp=<?=$tp;?>&gd=<?=$gd;?>&dt="+x.value
}

</script>