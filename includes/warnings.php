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
				<h2>Membros</h2>
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
	
	foreach($_POST['warn'] AS $jogador) {
		//echo $_POST['membro']." - ".$_POST['warning']." - ".$_POST['fase']." - ".floor($_POST['warning']/10);
		$sql2 = "INSERT INTO `punicao` VALUES (NULL, '".$jogador."', '".$_GET['ciclo']."',  
			'".$_POST['warning']."',  '".$_POST['data']."', ".floor($_POST['warning']/10).")";
			
		$PDO->query( $sql2 );
	}
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
			<td>
				<b>Data: </b> 
				<select name='ciclo' id='ciclo'><?=$opcao;?></select>
			</td>
			</form>
		</tr>
	</table>
	
	<table width='100%' cellpadding='3' id='tabela' class='table table-hover table-striped table-fixed'>
	<form action='' method='post'>
		<tr>
			<td colspan='2'>
				<b>Punição:</b>
			</td>
			<td colspan='3' style='text-align: left;'>
				<select name='warning' id='warning' style='width:400px'>
				<?php 
				while (list ($key, $val) = each ($tp_warning) ) {
					echo "<option value='".$key."'>".$val."</option>"; 
				} 
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<b>Data:</b>
			</td>
			<td colspan='3' style='text-align: left;'>
				<input type='date' name='data' value='<?=date("Y-m-d", strtotime("now"));?>' style='width:180px'>
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style='width: 20px;'></td>
			<td style='width: 120px;'><b>Player</b></td>
			<td style='width: 20px;'><b>Qtd.<br>Pun.</b></td>
			<td style='width: 300px;'><b>Punições</b></td>
			<td style='width: 20px;'><img src='images/plus.ico'></td>
		</tr>
<?php
	}
	
	$x++;
	$membro_nome = $membro['url'];	
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
		<td style='text-align: center;'>".$x."</td>
		<td style='text-align: left;'>".$membro['player']."</td>
		<td style='text-align: center;'>".$warning_t."</td>
		<td style='text-align: left; white-space:normal;'>".$warning."</td>
		<td style='text-align: center;'>".($status == 1 ? "<input type='checkbox' name='warn[]' value='".$membro_nome."' />" : "")."</td>
	</tr>";
	
} ?>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td colspan='5' style='text-align: right; white-space:normal;'>
				<input type='hidden' name='op' value='salvar_punicao'>
				<input type='Submit' value='Salvar'>
			</td>
		</tr>
	</form>
</table>

<br><br>
				</p>
			</div>
		</div>
	</div>
</div>
