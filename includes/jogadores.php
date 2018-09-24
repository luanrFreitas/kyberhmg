<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Jogadores</h1>
							<h2><span>Allycode | Discord</span></h2>
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
					<h2>Jogadores</h2>
					<p>
<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];

if ($op == "lista") {
// ==========================
// LISTAR JOGADORES 
// ==========================
?>
<table width='600' class="table table-hover table-striped table-fixed" style="font-size: 13px">
	<tr>
		<td><b>id</b></td>
		<td><b>Player</b></td>
		<td><b>Guilda</b></td>
		<td><b>AllyCode</b></td>
		<td><b>Discord</b></td>
		<td width='50'></td>
	</tr>

	<?php
	//leitura das guildas
	$sql = "SELECT nome, link FROM `guildas` ORDER BY nome ASC";
	$readGuilda = $PDO->query( $sql );
	$x = 0;
	if ($readGuilda->rowCount() < 1) {
		echo '<span class="ms no">Oppss! Não existe guildas cadastrados no momento!!</span>';
	} else {
		foreach ($readGuilda as $g){
			$guilda[substr($g['link'], 0, 5)] = $g['nome'];
		}
	}
	
	//leitura da tabela users
	$sql = "SELECT DISTINCT `j`.`id`, `u`.`player` AS nome, `u`.`guilda`, `j`.`allycode`, `j`.`discord`
		FROM `jogadores` AS j 
		INNER JOIN `units` AS u ON `j`.`url` = `u`.`url` 
		WHERE `u`.`url` != ''
		ORDER BY `u`.`guilda`, `u`.`player` ASC";

	$readUser = $PDO->query( $sql );
	$x = 0;
	if ($readUser->rowCount() < 1) {
		echo '<span class="ms no">Oppss! Não existe jogadores cadastrados no momento!!</span>';
	} else {
		foreach ($readUser as $rows){
			$x++;
			?>
			<tr>
				<td><?php echo $x; ?></td>
				<td><?php echo $rows['nome']; ?></td>
				<td><?php echo $guilda[$rows['guilda']]; ?></td>
				<td><?php echo number_format($rows['allycode'], 0, '', '-');; ?></td>
				<td><?php echo $rows['discord']; ?></td>
				<td><a href="?pg=jogadores&op=editar&id=<?php echo $rows['id']; ?>"><img src='images/edit.png'></a></td>
			</tr>
			<?php
		}
	}
	?>
</table>

<?php
} elseif ($op == "editar") {
// ==========================
// EDITAR JOGADOR
// ==========================

?>
<table width='600' class="table table-hover table-striped table-fixed">
	<tr>
		<td><b><center> <?php echo ($op == "editar" ? "Editar" : "Adicionar"); ?> Jogador</center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (isset($_POST['enviar'])){
			unset($data['enviar']);
			if ($op == "editar") {
				$sql = "UPDATE `jogadores` SET `allycode` = '".$data['allycode']."', `discord` = '".$data['discord']."' WHERE `jogadores`.`id` = '".$id."'";
				$PDO->query( $sql );
				echo '<span class="ms ok">Pronto! Jogador salvo com sucesso!</span>';
				echo '<meta HTTP-EQUIV="refresh" CONTENT="0;URL=?pg=jogadores">';
			}
		}
		if ($op == "editar") {
			$sql = "SELECT DISTINCT j.id, u.player AS nome, j.allycode, j.discord 
				FROM jogadores AS j 
				INNER JOIN units AS u ON j.url = u.url 
				WHERE j.id = '".$id."' 
				ORDER BY nome ASC";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table width='700' class="table table-hover table-striped table-fixed">
				<tr>
					<td width='70'>Nome:</td>
					<td width='300'><?php echo $user['nome']; ?></td>
				</tr>
				<tr>
					<td>AllyCode</td>
					<td><input type="number" name="allycode" value="<?php echo $user['allycode']; ?>" style='width:300px' placeholder="Somente números"/></td>
				</tr>
				<tr>
					<td>Discord:</td>
					<td><input type="text" name="discord" value="<?php echo $user['discord']; ?>" style='width:300px' placeholder="<@XXXXXXXXXXXXXXXXX>"/></td>
				</tr>
				<tr>
					<td></td>
					<td style="float:right">
						<button type="submit" name="enviar" class="btn btn-primary" value="Salvar" title="Salvar">Salvar</button>
					</td>
				</tr>
			</table>
			</form>
		</center>

		</td>
	</tr>
</table>

<?php
} else {
	echo "Opção inexistente.";
}
?>
					</p>
				</div>
			</div>
		</div>
	</div>