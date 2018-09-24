<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Batalhas por Território</h1>
							<h2><span>Pelotões | Esquadrões</span></h2>
						</div>
					</div>
				</div>
			</div>
		</li>
		</ul>
	</div>
</aside>
	<div class="colorlib-event"  style ="overflow-x: auto;">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Análise para atribuições</h2>
					<p>
<?php
$guilda 	= filter_input(INPUT_POST, 'guilda', FILTER_SANITIZE_STRING);
$lado 		= filter_input(INPUT_POST, 'lado', FILTER_SANITIZE_STRING);
$fase 		= filter_input(INPUT_POST, 'fase', FILTER_SANITIZE_STRING);
$territorio = filter_input(INPUT_POST, 'territorio', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$sql 		= "SELECT link, nome FROM guildas WHERE ativo = '1' ORDER BY nome ASC";
$readUser 	= $PDO->query( $sql );

foreach ($readUser as $rows){
	$link = explode("/", $rows['link']);
	$guilda = !isset($guilda) ? $link[0] : $guilda;
	$v_guildas .="
		<input type='radio' name='guilda' value='".$link[0]."' ".($guilda==$link[0]?'checked':'')."> ".$rows['nome']."<br>";
}
echo "<form method='POST' action=''>
	<table style='border-collapse: collapse;' class='table table-hover table-striped table-fixed'>
		<tr>
			<td><b>Guildas</b></td>
			<td>".$v_guildas."</td>
		</tr>
		<tr>
			<td><b>Lados</b></td>
			<td>
				<input type='radio' name='lado' value='Light Side' ".(($lado=='Light Side' or !isset($lado))?'checked':'')."> Lado da Luz &nbsp;
				<input type='radio' name='lado' value='Dark Side'  ".($lado=='Dark Side' ?'checked':'')."> Lado Sombrio
			</td>
		</tr>
		<tr>
			<td><b>Fases</b></td>
			<td>
				<input type='radio' name='fase' value='1' ".(($fase=='1' or !isset($fase))?'checked':'')."> 1 &nbsp;
				<input type='radio' name='fase' value='2' ".($fase=='2'?'checked':'')."> 2 &nbsp;
				<input type='radio' name='fase' value='3' ".($fase=='3'?'checked':'')."> 3 &nbsp;
				<input type='radio' name='fase' value='4' ".($fase=='4'?'checked':'')."> 4 &nbsp;
				<input type='radio' name='fase' value='5' ".($fase=='5'?'checked':'')."> 5 &nbsp;
				<input type='radio' name='fase' value='6' ".($fase=='6'?'checked':'')."> 6 
			</td>
		</tr>
		<tr>
			<td></td>
			<td style='text-align: right'>
				<button type='submit' name='SendPesqMsg' class='btn btn-primary' value='SendPesqMsg' title='Exibir'>Exibir</button>
			</td>
		</tr>
	</table>
</form>";

$avisar = filter_input(INPUT_POST, 'avisar', FILTER_SANITIZE_STRING);
$SendPesqMsg = filter_input(INPUT_POST, 'SendPesqMsg', FILTER_SANITIZE_STRING);
if ($SendPesqMsg OR $avisar) {
	echo "<br><br><h2>Chars do ".$lado."</h2>Chars que precisam de <b>atenção</b> pois a guilda tem menos que 10 unidades disponíveis<br><br>";

	//SQL para selecionar os registros
	//$sql = "SELECT `characters`.`name`, `characters`.`base_id`, `characters`.`image` FROM `characters` LEFT JOIN `chars` ON `chars`.`nome` = `characters`.`name` WHERE `chars`.`".$lado."` = '1' ";
	$sql = "SELECT `characters`.`name`, `characters`.`base_id`, `characters`.`image`, sum(CASE WHEN `units`.`rarity` >= '".($fase+1)."' THEN 1 ELSE 0 END) AS total
		FROM `characters` 
		LEFT JOIN `chars` ON `chars`.`nome` = `characters`.`name` 
		LEFT JOIN `units` ON `units`.`base_id` = `characters`.`base_id`
		WHERE `chars`.`".$lado."` = '1' AND `units`.`guilda` = '".$guilda."'
		GROUP BY `characters`.`base_id`  
		ORDER BY `total`, `characters`.`name` ASC";
	$resultado_sql = $PDO->prepare($sql);
	$resultado_sql->execute();

	$sql_nave = "SELECT `ships`.`name`, `ships`.`base_id`, `ships`.`image`, sum(CASE WHEN `units`.`rarity` >= '".($fase+1)."' THEN 1 ELSE 0 END) AS total
		FROM `ships` 
		LEFT JOIN `units` ON `units`.`base_id` = `ships`.`base_id`
		WHERE `units`.`guilda` = '".$guilda."'
		GROUP BY `ships`.`base_id`  
		ORDER BY `total`, `ships`.`name` ASC";
	$resultado_sql_nave = $PDO->prepare($sql_nave);
	$resultado_sql_nave->execute();

echo "<form method='POST' action='?pg=tb_aviso'>
	<table style='border-collapse: collapse;' class='table table-hover table-striped table-fixed'>
		<tr>
			<th>Chars</th>
			<th>Players</th>
			<th>Território<br>Superior/Central</th>
			<th>Território<br>Inferior</th>
		</tr>
";
if (($lado == 'Light Side' AND $fase >= 2) OR ($lado == 'Dark Side' AND $fase >= 3)) {
	while ($char = $resultado_sql_nave->fetch(PDO::FETCH_ASSOC)) {
		if ($char['total'] < 10) {

			$p_sql = "SELECT player FROM `units`
				WHERE `base_id` = '".$char['base_id']."' AND `guilda` = '".$guilda."' AND `rarity` >= '".($fase+1)."'
				ORDER BY `units`.`player` ASC";
			$resultado_p_sql = $PDO->prepare($p_sql);
			$resultado_p_sql->execute();

			$imagem = str_replace("//swgoh.gg/static/img/assets/", "http:".$site."/chars/", $char['image']);
			echo "<tr>
				<td><img src='".$imagem."' alt=\"".$char['name']."\" title=\"".$char['name']."\" height='90' width='90' style='border: 1px solid #fff; padding: 2px;'></td>
				<td>
					<b>".$char['name']."</b> (".$char['total'].")<br>";
				while ($player = $resultado_p_sql->fetch(PDO::FETCH_ASSOC)) {
					echo " - ".$player['player']."<br>";
				}
			echo "
				</td>
				<td>
					<input type='checkbox' name='territorio[".$char['base_id']."][0][]' value='1'> Esquadrão 1 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][0][]' value='2'> Esquadrão 2 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][0][]' value='3'> Esquadrão 3 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][0][]' value='4'> Esquadrão 4 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][0][]' value='5'> Esquadrão 5 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][0][]' value='6'> Esquadrão 6 
				</td>
				<td>
				</td>
			</tr>";
		}
	}
}
	while ($char = $resultado_sql->fetch(PDO::FETCH_ASSOC)) {
		if ($char['total'] < 10) {

			$p_sql = "SELECT player FROM `units`
				WHERE `base_id` = '".$char['base_id']."' AND `guilda` = '".$guilda."' AND `rarity` >= '".($fase+1)."'
				ORDER BY `units`.`player` ASC";
			$resultado_p_sql = $PDO->prepare($p_sql);
			$resultado_p_sql->execute();

			$imagem = str_replace("//swgoh.gg/static/img/assets/", "http:".$site."/chars/", $char['image']);
			echo "<tr>
				<td><img src='".$imagem."' alt=\"".$char['name']."\" title=\"".$char['name']."\" height='90' width='90' style='border: 1px solid #fff; padding: 2px;'></td>
				<td>
					<b>".$char['name']."</b> (".$char['total'].")<br>";
				while ($player = $resultado_p_sql->fetch(PDO::FETCH_ASSOC)) {
					echo " - ".$player['player']."<br>";
				}
			echo "
				</td>
				<td>
					<input type='checkbox' name='territorio[".$char['base_id']."][1][]' value='1'> Pelotão 1 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][1][]' value='2'> Pelotão 2 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][1][]' value='3'> Pelotão 3 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][1][]' value='4'> Pelotão 4 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][1][]' value='5'> Pelotão 5 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][1][]' value='6'> Pelotão 6 
				</td>
				<td>
					<input type='checkbox' name='territorio[".$char['base_id']."][2][]' value='1'> Pelotão 1 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][2][]' value='2'> Pelotão 2 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][2][]' value='3'> Pelotão 3 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][2][]' value='4'> Pelotão 4 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][2][]' value='5'> Pelotão 5 <br>
					<input type='checkbox' name='territorio[".$char['base_id']."][2][]' value='6'> Pelotão 6 
				</td>
			</tr>";
		}
	}
echo "
	<tr>
		<td style='text-align: right' colspan='4'>
			".($_SESSION['autUser']['nivel'] == 2 ? "<input type='checkbox' name='teste'> Teste" : "")."
			<input type='hidden' name='guilda' value='".$guilda."'>
			<input type='hidden' name='lado' value='".$lado."'>
			<input type='hidden' name='fase' value='".$fase."'>
			<button type='submit' name='avisar' class='btn btn-primary' value='avisar' title='Avisar'>Avisar</button>
		</td>
	</tr>
	</table>
</form>";
}

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

					</p>
				</div>
			</div>
		</div>
	</div>