<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d", strtotime("-1 days")); else $dt = $_GET['dt'];

if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

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
		//<br><img src='images/".str_replace(' ', '_', $membro['guilda'])."_m.jpg'><br><br>
		echo "
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='5'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>
		<tr>
			<td colspan='5'>
				<b>Data de Abertura: </b><input type='date' value='".$dt."' id='data'>
				e término: ".date( "d/m/Y", strtotime( $dt." +1 day" ) )." no final da tarde. 
				<input type='button' value='Coletar Dados do Print no C3P0' onclick=\"location.href='".$site."?pg=getprint&gd=".$gd."&dt=".$dt."';\"><br>
				* Deixar desmarcado apenas o campo do tipo de warning que o membro recebeu.
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px'><b>Membro</b></td>
			<td width='30px' style='text-align: center'><b><img src='images/i_600.png'></b></td>
			<td width='30px' style='text-align: center'><b><img src='images/i_fosso.png'></b></td>
			<td width='30px' style='text-align: center'><b><img src='images/i_tanque.png'></b></td>
			<td width='80%'></td>
			</td>
		</tr>
		";
	}
	$membro_nome = "membro_".$membro['id'];
	
	if ($op == 'salvar'){
		// ---------------------------------
		// Atualiza print
		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM print WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
		
		$ok = ($_POST["print_".$membro_nome] > 0) ? 1 : 0;
		if ($_POST["t600ok"] == 1) {$ok = 1;}
		
		if ($num_atual[total] > 0) {
			$sql2 = "UPDATE `print` SET print_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
			$acao = "Atualizado";
		} else {
			$sql2 = "INSERT INTO `print` VALUES (NULL, '".$membro['id']."', '".$dt."', ".$ok.")";
			$acao = "<b>Incluído</b>";
		}
		
		$PDO->query( $sql2 );
		
		
		// ---------------------------------
		// Atualiza fosso
		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM fosso WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
		
		$ok = ($_POST["fosso_".$membro_nome] > 0) ? 1 : 0;
		if ($_POST["semfosso"] == 1) {$ok = 1;}
		
		if ($num_atual[total] > 0) {
			$sql2 = "UPDATE `fosso` SET  fosso_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
			$acao = "Atualizado";
		} else {
			$sql2 = "INSERT INTO `fosso` VALUES (NULL, '".$membro['id']."', '".$dt."', ".$ok.")";
			$acao = "<b>Incluído</b>";
		}
		
		$PDO->query( $sql2 );
		
		
		// ---------------------------------
		// Atualiza tanque
		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM tanque WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
		
		$ok = ($_POST["tanque_".$membro_nome] > 0) ? 1 : 0;
		if ($_POST["semtanque"] == 1) {$ok = 1;}
		
		if ($num_atual[total] > 0) {
			$sql2 = "UPDATE `tanque` SET tanque_ok = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'";
			$acao = "Atualizado";
		} else {
			$sql2 = "INSERT INTO `tanque` VALUES (NULL, '".$membro['id']."', '".$dt."', ".$ok.")";
			$acao = "<b>Incluído</b>";
		}
		
		$PDO->query( $sql2 );
	}
	
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".++$x." - ".$membro['nome']." (".$membro['telegram'].")</td>";

	// Exibe informação atual do print
	$atual = $PDO->query( "SELECT print_ok FROM print WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'" );
	$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
	echo "<td style='text-align: center'><input type='checkbox' name='print_".$membro_nome."' value='".$membro['id']."' ".( $m_atual['print_ok'] == 1 ? "checked" : "")."></td>";
	
	// Exibe informação atual do fosso
	$atual = $PDO->query( "SELECT fosso_ok FROM fosso WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'" );
	$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
	echo "<td style='text-align: center'><input type='checkbox' name='fosso_".$membro_nome."' value='".$membro['id']."' ".( $m_atual['fosso_ok'] == 1 ? "checked" : "")."></td>";
	
	// Exibe informação atual do tanque
	$atual = $PDO->query( "SELECT tanque_ok FROM tanque WHERE idmembro = '".$membro['id']."' AND data = '".$dt."'" );
	$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
	echo "<td style='text-align: center'><input type='checkbox' name='tanque_".$membro_nome."' value='".$membro['id']."' ".( $m_atual['tanque_ok'] == 1 ? "checked" : "")."></td>";

	echo "<td></td></tr>";
}
echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right'> 600 OK p/ todos =></td>
		<td style='text-align: center'><input type='checkbox' name='t600ok' value='1'></td>
		<td style='text-align: center'><!--<input type='checkbox' name='semfosso' value='1'> Sem Fosso--></td>
		<td style='text-align: center'><!--<input type='checkbox' name='semtanque' value='1'> Sem Tanque--></td>
		<td></td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td><input type='hidden' name='op' value='salvar'></td>
		<td><input type='Submit' value='Salvar'></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
</form>";
?>

<script>
document.getElementById("data").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("data");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=print&gd=<?=$gd;?>&dt="+x.value
}
</script>