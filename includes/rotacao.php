<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Rotação</h1>
							<h2><span>Score | Resultado</span></h2>
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
				<h2>Dados Atuais</h2>
				<p>
<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dttb = date("Y-m-d", strtotime("-1 days")); else $dttb = $_GET['dt'];
if (is_null($_GET['tp'])) $tp = "tb"; else $tp = $_GET['tp'];

$rebaixamento['Sacrifício de Bnar'] = "Aquamarine";
$rebaixamento['Manto da Força'] = "Aquamarine";
$rebaixamento['Solari'] = "Aquamarine";
$rebaixamento['Aquamarine'] = "Kaiburr";

$cores['Sacrifício de Bnar'] = "#ff9999";
$cores['Manto da Força'] = "#ffcc99";
$cores['Solari'] = "#b3e0ff";
$cores['Aquamarine'] = "#99ff99";
$cores['Kaiburr'] = "#ffccdd";

$media['Sacrifício de Bnar']['valor'] = 0;
$media['Sacrifício de Bnar']['player'] = 0;
$media['Manto da Força']['valor'] = 0;
$media['Manto da Força']['player'] = 0;
$media['Solari']['valor'] = 0;
$media['Solari']['player'] = 0;
$media['Aquamarine']['valor'] = 0;
$media['Aquamarine']['player'] = 0;
$media['Kaiburr']['valor'] = 0;
$media['Kaiburr']['player'] = 0;

require 'includes/rank_dados.php'; 

$tp_warning[10] = 'Nível 1 - Queima parcial de fase em qualquer Raid';
$tp_warning[11] = 'Nível 1 - 600: Não concluir totalmente';
$tp_warning[12] = 'Nível 1 - TW: Não Entrar';
$tp_warning[13] = 'Nível 1 - TB/TW: Descrumprir Orientações';
$tp_warning[14] = 'Nível 1 - Raid Sith (Normal): Sem dano após 24hs da abertura';
$tp_warning[15] = 'Nível 1 - Raid Sith (Heróica): Menos de 1M de dano ao final';
$tp_warning[16] = 'Nível 1 - Raid Sith (Heróica): Nenhum ataque até o início da P4';
$tp_warning[20] = 'Nível 2 - Queimar 1 ou + fases em qualquer Raid';
$tp_warning[21] = 'Nível 2 - TW: Entra e não participar';
$tp_warning[22] = 'Nível 2 - 600: Contribuição zero';
$tp_warning[30] = 'Nível 3 - Raid Sith: Dano zero no final';

if(strtotime($dttb) > strtotime("-1 days")) { $dttb = date("Y-m-d", strtotime("-1 days")); }

$dttb = date("Y-m-d", strtotime("now"));

$op = $_POST['op'];

$sqlCiclo = "SELECT * FROM ciclos ORDER BY inicio DESC LIMIT 1";
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
	//$opcao .= "<option value='".$rows['id']."' ".(($_GET['ciclo'] == $rows['id']) ? "selected" : "").">De ".date("d/m/Y", strtotime($rows['inicio']))." a ".date("d/m/Y", strtotime($rows['termino']))."</option>";
}

// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT units.url, units.player, units.guilda, guildas.nome, guildas.tipo, jogadores.allycode, jogadores.discord , jogadores.casual 
	FROM units 
	INNER JOIN guildas ON guildas.link LIKE CONCAT('%',units.guilda,'%')
	LEFT JOIN jogadores ON units.url = jogadores.url 
	WHERE guildas.tipo != 'C' AND units.url != ''
	ORDER BY units.guilda, units.player
	";
	
$result = $PDO->query( $sql );
$x = 0;

while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$peso = 0;
	$final[$i] = 0;
	for ($o = 0; $o <= count($times)-1; $o++) {
		$peso += $times[$o]['peso'];
		$time[$o] = 0;
		$time_final = 0;
		$item = "";
		for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) {
			for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
				$c_atual = $times[$o]['chars'][$u][$a];
				if ($rank2[$membro['url']][$c_atual['nome']]['base_id'] > 0) {
					$reducao = 0;
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
						if ($reducao > 0) $rank2[$membro['url']][$c_atual['nome']]['base_id'] = $rank2[$membro['url']][$c_atual['nome']]['base_id']*((100-$reducao*25)/100);
					}
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
				$c_atual = $times[$o]['chars'][$u][$a];
				if ($rank2[$membro['url']][$c_atual['nome']]['base_id'] > 0) {
					if ($rank2[$membro['url']][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank2[$membro['url']][$times[$o]['chars'][$u][1]['nome']]['base_id'])
						if ($rank2[$membro['url']][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank2[$membro['url']][$times[$o]['chars'][$u][2]['nome']]['base_id'])
							$ver = 0; else $ver = 2;
					else
						if ($rank2[$membro['url']][$times[$o]['chars'][$u][1]['nome']]['base_id'] >= $rank2[$membro['url']][$times[$o]['chars'][$u][2]['nome']]['base_id']) 
							$ver = 1; else $ver = 2;

					if ($a == $ver) $time[$o]  += $rank2[$membro['url']][$c_atual['nome']]['base_id'];
				}
			}
		}
		$time[$o] = $time_final > 0 ? 0 : $time[$o];
		$final[$i] += ($time[$o] / 5) * $times[$o]['peso'];
	}
	$final[$i] = $final[$i]/$peso;
	if ($membro['casual'] == 1) $final[$i] = 0;
	
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
	if ($membro['tipo'] == "H") {
		$rotacao_h[$x]['player'] = $membro['player'];
		$rotacao_h[$x]['final'] = $final[$i]-3*$warning_t < 0 ? 0 : $final[$i]-3*$warning_t;
		$rotacao_h[$x]['times'] = $final[$i];
		$rotacao_h[$x]['punicoes'] = $warning_t;
		$rotacao_h[$x]['origem'] = ucfirst($membro['nome']);
		$rotacao_h[$x]['destino'] = ucfirst($membro['nome']);	
		$rotacao_h[$x]['allycode'] = $membro['allycode'];	
		$rotacao_h[$x]['discord'] = $membro['discord'];	
		$rotacao_h[$x]['casual'] = $membro['casual'];	
		$media[ucfirst($membro['nome'])]['valor'] += $rotacao_h[$x]['final'];
	} elseif ($membro['tipo'] == "N") {
		$rotacao_n[$x]['player'] = $membro['player'];
		$rotacao_n[$x]['final'] = $final[$i]-3*$warning_t < 0 ? 0 : $final[$i]-3*$warning_t;
		$rotacao_n[$x]['times'] = $final[$i];
		$rotacao_n[$x]['punicoes'] = $warning_t;
		$rotacao_n[$x]['origem'] = ucfirst($membro['nome']);
		$rotacao_n[$x]['destino'] = ucfirst($membro['nome']);
		$rotacao_n[$x]['allycode'] = $membro['allycode'];	
		$rotacao_n[$x]['discord'] = $membro['discord'];	
		$rotacao_n[$x]['casual'] = $membro['casual'];	
		$media[ucfirst($membro['nome'])]['valor'] += $rotacao_n[$x]['final'];
	}
	$media[ucfirst($membro['nome'])]['player'] += 1;

	$x++;
}

$media['Sacrifício']['valor'] = $media['Sacrifício']['valor']/$media['Sacrifício']['player'];
$media['Manto da Força']['valor'] = $media['Manto da Força']['valor']/$media['Manto da Força']['player'];
$media['Solari']['valor'] = $media['Solari']['valor']/$media['Solari']['player'];
$media['Aquamarine']['valor'] = $media['Aquamarine']['valor']/$media['Aquamarine']['player'];
$media['Kaiburr']['valor'] = $media['Kaiburr']['valor']/$media['Kaiburr']['player'];

for ($x = 0; $x < count($rotacao_h); $x++)
	if ($rotacao_h[$x]['punicoes'] > 3) $rotacao_h[$x]['final'] = $media[$rebaixamento[$rotacao_h[$x]['origem']]]['valor'];

for ($x = 0; $x < count($rotacao_n); $x++)
	if ($rotacao_n[$x]['punicoes'] > 3) $rotacao_n[$x]['final'] = $media[$rebaixamento[$rotacao_n[$x]['origem']]]['valor'];

$x = 0;
$rotacao_h = array_sort($rotacao_h, 'final', SORT_DESC);
foreach ($rotacao_h as $jogador) { $x++; $ordem_h[$x] = $jogador; }

$x = 0;
$rotacao_n = array_sort($rotacao_n, 'final', SORT_DESC);
foreach ($rotacao_n as $jogador) { $x++; $ordem_n[$x] = $jogador; }

$x = 0;
$fim = false;
while ($fim == false) {
	$x++;
	if ($ordem_h[151-$x]['final'] < $ordem_n[$x]['final']) {
		$ordem_h[151-$x]['destino'] = "Aquamarine";
		$ordem_n[$x]['destino'] = $ordem_h[151-$x]['origem'];
		$rotacao[] = $ordem_h[151-$x];
		$rotacao[] = $ordem_n[$x];
	} else {
		$fim = true;
	}
}
for ($a = $x; $a <= count($ordem_n); $a++) {
	if (floor(($a-1)/50) == 0) $destino = "Aquamarine";
	elseif (floor(($a-1)/50) == 1) $destino = "Lambent";
	elseif (floor(($a-1)/50) == 2) $destino = "Kaiburr";
	$ordem_n[$a]['destino'] = $destino;
	if ($ordem_n[$a]['origem'] != $ordem_n[$a]['destino']) $rotacao[] = $ordem_n[$a];
}

$x = 0;
$rotacao = array_sort($rotacao, 'player', SORT_ASC);
foreach ($rotacao as $jogador) { $x++; $ordem[$x] = $jogador; }

$x = 0;
$guildas_rotacao = null;
?>
	<table width='700' cellpadding='3' class='table table-hover table-striped table-fixed'>
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; font-weight: bold; ' colspan='21'>
				<br>Guildas Raid Sith Heróica<br><br>
			</td>
		</tr>
	</table>
	<table width='700' cellpadding='3' id='tabela' class='table table-hover table-striped table-fixed'>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style='width: 20px;'></td>
			<td style='width: 160px;'><b>Player</b></td>
			<td style='width: 60px;'><b>Score<br>Final</b></td>
			<td style='width: 60px;'><b>% dos<br>Times</b></td>
			<td style='width: 40px;'><b>Qtd.<br>Pun.</b></td>
			<td style='width: 180px;'><b><center>Origem</center></b></td>
			<td style='width: 180px;'><b><center>Destino</center></b></td>
		</tr>
<?php
foreach ($ordem_h as $jogador) {
	$x++;
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x."</td>
		<td style='text-align: left;".($jogador['punicoes'] > 3 ? "background-color: #ffe8a8;" : "").($jogador['casual'] == 1 ? "color:red; font-weight: bold;" : "")."'>".$jogador['player']."</td>
		<td>".formatMoney($jogador['final'], 2)."</td>
		<td>".formatMoney($jogador['times'], 2)."</td>
		<td>".$jogador['punicoes']."</td>
		<td style='background-color:".$cores[$jogador['origem']]."'><center>".$jogador['origem']."</center></td>
		<td style='background-color:".$cores[$jogador['destino']]."'><center>".$jogador['destino']."</center></td>
	</tr>";
	if (!in_array($jogador['origem'], $guildas_rotacao) AND $jogador['origem'] != null) $guildas_rotacao[] = $jogador['origem'];
	
}
?>
	</table>

<?php?>
	<table width='700' cellpadding='3' class='table table-hover table-striped table-fixed'>
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; font-weight: bold; ' colspan='21'>
				<br>Guildas Raid Sith Normal<br><br>
			</td>
		</tr>
	</table>
	<table width='700' cellpadding='3' id='tabela' class='table table-hover table-striped table-fixed'>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style='width: 20px;'></td>
			<td style='width: 160px;'><b>Player</b></td>
			<td style='width: 60px;'><b>Score<br>Final</b></td>
			<td style='width: 60px;'><b>% dos<br>Times</b></td>
			<td style='width: 40px;'><b>Qtd.<br>Pun.</b></td>
			<td style='width: 180px;'><b><center>Origem</center></b></td>
			<td style='width: 180px;'><b><center>Destino</center></b></td>
		</tr>
<?php

$x = 0;
foreach ($ordem_n as $jogador) {
	$x++;
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x."</td>
		<td style='text-align: left;".($jogador['punicoes'] > 3 ? "background-color: #ffe8a8;": "").($jogador['casual'] == 1 ? "color:red;fontWeight=bold;": "")."'>".$jogador['player']."</td>
		<td>".formatMoney($jogador['final'], 2)."</td>
		<td>".formatMoney($jogador['times'], 2)."</td>
		<td>".$jogador['punicoes']."</td>
		<td style='background-color:".$cores[$jogador['origem']]."'><center>".$jogador['origem']."</center></td>
		<td style='background-color:".$cores[$jogador['destino']]."'><center>".$jogador['destino']."</center></td>
	</tr>";
	if (!in_array($jogador['origem'], $guildas_rotacao) AND $jogador['origem'] != null) $guildas_rotacao[] = $jogador['origem'];
}
?>
	</table>

				</p>
			</div>
		</div>
	</div>
</div>
<?php 
if (!empty($_SESSION['autUser'])) {
?>
<div id="colorlib-services">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
				<h2>Rotação</h2>
				<p>Resultado final para a rotação.</p>
			</div>
		</div>
		<div class="row">
<?php
foreach ($guildas_rotacao as $destino) {
	echo "
			<div class=\"col-md-5 animate-box\">
				<div class=\"event-entry\">
						<h3>Destino: ".$destino."</h3>";
						$x = 0;
						foreach ($ordem as $jogador) {
							if ($jogador['destino'] == $destino) {
								echo ($x+1)." - ".$jogador['player']." (".$jogador['allycode'].") Origem: ".$jogador['origem']."<br>";
								$x++;
							}
						}
	echo "
				</div>
			</div>";
}
?>
		</div>
	</div>
</div>
<?php
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

<br><br>
	
<?php
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

?>