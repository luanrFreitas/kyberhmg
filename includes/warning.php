<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d", strtotime("-1 days")); else $dt = $_GET['dt'];

if (!isset($_SESSION['dt_ini'])) $_SESSION['dt_ini'] = date("Y-m-d", strtotime("-14 days"));
if (is_null($_POST['dt_ini'])) $dt_ini = $_SESSION['dt_ini']; else $dt_ini = $_POST['dt_ini'];
if (!isset($_SESSION['dt_ter'])) $_SESSION['dt_ter'] = date("Y-m-d", strtotime("-1 days"));
if (is_null($_POST['dt_ter'])) $dt_ter = $_SESSION['dt_ter']; else $dt_ter = $_POST['dt_ter'];

if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

$dt = date("Y-m-d", strtotime("-1 days"));

$opt = $_POST['opt'];

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

$ini = 0;
$p_c3p0 = "";
$p_dias = ""; $t_p_dias = 0;
$r_dias = ""; $t_r_dias = 0;
$t_dias = ""; $t_t_dias = 0;
$b_dias = ""; $t_b_dias = 0;

while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$date = $dt_ini;
	while (strtotime($date) <= strtotime($dt_ter)) {
		
		$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'print'");
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
		if ($num_atual[total] > 0) {
		} else {
			$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'print', 1)";
			$PDO->query( $sql2 );
		}
		if ($ini == 0) {
			$p_c3p0 .= "<td style='text-align: center'><a href=\"#\" onClick='javascript:if (confirm(\"Coletar Dados do Print no C3P0 no dia ".date("d/m", strtotime($date))."?\")){location.href=\"".$site."?pg=getprint2&gd=".$gd."&dt=".$date."\"}'><img src='images/c3p0.ico' height='16' width='16' alt='Atualizar Print' title='Atualizar Print'></a></td>";
			$p_dias .= "<td style='text-align: center'><b>".date("d", strtotime($date))."</b></td>";
			$t_p_dias += 1;
		}
		
		$check_rancor = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'rancor'");
		$num_rancor = $check_rancor->fetch(PDO::FETCH_ASSOC);
		if ($num_rancor[total] > 0) {
			$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'fosso'");
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			if ($num_atual[total] > 0) {
			} else {
				$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'fosso', 0)";
				$PDO->query( $sql2 );
			}
			if ($ini == 0) {
				$r_dias .= "<td style='text-align: center'><b>".date("d", strtotime($date))."</b></td>";
				$t_r_dias += 1;
			}
		}
		
		$check_tanque = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'tanque'");
		$num_tanque = $check_tanque->fetch(PDO::FETCH_ASSOC);
		if ($num_tanque[total] > 0) {
			$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'tanque'");
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			if ($num_atual[total] > 0) {
			} else {
				$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'tanque', 0)";
				$PDO->query( $sql2 );
			}
			if ($ini == 0) {
				$t_dias .= "<td style='text-align: center'><b>".date("d", strtotime($date))."</b></td>";
				$t_t_dias += 1;
			}
		}
		
		$check_bonus = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'bonus'");
		$num_bonus = $check_bonus->fetch(PDO::FETCH_ASSOC);
		if ($num_bonus[total] > 0) {
			$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'bonus'");
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			if ($num_atual[total] > 0) {
			} else {
				$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'bonus', 0)";
				$PDO->query( $sql2 );
			}
			if ($ini == 0) {
				$b_dias .= "<td style='text-align: center'><b>".date("d", strtotime($date))."</b></td>";
				$t_b_dias += 1;
			}
		}
		
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	$ini += 1;
}

$guilda = 0;

// Busca os dados para exibição
$sql = "SELECT warning.*, membros.id, membros.nome, membros.idguilda, membros.telegram, guildas.nome AS guilda
	FROM warning
	INNER JOIN membros ON membros.id = warning.idmembro
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda 		= ".$gd."
	AND warning.data 				= '".$dt."'
	AND warning.tipo 				= 'print'
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
		//<br><img src='images/".str_replace(' ', '_', $membro['guilda'])."_m.jpg'><br><br>
		echo "
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='".(9+$t_p_dias+$t_r_dias+$t_t_dias+$t_b_dias)."'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>

		<form action='' method='post'>
		<tr>
			<td colspan='".(9+$t_p_dias+$t_r_dias+$t_t_dias+$t_b_dias)."'>
				<b>Ciclo: </b> 
				<select name='ciclo' id='ciclo'>".$opcao."</select>
			</td>
		</tr>
		</form>
		<form action='' method='post'>
		";

		echo "
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px' colspan='2'></td>
			<td style='text-align: center' colspan='".$t_p_dias."'><b><img src='images/i_600.png'></b></td>
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			<td style='text-align: center' colspan='".$t_r_dias."'><b><img src='images/i_fosso.png'></b></td>
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			<td style='text-align: center' colspan='".$t_t_dias."'><b><img src='images/i_tanque.png'></b></td>
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			<td style='text-align: center' colspan='".$t_b_dias."'><b><img src='images/i_bonus.png'></b></td>
			<td></td>
			<td width='80%'></td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px' colspan='2'><b>Membro</b></td>
			".$p_c3p0."
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			<td style='text-align: center' colspan='".$t_r_dias."'></td>
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			<td style='text-align: center' colspan='".$t_t_dias."'></td>
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			<td style='text-align: center' colspan='".$t_b_dias."'></td>
			<td></td>
			<td></td>
		</tr>
		";
		
		$tit = "
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='100px'><b>Nick</b></td>
			<td width='100px'><b>Nome no Telegram</b></td>
			".($p_dias == "" ? "<td></td>" : $p_dias)."
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			".($r_dias == "" ? "<td></td>" : $r_dias)."
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			".($t_dias == "" ? "<td></td>" : $t_dias)."
			<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>
			".($b_dias == "" ? "<td></td>" : $b_dias)."
			<td></td>
			<td width='80%'></td>
		</tr>
		";
	}
	$membro_nome = $membro['id'];
	
	if ($opt == 'salvar'){
		// ---------------------------------
		// Atualiza print
		$date = $dt_ini;
		while (strtotime($date) <= strtotime($dt_ter)) {
			$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'print'");
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			
			$ok = ($_POST["print_".$membro_nome."_".$date] > 0) ? 1 : 0;
			if ($_POST["t600ok_".$date] == 1) {$ok = 0;}
			
			if ($num_atual[total] > 0) {
				$sql2 = "UPDATE `warning` SET qtd = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'print'";
				$acao = "Atualizado";
			} else {
				$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'print', ".$ok.")";
				$acao = "<b>Incluído</b>";
			}
			
			$PDO->query( $sql2 );
			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	
		// ---------------------------------
		// Atualiza fosso
		$date = $dt_ini;
		while (strtotime($date) <= strtotime($dt_ter)) {
			$check_rancor = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'rancor'");
			$num_rancor = $check_rancor->fetch(PDO::FETCH_ASSOC);
			if ($num_rancor[total] > 0) {

				$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'fosso'");
				$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
				
				$ok = $_POST["fosso_".$membro_nome."_".$date];
				
				if ($num_atual[total] > 0) {
					$sql2 = "UPDATE `warning` SET qtd = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'fosso'";
					$acao = "Atualizado";
				} else {
					$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'fosso', ".$ok.")";
					$acao = "<b>Incluído</b>";
				}
				
				$PDO->query( $sql2 );
			}
			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		
		// ---------------------------------
		// Atualiza tanque
		$date = $dt_ini;
		while (strtotime($date) <= strtotime($dt_ter)) {
			$check_rancor = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'tanque'");
			$num_rancor = $check_rancor->fetch(PDO::FETCH_ASSOC);
			if ($num_rancor[total] > 0) {

				$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'tanque'");
				$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
				
				$ok = $_POST["tanque_".$membro_nome."_".$date];
				
				if ($num_atual[total] > 0) {
					$sql2 = "UPDATE `warning` SET qtd = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'tanque'";
					$acao = "Atualizado";
				} else {
					$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'tanque', ".$ok.")";
					$acao = "<b>Incluído</b>";
				}
				
				$PDO->query( $sql2 );
			}
			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	
		// ---------------------------------
		// Atualiza bonus
		$date = $dt_ini;
		while (strtotime($date) <= strtotime($dt_ter)) {
			$check_bonus = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'bonus'");
			$num_bonus = $check_bonus->fetch(PDO::FETCH_ASSOC);
			if ($num_bonus[total] > 0) {

				$atual = $PDO->query("SELECT COUNT(*) AS total FROM warning WHERE idmembro = ".$membro['id']." AND data  = '".$date."' AND tipo  = 'bonus'");
				$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
				
				$ok = $_POST["bonus_".$membro_nome."_".$date];
				
				if ($num_atual[total] > 0) {
					$sql2 = "UPDATE `warning` SET qtd = '".$ok."' WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'bonus'";
					$acao = "Atualizado";
				} else {
					$sql2 = "INSERT INTO `warning` VALUES (NULL, '".$membro['id']."', '".$date."', 'bonus', ".$ok.")";
					$acao = "<b>Incluído</b>";
				}
				
				$PDO->query( $sql2 );
			}
			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	
	}
		
	if ($x%10 == 0) echo $tit;
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".++$x." - ".$membro['nome']."</td>
		<td>".$membro['telegram']."</td>";


	$date = $dt_ini;
	$warning = 0;
	while (strtotime($date) <= strtotime($dt_ter)) {
		// Exibe informação atual do print
		$atual = $PDO->query( "SELECT qtd FROM warning WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'print'" );
		$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
		echo "<td style='text-align: center'><input type='checkbox' name='print_".$membro_nome."_".$date."' value='".$membro['id']."' ".( $m_atual['qtd'] == 1 ? "checked" : "")."></td>";

		if (strtotime($date) <= strtotime("-2 day")) $warning += $m_atual['qtd'];
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}

	echo "<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>";
			
	$date = $dt_ini;
	$nenhum = 0;
	while (strtotime($date) <= strtotime($dt_ter)) {
		// Exibe informação atual do fosso
		$check_rancor = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'rancor'");
		$num_rancor = $check_rancor->fetch(PDO::FETCH_ASSOC);
		if ($num_rancor[total] > 0) {
			$atual = $PDO->query( "SELECT qtd FROM warning WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'fosso'" );
			$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
			echo "<td style='text-align: center'>
				<select name='fosso_".$membro_nome."_".$date."' id='fosso_".$membro_nome."_".$date."'>
					<option value='0' ".(($m_atual['qtd'] ==  0) ? "selected" : "").">0</option>
					<option value='1' ".(($m_atual['qtd'] ==  1) ? "selected" : "").">1</option>
					<option value='2' ".(($m_atual['qtd'] ==  2) ? "selected" : "").">2</option>
				</select>
				</td>
				";
			$nenhum = 1;
			$warning += $m_atual['qtd'];
		} 

		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	if ($nenhum == 0) echo "<td></td>";
		
	echo "<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>";

	$date = $dt_ini;
	$nenhum = 0;
	while (strtotime($date) <= strtotime($dt_ter)) {
		// Exibe informação atual do tanque
		$check_tanque = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'tanque'");
		$num_tanque = $check_tanque->fetch(PDO::FETCH_ASSOC);
		if ($num_tanque[total] > 0) {
			$atual = $PDO->query( "SELECT qtd FROM warning WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'tanque'" );
			$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
			echo "<td style='text-align: center'>
				<select name='tanque_".$membro_nome."_".$date."' id='tanque_".$membro_nome."_".$date."'>
					<option value='0' ".(($m_atual['qtd'] ==  0) ? "selected" : "").">0</option>
					<option value='1' ".(($m_atual['qtd'] ==  1) ? "selected" : "").">1</option>
					<option value='2' ".(($m_atual['qtd'] ==  2) ? "selected" : "").">2</option>
				</select>
				</td>
				";
			$nenhum = 1;
			$warning += $m_atual['qtd'];			
		}
		
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	if ($nenhum == 0) echo "<td></td>";
		
	echo "<td style='text-align: center'>&nbsp;&nbsp;&nbsp;</td>";

	$date = $dt_ini;
	$nenhum = 0;
	while (strtotime($date) <= strtotime($dt_ter)) {
		// Exibe informação atual do bonus
		$check_bonus = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = 'bonus'");
		$num_bonus = $check_bonus->fetch(PDO::FETCH_ASSOC);
		if ($num_bonus[total] > 0) {
			$atual = $PDO->query( "SELECT qtd FROM warning WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'bonus'" );
			$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
			echo "<td style='text-align: center'>
				<select name='bonus_".$membro_nome."_".$date."' id='tanque_".$membro_nome."_".$date."'>
					<option value='0' ".(($m_atual['qtd'] ==  0) ? "selected" : "").">0</option>
					<option value='-1' ".(($m_atual['qtd'] ==  -1) ? "selected" : "").">1</option>
					<option value='-2' ".(($m_atual['qtd'] ==  -2) ? "selected" : "").">2</option>
				</select>
				</td>
				";
			$nenhum = 1;
		}
		
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	if ($nenhum == 0) echo "<td></td>";

	echo "<td>".$warning
		.($warning >= 2 ? "&nbsp;<img src='images/rancor.ico' height='16' width='16' alt='Enviar Aviso de Punição - Rancor' title='Enviar Aviso de Punição - Rancor'>": "")
		.($warning >= 3 ? "&nbsp;<img src='images/aat.ico' height='16' width='16' alt='Enviar Aviso de Punição - AAT' title='Enviar Aviso de Punição - AAT'>": "")
		."</td>
		<td></td></tr>";
}
echo $tit."
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right' colspan='2'> 600 OK p/ todos =></td>";

	$date = $dt_ini;
	while (strtotime($date) <= strtotime($dt_ter)) {
		// Exibe informação atual do print
		$atual = $PDO->query( "SELECT qtd FROM warning WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo  = 'print'" );
		$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
		echo "<td style='text-align: center'><input type='checkbox' name='t600ok_".$date."' value='1'></td>";

		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
		
echo "
		<td style='text-align: center'><!--<input type='checkbox' name='semfosso' value='1'> Sem Fosso--></td>
		<td style='text-align: center'><!--<input type='checkbox' name='semtanque' value='1'> Sem Tanque--></td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td><input type='hidden' name='opt' value='salvar' colspan='2'></td>
		<td><input type='Submit' value='Salvar' colspan='3'></td>
	</tr>
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