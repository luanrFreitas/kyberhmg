<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dttb = date("Y-m-d", strtotime("-1 days")); else $dttb = $_GET['dt'];
if (is_null($_GET['tp'])) $tp = "tb"; else $tp = $_GET['tp'];

//if (!isset($_SESSION['dt_ini'])) $_SESSION['dt_ini'] = date("Y-m-d", strtotime("-14 days"));
//if (is_null($_POST['dt_ini'])) $dttb_ini = $_SESSION['dt_ini']; else $dttb_ini = $_POST['dt_ini'];
//if (!isset($_SESSION['dt_ter'])) $_SESSION['dt_ter'] = date("Y-m-d", strtotime("-1 days"));
//if (is_null($_POST['dt_ter'])) $dttb_ter = $_SESSION['dt_ter']; else $dttb_ter = $_POST['dt_ter'];

if(strtotime($dttb) > strtotime("-1 days")) { $dttb = date("Y-m-d", strtotime("-1 days")); }

$dttb = date("Y-m-d", strtotime("now"));

$op = $_POST['op'];



$sqlCiclo = "SELECT * FROM ciclostb ORDER BY inicio DESC LIMIT 10";
$readUser = $PDO->query( $sqlCiclo );
$inicio = 0;
$start = 0;
foreach ($readUser as $rows){
	if (is_null($_GET['ciclo'])) {
		$date1 = new DateTime($rows['inicio']);
		$date2 = new DateTime($rows['termino']);
		$hoje  = new DateTime("now");

		if (($date1 < $hoje AND $date2 > $hoje) OR $inicio == 0) {
			$_GET['ciclo'] = $rows['id'];
			$inicio = 1;
		}
	}
	if ($_GET['ciclo'] ==  $rows['id'] or $start == 0) {
		$dttb_ini = $rows['inicio'];
		$dttb_ter = $rows['termino'];
		$start = 1;
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

//if ($tp == "tb") 	{ $r_tipo = "tb"; $n_tipo = "tb"; }
$r_tipo = "tb"; $n_tipo = "tb";

while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$nivel = $membro['nivel'];
	//echo $nivel;
	$date = $dttb_ini;

	$atual = $PDO->query("SELECT COUNT(*) AS total FROM danotb INNER JOIN membros ON danotb.idmembro = membros.id WHERE idmembro = ".$membro['id']." AND membros.idguilda = '".$gd."' AND data = '".$date."' AND tipo = '".$n_tipo."'");
	$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
	if ($num_atual[total] > 0) {
	} else {
		$sql2 = "INSERT INTO `danotb` VALUES (NULL, '".$membro['id']."', '".$date."',  '".$n_tipo."', 0, 0, 0, 0, 0, 0, 0, 0, 0, '".$membro['nivel']."')";
		$PDO->query( $sql2 );
	}
}

$guilda = 0;

// SALVAR PUNIÇÕES
// ===================================
// id	idmembro	ciclotb	punicao	fase	qtd
	if ($op == 'salvar_punicao'){
		//echo $_POST['membro']." - ".$_POST['warning']." - ".$_POST['fase']." - ".floor($_POST['warning']/10);
		$sql2 = "INSERT INTO `punicao` VALUES (NULL, '".$_POST['membro']."', '".$_GET['ciclo']."',  
			'".$_POST['warning']."', ".$_POST['fase'].", ".floor($_POST['warning']/10).")";
			
		$PDO->query( $sql2 );
	}
// ===================================


// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT membros.id, membros.nome, membros.telegram, membros.idguilda, membros.gpc, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";
	
$result = $PDO->query( $sql );
$x = 0;

while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	if ($membro['idguilda'] <> $guilda) {
		$guilda = $membro['idguilda'];
		echo "
	<table width='100%' cellpadding='3'>
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='21'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>
	</table>
	<table width='100%' cellpadding='3' id='tabela'>
		<tr>
			<form action='' method='post'>
			<td colspan='11'>
				<b>Data: </b> 
				<select name='ciclo' id='ciclo'>".$opcao."</select>
			</td>
			</form>
			<td colspan='10'>
				
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='100px'><b></b></td>
			<!--<td width='100px'><b></b></td>-->
			<td width='100px'><b></b></td>
			<td style='text-align: center; width: 100px;'><b>Território</b></td>
			<td style='text-align: center' width: 100px;'><b>Missão de<br>Pelotão</b></td>
			<td style='text-align: center' width: 100px;'><b>Missão de<br>Combate</b></td>
			<td style='text-align: center' width: 100px;' bgcolor='#B0E0E6' colspan='6'><b>Bônus</b></td>
			<td style='text-align: center' width: 100px;' bgcolor='#FA8072'><b>Punições</b> <span onClick='punicao();'><img src='images/add.png'></span></td>
			<td style='text-align: center' width: 100px;' bgcolor='#FFE4B5' colspan='6'><b>Penalidades</b></td>
			<td width='80%'></td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='100px' rowspan='2'><b>Nick</b></td>
			<!--<td width='100px' rowspan='2'><b>Nome no Telegram</b></td>-->
			<td width='100px' rowspan='2'><b>GP Chars</b></td>
			<td style='text-align: center; width: 100px;' rowspan='2'>Pontos<br>Contribuídos</td>
			<td style='text-align: center' width: 100px;' rowspan='2'>Unidades<br>Atribuídas</td>
			<td style='text-align: center' width: 100px;' rowspan='2'>Ondas<br>Concluídas</td>
			<td style='text-align: center' width: 100px;' rowspan='2'><br>P1</td>
			<td style='text-align: center' width: 100px;' rowspan='2'><br>P2</td>
			<td style='text-align: center' width: 100px;' rowspan='2'><br>P3</td>
			<td style='text-align: center' width: 100px;' rowspan='2'><br>P4</td>
			<td style='text-align: center' width: 100px;' rowspan='2'><br>P5</td>
			<td style='text-align: center' width: 100px;' rowspan='2'><br>P6</td>
			<td style='text-align: center' width: 100px;' rowspan='2'></td>
			<td style='text-align: center' width: 100px;'>CLS</td>
			<td style='text-align: center' width: 100px;' colspan='2'>Soldier</td>
			<td style='text-align: center' width: 100px;' colspan='2'>Scout</td>
			<td style='text-align: center' width: 100px;'>CHan</td>
			<td width='80%' rowspan='2'></td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style='text-align: center' width: 100px;'>6* G10</td>
			<td style='text-align: center' width: 100px;'>3* G7</td>
			<td style='text-align: center' width: 100px;'>5* G8</td>
			<td style='text-align: center' width: 100px;'>4* G8</td>
			<td style='text-align: center' width: 100px;'>6* G8</td>
			<td style='text-align: center' width: 100px;'>5* G8</td>
		</tr>
		<form action='' method='post'>
		";
	}
	
	$membro_nome = "membro_".$membro['id'];	
	
	if ($op == 'salvar'){
		$date = $dttb_ini;
			
		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM danotb WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo = '".$n_tipo."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);

		if ($num_atual[total] > 0) {
			$sql2 = "UPDATE `danotb` SET 
				deploy		 	= '".str_replace(".", "", $_POST[$membro_nome."_deploy"])."', 
				platoon		 	= '".$_POST[$membro_nome."_platoon"]."', 
				waves		 	= '".$_POST[$membro_nome."_waves"]."', 
				w1	 			= '".($_POST[$membro_nome."_w1"] > 0 ? 1 : 0)."',  
				w2	 			= '".($_POST[$membro_nome."_w2"] > 0 ? 1 : 0)."', 
				w3	 			= '".($_POST[$membro_nome."_w3"] > 0 ? 1 : 0)."', 
				w4	 			= '".($_POST[$membro_nome."_w4"] > 0 ? 1 : 0)."', 
				w5	 			= '".($_POST[$membro_nome."_w5"] > 0 ? 1 : 0)."', 
				w6	 			= '".($_POST[$membro_nome."_w6"] > 0 ? 1 : 0)."', 
				nivel		 	= '".$nivel."' 
				WHERE idmembro 	= '".$membro['id']."' 
				AND data 		= '".$date."'
				AND tipo 		= '".$n_tipo."'
				";
			$acao = "Atualizado";
		} else {
			$sql2 = "INSERT INTO `danotb` VALUES (NULL, '".$membro['id']."', '".$date."',  
				'".$n_tipo."', ".$_POST[$membro_nome."_deploy"].", 
				".$_POST[$membro_nome."_platoon"].", ".$_POST[$membro_nome."_waves"].", 
				".($_POST[$membro_nome."_w1"] > 0 ? 1 : 0).", ".($_POST[$membro_nome."_w2"] > 0 ? 1 : 0).", 
				".($_POST[$membro_nome."_w3"] > 0 ? 1 : 0).", ".($_POST[$membro_nome."_w4"] > 0 ? 1 : 0).", 
				".($_POST[$membro_nome."_w5"] > 0 ? 1 : 0).", ".($_POST[$membro_nome."_w6"] > 0 ? 1 : 0).", '".$nivel."')";
			$acao = "<b>Incluído</b>";
		}
		
		$PDO->query( $sql2 );
			
	}
	
	$x++;
	
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x." - ".$membro['nome']."</td>
		<!--<td>".$membro['telegram']."</td>-->
		<td style='text-align: right;'>".number_format($membro['gpc'], 0, ',', '.')."</td>";
		
	$date = $dttb_ini;

	$atual = $PDO->query( "SELECT deploy, platoon, waves, w1, w2, w3, w4, w5, w6 FROM danotb WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo = '".$n_tipo."'" );
	$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
	
	$chars = $PDO->query( "SELECT nome, gear, star1+star2+star3+star4+star5+star6+star7 as star FROM `colecao` WHERE `idmembro` = ".$membro['id']." AND 
		( `nome` LIKE 'Commander Luke Skywalker' or `nome` LIKE 'Hoth Rebel Scout' or `nome` LIKE 'Hoth Rebel Soldier' or `nome` LIKE 'Captain Han Solo')" );
	while ($c_atual = $chars->fetch( PDO::FETCH_ASSOC )) {
		if ($c_atual['nome'] == 'Commander Luke Skywalker') { $cls_gear 	= $c_atual['gear']; 	$cls_star 		= $c_atual['star']; }
		if ($c_atual['nome'] == 'Hoth Rebel Soldier') 		{ $soldier_gear = $c_atual['gear']; 	$soldier_star 	= $c_atual['star']; }
		if ($c_atual['nome'] == 'Hoth Rebel Scout') 		{ $scout_gear 	= $c_atual['gear']; 	$scout_star 	= $c_atual['star']; }
		if ($c_atual['nome'] == 'Captain Han Solo') 		{ $chan_gear 	= $c_atual['gear']; 	$chan_star 		= $c_atual['star']; }
	}

	$lista_membro .= "<option value='".$membro['id']."'>".$membro['nome']."</option>";	
	
	echo "
		<td><input type='text' name='".$membro_nome."_deploy' value='".$m_atual['deploy']."' style='text-align:right;width: 70px;' onClick='this.select();'></td>
		<td><input type='text' name='".$membro_nome."_platoon' value='".$m_atual['platoon']."' style='text-align:right;width: 70px;' onClick='this.select();'></td>
		<td><input type='text' name='".$membro_nome."_waves' value='".$m_atual['waves']."' style='text-align:right;width: 70px;' onClick='this.select();'></td>
		<td style='text-align: center' bgcolor='#B0E0E6'><input type='checkbox' name='".$membro_nome."_w1' value='1' ".( $m_atual['w1'] == 1 ? "checked" : "")."></td>
		<td style='text-align: center' bgcolor='#B0E0E6'><input type='checkbox' name='".$membro_nome."_w2' value='1' ".( $m_atual['w2'] == 1 ? "checked" : "")."></td>
		<td style='text-align: center' bgcolor='#B0E0E6'><input type='checkbox' name='".$membro_nome."_w3' value='1' ".( $m_atual['w3'] == 1 ? "checked" : "")."></td>
		<td style='text-align: center' bgcolor='#B0E0E6'><input type='checkbox' name='".$membro_nome."_w4' value='1' ".( $m_atual['w4'] == 1 ? "checked" : "")."></td>
		<td style='text-align: center' bgcolor='#B0E0E6'><input type='checkbox' name='".$membro_nome."_w5' value='1' ".( $m_atual['w5'] == 1 ? "checked" : "")."></td>
		<td style='text-align: center' bgcolor='#B0E0E6'><input type='checkbox' name='".$membro_nome."_w6' value='1' ".( $m_atual['w6'] == 1 ? "checked" : "")."></td>
		<td style='text-align: left' bgcolor='#FA8072'><span style='cursor: pointer;' onClick=\"punicao(); document.getElementById('membro').value=".$membro['id'].";\"><img src='images/add.png'></span></td>
		<td style='text-align: center' bgcolor='#FFE4B5'>".(($cls_star 	 	>= 6 AND $cls_gear 		>= 10) ? "-" : $cls_star."* G".$cls_gear."</b>")."</td>
		<td style='text-align: center' bgcolor='#FFE4B5'>".(($soldier_star 	>= 3 AND $soldier_gear 	>=  7) ? "-" : $soldier_star."* G".$soldier_gear)."</td>
		<td style='text-align: center' bgcolor='#FFE4B5'>".(($soldier_star 	>= 5 AND $soldier_gear 	>=  8) ? "-" : $soldier_star."* G".$soldier_gear)."</td>
		<td style='text-align: center' bgcolor='#FFE4B5'>".(($scout_star 	>= 4 AND $scout_gear 	>=  8) ? "-" : $scout_star."* G".$scout_gear)."</td>
		<td style='text-align: center' bgcolor='#FFE4B5'>".(($scout_star 	>= 6 AND $scout_gear 	>=  8) ? "-" : $scout_star."* G".$scout_gear)."</td>
		<td style='text-align: center' bgcolor='#FFE4B5'>".(($chan_star 	>= 5 AND $chan_gear 	>=  8) ? "-" : $chan_star."* G".$chan_gear)."</td>
		";

		$penalidades = (($cls_star >= 6 AND $cls_gear >= 10) ? 0 : 1) + (($soldier_star >= 3 AND $soldier_gear >=  7) ? 0 : 1) + 
		(($soldier_star >= 5 AND $soldier_gear >=  8) ? 0 : 1) + (($scout_star >= 4 AND $scout_gear >=  8) ? 0 : 1) + 
		(($scout_star >= 6 AND $scout_gear >=  8) ? 0 : 1) + (($chan_star >= 5 AND $chan_gear >=  8) ? 0 : 1);
		
	echo "
	</tr>";
}

echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td colspan= '2'><input type='hidden' name='op' value='salvar'></td>
		<td colspan='3'><input type='Submit' value='Salvar'></td>
	</tr>
</table>
</form>
<table width='100%' cellpadding='5' id='punicao' style='display: none'>
<form action='' method='post'>
	<tr>
		<td width='50'>
			<b>Membro:</b> 
		</td>
		<td>
			<select name='membro' id='membro'>".$lista_membro."</select>
		</td>
	</tr>
	<tr>
		<td>
			<b>Punição:</b>
		</td>
		<td>
			<input type='radio' name='warning' value='10'>Nível 1 - Combat missions após 23h<br>
			<input type='radio' name='warning' value='11'>Nível 1 - Queimada Fosso ( menos de 1 fase ) ou AAT ( menos que 10% )<br>
			<input type='radio' name='warning' value='20'>Nível 2 - Não seguiu estrategia<br>
			<input type='radio' name='warning' value='21'>Nível 2 - Warning 600<br>
			<input type='radio' name='warning' value='22'>Nível 2 - Queimada Fosso ( 1 fase ou mais ) ou AAT ( mais que 10% )<br>
			<input type='radio' name='warning' value='30'>Nível 3 - Não jogar a TB por 01 dia<br>
		</td>
	</tr>
	<tr>
		<td>
			<b>Fase:</b> 
		</td>
		<td>
			<input type='radio' name='fase' value='1'>1 
			<input type='radio' name='fase' value='2'>2 
			<input type='radio' name='fase' value='3'>3 
			<input type='radio' name='fase' value='4'>4 
			<input type='radio' name='fase' value='5'>5 
			<input type='radio' name='fase' value='6'>6 
		</td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td></td>
		<td>
			<input type='hidden' name='op' value='salvar_punicao'>
			<input type='Submit' value='Salvar'>
			<input type='button' value='Cancelar' onClick='tabela();'>
	</tr>
</form>
</table>
<br><br>";
?>

<script>
document.getElementById("ciclo").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("ciclo");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=<?=$pg;?>&tp=<?=$tp;?>&gd=<?=$gd;?>&ciclo="+x.value
}

function tabela() { 
   document.getElementById("punicao").style.display = 'none';
   document.getElementById("tabela").style.display = '';
}
function punicao() { 
   document.getElementById("punicao").style.display = '';
   document.getElementById("tabela").style.display = 'none';
}
</script>