<?php
$filtros = array(
	"Dark Side"			=> "",
	"Attacker"			=> "",
	"Bounty Hunter"		=> "",
	"Droid"				=> "",
	"Empire"			=> "",
	"Light Side"		=> "",
	"Fleet Commander"	=> "",
	"Clone Trooper"		=> "",
	"Ewok"				=> "",
	"First Order"		=> "",
	"Healer"			=> "",
	"Jedi"				=> "",
	"Geonosian"			=> "",
	"Nightsister"		=> "",
	"Support"			=> "",
	"Scoundrel"			=> "",
	"Human"				=> "",
	"Rebel"				=> "",
	"Tank"				=> "",
	"Sith"				=> "",
	"Jawa"				=> "",
	"Resistance"		=> "",
	"Tusken"			=> "",
	"Separatist"		=> ""
);

$item = $_POST['filtro'];
if(empty($item)) { 
	$erro = "Marque ao menos 1 item acima.";
} else {
	$erro = "";
	$campos = "";
	$N = count($item);
	for($i=0; $i < $N; $i++) { 
		if ($campos != "") $campos .= " OR ";
		$campos .= "`chars`.`".$item[$i]."` = '1' "; 
		$filtros[$item[$i]] = "checked";
	}
	//echo $campos;
}
?>

<script language="javascript">
	function checkAll(ele) {
		var checkboxes = document.getElementsByTagName('input');
		if (ele.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				console.log(i)
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
				}
			}
		}
	}
	function openCity(evt, cityName) {
		// Declare all variables
		var i, tabcontent, tablinks;

		// Get all elements with class="tabcontent" and hide them
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		// Get all elements with class="tablinks" and remove the class "active"
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}

		// Show the current tab, and add an "active" class to the link that opened the tab
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += " active";
	}
</script>

<ul class="tab">
	<li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Tipo')">Tipo</a></li>
	<li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Char')">Char</a></li>
</ul>

<div id="Tipo" class="tabcontent">
	<p>
		<form action="" method="post">
		<table>
		<tr>
			<th>Alinhamento</th>
			<th>Função</th>
			<th>Profissão</th>
			<th>Espécie</th>
			<th>Afiliação</th>
			<th>Estrelas</th>
		</tr>
		<tr>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Dark Side"]?> value="Dark Side">Lado Negro</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Attacker"]?> value="Attacker">Atacante</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Bounty Hunter"]?> value="Bounty Hunter">Caçador de Recompensa</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Droid"]?> value="Droid">Droid</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Empire"]?> value="Empire">Império</td>
			<td style="text-align: center;">
				<select name="stars">
					<option value="star1" <?=($_POST['stars'] == "star1") ? "selected" : "";?>>1 estrela</option>
					<option value="star2" <?=($_POST['stars'] == "star2") ? "selected" : "";?>>2 estrelas</option>
					<option value="star3" <?=($_POST['stars'] == "star3") ? "selected" : "";?>>3 estrelas</option>
					<option value="star4" <?=($_POST['stars'] == "star4") ? "selected" : "";?>>4 estrelas</option>
					<option value="star5" <?=($_POST['stars'] == "star5") ? "selected" : "";?>>5 estrelas</option>
					<option value="star6" <?=($_POST['stars'] == "star6") ? "selected" : "";?>>6 estrelas</option>
					<option value="star7" <?=($_POST['stars'] == "star7") ? "selected" : "";?>>7 estrelas</option>
				</select> *
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Light Side"]?> value="Light Side">Lado Bom</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Fleet Commander"]?> value="Fleet Commander">Comandante da Frota</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Clone Trooper"]?> value="Clone Trooper">Clone Trooper</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Ewok"]?> value="Ewok">Ewok</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["First Order"]?> value="First Order">Primeira Ordem</td>
			<td style="text-align: center;"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Healer"]?> value="Healer">Curandeiro</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Jedi"]?> value="Jedi">Jedi</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Geonosian"]?> value="Geonosian">Geonosiano</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Nightsister"]?> value="Nightsister">Nightsister</td>
			<td style="text-align: center;"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Support"]?> value="Support">Suporte</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Scoundrel"]?> value="Scoundrel">Canalha</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Human"]?> value="Human">Humano</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Rebel"]?> value="Rebel">Rebelde</td>
			<td style="text-align: center;"><b>Equipamentos</b></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Tank"]?> value="Tank">Tanque</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Sith"]?> value="Sith">Sith</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Jawa"]?> value="Jawa">Jawa</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Resistance"]?> value="Resistance">Resistência</td>
			<td style="text-align: center;">
				<select name="gears">
					<option value="1"  <?=($_POST['gears'] == "1")  ? "selected" : "";?>>I</option>
					<option value="2"  <?=($_POST['gears'] == "2")  ? "selected" : "";?>>II</option>
					<option value="3"  <?=($_POST['gears'] == "3")  ? "selected" : "";?>>III</option>
					<option value="4"  <?=($_POST['gears'] == "4")  ? "selected" : "";?>>IV</option>
					<option value="5"  <?=($_POST['gears'] == "5")  ? "selected" : "";?>>V</option>
					<option value="6"  <?=($_POST['gears'] == "6")  ? "selected" : "";?>>VI</option>
					<option value="7"  <?=($_POST['gears'] == "7")  ? "selected" : "";?>>VII</option>
					<option value="8"  <?=($_POST['gears'] == "8")  ? "selected" : "";?>>VIII</option>
					<option value="9"  <?=($_POST['gears'] == "9")  ? "selected" : "";?>>IX</option>
					<option value="10" <?=($_POST['gears'] == "10") ? "selected" : "";?>>X</option>
					<option value="11" <?=($_POST['gears'] == "11") ? "selected" : "";?>>XI</option>
					<option value="12" <?=($_POST['gears'] == "12") ? "selected" : "";?>>XII</option>
				</select> *
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" onchange="checkAll(this)" name="filtro[]" /><b>Marcar Todos</b></td>
			<td></td>
			<td></td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Tusken"]?> value="Tusken">Tusken</td>
			<td><input type="checkbox" name="filtro[]" <?=$filtros["Separatist"]?> value="Separatist">Separatista</td>
			<td style="text-align: center;"></td>
		</tr>
		<tr>
			<td colspan="6" style="text-align: center;padding: 15px"><input type="Submit" value="Filtrar"></td>
		</tr>
		<tr>
			<td colspan="6" style="text-align: center;"><div style="text-align: right;">* valor mínimo</td>
		</tr>

		</table>
		</form>
	</p>
</div>

<div id="Char" class="tabcontent">
	<p>Char</p> 
</div>

<?php
if ($erro != "") {
	echo $erro;
} else {
	$sql = "SELECT DISTINCT membros.id, membros.nome, membros.idguilda, guildas.nome AS guilda
		FROM membros
		INNER JOIN colecao ON membros.id = colecao.idmembro
		INNER JOIN chars   ON chars.nome = colecao.nome
		INNER JOIN guildas ON membros.idguilda = guildas.id
		WHERE (".$campos.")
		AND colecao.gear >= ".$_POST['gears']." 
		AND colecao.".$_POST['stars']." = 1 
		ORDER BY membros.idguilda, membros.nome
		";

	//$membros = mysql_query($sql);
	$result = $PDO->query( $sql );
	$guilda = 0;
	
	echo "
		<table width='100%'>";
	while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
		$sql2 = "SELECT DISTINCT colecao.*, chars.link
			FROM membros
			INNER JOIN colecao ON membros.id = colecao.idmembro
			INNER JOIN chars   ON chars.nome = colecao.nome
			INNER JOIN guildas ON membros.idguilda = guildas.id
			WHERE (".$campos.")
			AND membros.id = ".$membro['id']."
			AND colecao.gear >= ".$_POST['gears']." 
			AND colecao.".$_POST['stars']." = 1 
			ORDER BY colecao.level, colecao.gear DESC
			";

	
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
				<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000' colspan='2'>
					<br><img src='".str_replace(' ', '_', $membro['guilda'])."_m.jpg'><br><br>
				</td>
			</tr>";
		}
		echo "
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px'>".$membro['nome']."</td>
			<td>
			";

			$chars = $PDO->query( $sql2 );
			while ($char = $chars->fetch( PDO::FETCH_ASSOC )) {
				echo "
				<div class='collection-char collection-char-light-side'>
					<div class='player-char-portrait char-portrait-full char-portrait-full-gear-t".$char['gear']."'>
						<img class='char-portrait-full-img' src='tex.".$char['link'].".png' alt='".$char['nome']."' title='".$char['nome']."'>
						<div class='char-portrait-full-gear'></div>
						<div class='star star1'></div>
						<div class='star star2 ".($char['star2'] == 1 ? "":"star-inactive")."'></div>
						<div class='star star3 ".($char['star3'] == 1 ? "":"star-inactive")."'></div>
						<div class='star star4 ".($char['star4'] == 1 ? "":"star-inactive")."'></div>
						<div class='star star5 ".($char['star5'] == 1 ? "":"star-inactive")."'></div>
						<div class='star star6 ".($char['star6'] == 1 ? "":"star-inactive")."'></div>
						<div class='star star7 ".($char['star7'] == 1 ? "":"star-inactive")."'></div>
						<div class='char-portrait-full-level'>".$char['level']."</div>
						<div class='char-portrait-full-gear-level'>".$char['gear']."</div>
					</div>
					<!--<div class='collection-char-name'>".$char['nome']."</div>-->
				</div>";
			}
			
		echo "
			</td>
		</tr>";
	}
	echo "
	</table>";
}
?>