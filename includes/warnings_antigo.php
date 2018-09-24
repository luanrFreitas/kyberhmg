<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Warnings</h1>
							<h2><span>Rotação | Punições</span></h2>
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
			<div class="row" style="overflow-x: auto;">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Planilha</h2>
					<p>
<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dttb = date("Y-m-d", strtotime("-1 days")); else $dttb = $_GET['dt'];
if (is_null($_GET['tp'])) $tp = "tb"; else $tp = $_GET['tp'];

require 'includes/rank_dados.php'; 

$tp_warning[10] = 'Nível 1 - Queima parcial de fase em qualquer Raid';
$tp_warning[11] = 'Nível 1 - 600: Não concluir totalmente';
$tp_warning[12] = 'Nível 1 - TW: Não Entrar';
$tp_warning[13] = 'Nível 1 - TB/TW: Descrumprir Orientações';
$tp_warning[14] = 'Nível 1 - Raid Sith (Normal): Sem dano após 24hs da abertura';
$tp_warning[15] = 'Nível 1 - Raid Sith (Heróica): Menos de 500k de dano ao final';
$tp_warning[16] = 'Nível 1 - Raid Sith (Heróica): Nenhum ataque até o início da P4';
$tp_warning[20] = 'Nível 2 - Queimar 1 ou + fases em qualquer Raid';
$tp_warning[21] = 'Nível 2 - TW: Entra e não participar';
$tp_warning[22] = 'Nível 2 - 600: Contribuição zero';
$tp_warning[30] = 'Nível 3 - Raid Sith: Dano zero no final';

if(strtotime($dttb) > strtotime("-1 days")) { $dttb = date("Y-m-d", strtotime("-1 days")); }

$dttb = date("Y-m-d", strtotime("now"));

$op = $_POST['op'];

$sqlCiclo = "SELECT * FROM ciclos ORDER BY inicio DESC LIMIT 10";
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
	if ($_GET['ciclo'] == $rows['id']) $status = $rows['status'];
	$opcao .= "<option value='".$rows['id']."' ".(($_GET['ciclo'] == $rows['id']) ? "selected" : "").">De ".date("d/m/Y", strtotime($rows['inicio']))." a ".date("d/m/Y", strtotime($rows['termino']))."</option>";
}

// ===================================
// EXCLUIR PUNIÇÕES
// ===================================
// id	idmembro	ciclotb	punicao	fase	qtd
if ($_GET['op'] > 0){
	$sql2 = "DELETE FROM `punicao` WHERE `punicao`.`id` = ".$_GET['op'];
		
	$PDO->query( $sql2 );
	
	//echo '<span class="ms ok">Pronto! Punição REMOVIDA com sucesso!</span>';
	//echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?pg=warnings&gd=".$gd."&ciclo=".$_GET['ciclo']."'>";
}
// ===================================

// ===================================
// SALVAR PUNIÇÕES
// ===================================
// id	idmembro	ciclotb	punicao	fase	qtd
if ($op == 'salvar_punicao'){
	//echo $_POST['membro']." - ".$_POST['warning']." - ".$_POST['fase']." - ".floor($_POST['warning']/10);
	$sql2 = "INSERT INTO `punicao` VALUES (NULL, '".$_POST['membro']."', '".$_GET['ciclo']."',  
		'".$_POST['warning']."',  '".$_POST['data']."', ".floor($_POST['warning']/10).")";
		
	$PDO->query( $sql2 );
	
	//echo '<span class="ms ok">Pronto! Punição REGISTRADA com sucesso!</span>';
	//echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?pg=warnings&gd=".$gd."&ciclo=".$_GET['ciclo']."'>";
}
// ===================================

$guilda = 0;

// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT units.url, units.player, units.guilda, guildas.nome, guildas.tipo
	FROM units 
	INNER JOIN guildas ON guildas.link LIKE CONCAT('%',units.guilda,'%')
	WHERE units.guilda = ".$gd." AND units.url != ''
	ORDER BY units.guilda, units.player
	";
	
$result = $PDO->query( $sql );
$x = 0;

while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	if ($membro['guilda'] <> $guilda) {
		$guilda = $membro['guilda'];
		?>

	<table width='100%' cellpadding='3' width='600' class='table table-hover table-striped table-fixed'>
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; font-weight: bold;'>
				<br><?=ucfirst($membro['nome']);?><br><br>
			</td>
		</tr>
	</table>
	
	<table width='100%' cellpadding='3' id='tabela' class='table table-hover table-striped table-fixed'>
		<tr>
			<form action='' method='post'>
			<td colspan='7'>
				<b>Data: </b> 
				<select name='ciclo' id='ciclo'><?=$opcao;?></select>
			</td>
			</form>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style='width: 20px;'></td>
			<td style='width: 160px;'><b>Player</b></td>
			<td style='width: 60px;'><b>Score<br>Final</b></td>
			<td style='width: 60px;'><b>% dos<br>Times</b></td>
			<td style='width: 40px;'><b>Qtd.<br>Pun.</b></td>
			<td style='width: 240px;'><b>Punições</b></td>
			<td style='width: 20px;'></td>
		</tr>
<?php
	}

	$peso = 0;
	$final[$i] = 0;
	for ($o = 0; $o <= count($times)-1; $o++) {
		$peso += $times[$o]['peso'];
		$time[$o] = 0;
		$item = "";
		for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) {
			for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
				$c_atual = $times[$o]['chars'][$u][$a];
				if ($rank2[$membro['url']][$c_atual['nome']]['base_id'] > 0) {
					for ($z = 0; $z < count($c_atual['zeta']); $z++) {
					$atual = $PDO->query( "SELECT COUNT(*) AS total FROM `zetas` 
									LEFT JOIN `abilities` ON `abilities`.`base_id` = `zetas`.`zeta` 
									WHERE `zetas`.`player` = '".str_replace("'", "\'", $rank2[$membro['url']]['player'])."' 
									AND `abilities`.`name` = '".str_replace("'", "\'", $c_atual['zeta'][$z])."'" );
						$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
						if ($num_atual['total'] == 0) {
							if ($rank2[$membro['url']][$c_atual['nome']]['jafoi'] == 0) {
								$reducao++;
								if ($z == (count($c_atual['zeta'])-1)) $rank2[$membro['url']][$c_atual['nome']]['jafoi'] = 1;
							}
						}
					}
					if ($reducao > 0)$rank2[$membro['url']][$c_atual]['base_id'] = $rank2[$membro['url']][$c_atual]['base_id']*((100-$reducao*25)/100);
				}
				if (isset($c_atual['unico'])) {
					if ($c_atual['unico']['extra'] == "principal") {
						if ($rank2[$membro['url']][$c_atual['nome']]['rarity'] < 7) $time_final = 1;
					}
					if (isset($c_atual['unico']['campo'])) {
						$rank2[$membro['url']][$c_atual['nome']]['base_id'] = $rank2[$membro['url']][$c_atual['nome']][$c_atual['unico']['campo']] == $c_atual['unico']['max'] ? 100 : 0;
					}
				}				
			}
			
			for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
				if ($rank2[$membro['url']][$times[$o]['chars'][$u][$a]['nome']]['base_id'] > 0) {
					$c_atual = $times[$o]['chars'][$u][$a]['nome'];
					for ($z = 0; $z < count($times[$o]['chars'][$u][$a]['zeta']); $z++) {
						$atual = $PDO->query( "SELECT COUNT(*) AS total FROM `zetas` 
									LEFT JOIN `abilities` ON `abilities`.`base_id` = `zetas`.`zeta` 
									WHERE `zetas`.`player` = '".str_replace("'", "\'", $rank2[$membro['url']]['player'])."' 
									AND `abilities`.`name` = '".str_replace("'", "\'", $times[$o]['chars'][$u][$a]['zeta'][$z])."'" );
						$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
						if ($num_atual['total'] == 0) 
							$rank2[$membro['url']][$c_atual]['base_id'] = ($rank2[$membro['url']][$c_atual]['base_id'] > 5 ? $rank2[$membro['url']][$c_atual]['base_id']-25 : 0);
						if (isset($times[$o]['chars'][$u][$a]['unico']))
							$rank[$i][$c_atual['nome']]['base_id'] = $rank[$i][$c_atual['unico']] == $c_atual['max'] ? 100 : 0;
						}

					if ($rank2[$membro['url']][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank2[$membro['url']][$times[$o]['chars'][$u][1]['nome']]['base_id'])
						if ($rank2[$membro['url']][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank2[$membro['url']][$times[$o]['chars'][$u][2]['nome']]['base_id'])
							$ver = 0; else $ver = 2;
					else
						if ($rank2[$membro['url']][$times[$o]['chars'][$u][1]['nome']]['base_id'] >= $rank2[$membro['url']][$times[$o]['chars'][$u][2]['nome']]['base_id']) 
							$ver = 1; else $ver = 2;

					if ($a == $ver) $time[$o]  += $rank2[$membro['url']][$c_atual]['base_id'];
				}
			}
		}
		$final[$i] += ($time[$o] / 5) * $times[$o]['peso'];
	}
	$final[$i] = $final[$i]/$peso;
	
	$x++;
	$membro_nome = str_replace("/collection/", "", str_replace("/u/", "", $membro['url']));	
	$lista_membro .= "<option value='".$membro_nome."'>".$membro['player']."</option>";	
	
	$war_sql = "SELECT * FROM `punicao` WHERE idmembro='".$membro_nome."' AND ciclotb='".$_GET['ciclo']."' ORDER BY punicao";
	$war_result = $PDO->query( $war_sql );
	$warning = "";
	$warning_t = 0;
	while ($war = $war_result->fetch( PDO::FETCH_ASSOC )) {
		$warning .= $status == 1 ? "<span style='cursor: pointer;' onClick=\"location.href='".$site."?pg=".$pg."&gd=".$gd."&ciclo=".$_GET['ciclo']."&op=".$war['id']."'\"><img src='images/del.png'></span> " : "";
		$warning .= $tp_warning[$war['punicao']]." (".date("d/m", strtotime($war['data'])).")<BR>";
		$warning_t += $war['qtd'];
	}

	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x."</td>
		<td style='text-align: left;'>".$membro['player']."</td>
		<td>".formatMoney(($final[$i]-3*$warning_t), 2)."</td>
		<td>".formatMoney($final[$i], 2)."</td>
		<td>".$warning_t."</td>
		<td style='text-align: left; white-space:normal;'>".$warning."</td>
		<td>".($status == 1 ? "<span style='cursor: pointer;' onClick=\"punicao(); document.getElementById('membro').value='".$membro_nome."';\"><img src='images/plus.ico'></span>" : "")."</td>
	</tr>";
	
}

function formatMoney($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0,00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0), ',', ''); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), ',', ''); // format
      } // integer or decimal
    } // value
    return $money;
  } // numeric
} // formatMoney
?>
</table>

<table width='100%' cellpadding='5' id='punicao' style='display: none' class='table table-hover table-striped table-fixed'>
<form action='' method='post'>
	<tr>
		<td width='50'>
			<b>Membro:</b> 
		</td>
		<td>
			<select name='membro' id='membro' style='width:180px'><?=$lista_membro;?></select>
		</td>
	</tr>
	<tr>
		<td>
			<b>Punição:</b>
		</td>
		<td style='text-align: left;'>
			<?php 
			//for ($i = 0; $i <= count($tp_warning)-1; $i++) { 
			while (list ($key, $val) = each ($tp_warning) ) {//echo $val; 
			?>
			<input type='radio' name='warning' value='<?=$key;?>'><?=$val;?><br>
			<?php } ?>
		</td>
	</tr>	<tr>
		<td>
			<b>Data:</b>
		</td>
		<td style='text-align: left;'>
			<input type='date' name='data' value='<?=date("Y-m-d", strtotime("now"));?>' style='width:180px'>
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
<br><br>
					</p>
				</div>
			</div>
		</div>
	</div>


<script>
document.getElementById("ciclo").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("ciclo");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=<?=$pg;?>&gd=<?=$gd;?>&ciclo="+x.value
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