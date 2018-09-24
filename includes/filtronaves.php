<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Filtros</h1>
							<h2><span>Naves | União</span></h2>
						</div>
					</div>
				</div>
			</div>
		</li>
		</ul>
	</div>
</aside>
	<div class="colorlib-event" style ="overflow-x: auto;">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Filtrar Naves</h2>
					<p>
<table width='630' class="table table-hover table-striped table-fixed">
<form action="" method="post">
<tr>
	<th width='70'></th>
	<th width='420'>Time</th>
	<th width='60' style='text-align: right'>Estrelas:</th>
	<th width='50'>
		<select name="stars">
			<option value="7" <?=($_POST['stars'] == "7") ? "selected" : "";?>>7</option>
			<option value="6" <?=($_POST['stars'] == "6") ? "selected" : "";?>>6</option>
			<option value="5" <?=($_POST['stars'] == "5") ? "selected" : "";?>>5</option>
			<option value="4" <?=($_POST['stars'] == "4") ? "selected" : "";?>>4</option>
			<option value="3" <?=($_POST['stars'] == "3") ? "selected" : "";?>>3</option>
			<option value="2" <?=($_POST['stars'] == "2") ? "selected" : "";?>>2</option>
			<option value="1" <?=($_POST['stars'] == "1") ? "selected" : "";?>>1</option>
		</select> *
	</th>
</tr>
	<?php for ($c = 1; $c <= 8; $c++) { ?>
<tr>
	<td style="text-align: right;"><b>Nave <?=$c;?></b></td>
	<td style="text-align: center;">
		<select name="char_<?=$c;?>">
			<option value="" <?=(($_POST["char_".$c] == "") ? "selected" : "");?>>--</option>
		<?php
			$sql_char = "SELECT * FROM ships ORDER BY name";
			$chars = $PDO->query( $sql_char );
			while ($char = $chars->fetch( PDO::FETCH_ASSOC )) {
				echo "<option value=\"".$char['base_id']."\" ".(($_POST["char_".$c] == $char['base_id']) ? "selected" : "").">".$char['name']."</option>";
			}
		?>
		</select>
	</td>
	<?php
		if ($c == 1) { ?>
			<td style='text-align: right'><b>Nível:</b></td>
			<td>
				<select name="nivel">
					<option value="85"  <?=($_POST['nivel'] == "85")  ? "selected" : "";?>>85</option>
					<option value="80"  <?=($_POST['nivel'] == "80")  ? "selected" : "";?>>80</option>
					<option value="75"  <?=($_POST['nivel'] == "75")  ? "selected" : "";?>>75</option>
					<option value="70"  <?=($_POST['nivel'] == "70")  ? "selected" : "";?>>70</option>
					<option value="65"  <?=($_POST['nivel'] == "65")  ? "selected" : "";?>>65</option>
					<option value="60"  <?=($_POST['nivel'] == "60")  ? "selected" : "";?>>60</option>
					<option value="50"  <?=($_POST['nivel'] == "50")  ? "selected" : "";?>>50</option>
					<option value="40"  <?=($_POST['nivel'] == "40")  ? "selected" : "";?>>40</option>
					<option value="30"  <?=($_POST['nivel'] == "30")  ? "selected" : "";?>>30</option>
					<option value="20" <?=($_POST['nivel'] == "20") ? "selected" : "";?>>20</option>
					<option value="10" <?=($_POST['nivel'] == "10") ? "selected" : "";?>>10</option>
					<option value="1" <?=($_POST['nivel'] == "1") ? "selected" : "";?>>1</option>
				</select> *
			</td>
		<?php }
		if ($c == 2) { ?>
			<td rowspan='2' style='text-align: right'><b>Exibir:</b></td>
			<td rowspan='2'>
				<input type="radio" name="exibir" value="todos" <?=($_POST['exibir'] == "todos" OR !$_POST['exibir'])  ? "checked" : "";?>>Todos<br>
				<input type="radio" name="exibir" value="qualquer" <?=($_POST['exibir'] == "qualquer")  ? "checked" : "";?>>Qualquer um
			</td>
		<?php }
		if ($c == 3) { ?>
		<?php }
		if ($c == 4) { ?>
			<td style='text-align: right' colspan="2">* valor mínimo</td>
		<?php }
		if ($c == 5 or $c == 6 or $c == 7) { ?>
			<td style='text-align: right' colspan="2"></td>
		<?php }
		if ($c == 8) { ?>
			<td style='text-align: right' colspan="2" rowspan="3">
				<input type="hidden" name="filtrar" value="time">
				<button type="submit" name="filtrar" class="btn btn-primary" value="time" title="Filtrar">Filtrar</button>
			</td>
		<?php }
		?>
</tr>
	<?php 
	}
	?>
</form>
</table>

<?php
$n = 0;

$where = "";
$qtd = 0;
if ($_POST['filtrar'] == "time") {
	if ($_POST['char_1'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_1']."'"; ++$qtd; }
	if ($_POST['char_2'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_2']."'"; ++$qtd; }
	if ($_POST['char_3'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_3']."'"; ++$qtd; }
	if ($_POST['char_4'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_4']."'"; ++$qtd; }
	if ($_POST['char_5'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_5']."'"; ++$qtd; }
	if ($_POST['char_6'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_6']."'"; ++$qtd; }
	if ($_POST['char_7'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_7']."'"; ++$qtd; }
	if ($_POST['char_8'] != "") { $where = $where.($where == "" ? "" : " OR ")."units.base_id = '".$_POST['char_8']."'"; ++$qtd; }

	$players = "SELECT DISTINCT units.player, units.guilda, guildas.nome
		FROM units
		INNER JOIN guildas ON guildas.link LIKE CONCAT('%',units.guilda,'%')
		WHERE (".$where.")
		AND units.rarity >= ".$_POST['stars']." 
		AND units.level >= ".$_POST['nivel']." 
		ORDER BY units.guilda, units.player
		";

	$res_players = $PDO->query( $players );
	$guilda = 0;
	echo "
		<table width='630' class='table table-hover table-striped table-fixed'>";
	while ($membro = $res_players->fetch( PDO::FETCH_ASSOC )) {
		if ($_POST['filtrar'] == "time") {
			$chars = "SELECT units.*, ships.name, ships.image
				FROM units
				INNER JOIN ships ON ships.base_id = units.base_id
				WHERE (".$where.")
				AND units.player = \"".$membro['player']."\"
				AND units.rarity >= ".$_POST['stars']." 
				AND units.level >= ".$_POST['nivel']." 
				ORDER BY ships.name
				";

			$sql3 = "SELECT COUNT(*) AS total 
				FROM units
				WHERE (".$where.")
				AND units.player = \"".$membro['player']."\"
				AND units.rarity >= ".$_POST['stars']." 
				AND units.level >= ".$_POST['nivel']." 
				ORDER BY units.guilda, units.player
				";
		}
		
		$exibir = 1;
		
		if ( $sql3 == "" ) {
			$qtd = 0;
			$num_atual['total'] = 0;
		} else {
			$atual = $PDO->query( $sql3 );
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			if ($_POST['exibir'] == "todos") { $exibir = ($qtd == $num_atual['total']) ? 1 : 0; }
		}
		
		if ($qtd > 0 AND $num_atual['total'] > 0 AND $exibir == 1) {
			if ($membro['guilda'] <> $guilda and $guilda != 0) {
				echo "
				</table>
				<br><br>
				<table width='630' class='table table-hover table-striped table-fixed'>";
			}
			if ($membro['guilda'] <> $guilda) {
				$guilda = $membro['guilda'];
				echo "
				<tr>
					<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='2'>
						<br><b>".$membro['nome']."<b><br><br>
					</td>
				</tr>";
				$x = 0;
			}
		
			$x = $x + 1;
			echo "
			<tr style='border-bottom: 1px solid #ddd;'>
				<td style='text-align: left; width: 20%;'><B>".$x."</B><BR>".$membro['player']."<BR><a href='https://swgoh.gg".$membro['url']."' target='_blank'><img src='images/view.ico'></a></td>
				<td style='text-align: left; width: 80%;'>
				";

			$res_chars = $PDO->query( $chars );
			while ($char = $res_chars->fetch( PDO::FETCH_ASSOC )) {
				$imagem = str_replace("//swgoh.gg/static/img/assets/", "http:".$site."/chars/", $char['image']);
				echo "
				<div class='collection-char collection-char-light-side'>
					<div class='player-char-portrait char-portrait-full char-portrait-full-gear-t".$char['gear_level']."'>
						<img class='char-portrait-full-img' src='".$imagem."' alt='".$char['name']."' title='".$char['name']."'>
						<div class='char-portrait-full-gear'></div>
						<div class='star star1'></div>
						<div class='star star2 ".($char['rarity'] >= 2 ? "" : "star-inactive")."'></div>
						<div class='star star3 ".($char['rarity'] >= 3 ? "" : "star-inactive")."'></div>
						<div class='star star4 ".($char['rarity'] >= 4 ? "" : "star-inactive")."'></div>
						<div class='star star5 ".($char['rarity'] >= 5 ? "" : "star-inactive")."'></div>
						<div class='star star6 ".($char['rarity'] >= 6 ? "" : "star-inactive")."'></div>
						<div class='star star7 ".($char['rarity'] >= 7 ? "" : "star-inactive")."'></div>
						<div class='char-portrait-full-level'>".$char['level']."</div>
						<!--<div class='char-portrait-full-gear-level'>".$char['gear_level']."</div>-->
					</div>
					<!--<div class='collection-char-name'>".$char['nome']."</div>-->
				</div>";
			}
				
			echo "
				</td>
			</tr>";
		}
	}
}
echo "</table>";
?>
					</p>
				</div>
			</div>
		</div>
	</div>