<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Times</h1>
							<h2><span>Raids / TB / TW</span></h2>
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
					<h2>Times Importantes</h2>
					<p>
<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];

if ($op == "lista") { 
// ==========================
// LISTAR TIMES 
// ==========================
?>
<table width='650'>
	<tr>
		<td style="text-align: right;" colspan='5'>
			<button type="button" name="novo" class="btn btn-primary" value="novo" title="Novo" onclick="location.href='?pg=times&op=novo';">Novo</button>
		</td>
	</tr>
	<tr>
		<td>
		<center>
        <table cellpadding='4' class="table table-hover table-striped table-fixed">
            <tr>
                <td width='30'><b>#</b></td>
                <td width='70'><b>Batalha</b></td>
                <td width='50'><b>Fase</b></td>
                <td width='80'><b>Nome</b></td>
                <td width='420'><b>Time</b></td>
                <td></td>
            </tr>

            <?php
            //deleta usuarios
            if (!empty($_GET['delete'])){
                $delUserId = $_GET['delete'];

				$sql = "DELETE FROM times WHERE id = '$delUserId'" ;
				$PDO->query( $sql );
				echo '<span class="ms ok">Pronto! Time deletado com sucesso!</span>';
				echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=times">';
            }

            //leitura da tabela times
			$sql = "SELECT * FROM times ORDER BY raid, fase ASC";
			$readUser = $PDO->query( $sql );
			$num = 0;
			if ($readUser->rowCount() < 1) {
                echo '<span class="ms no">Oppss! Não existem times cadastrados no momento!!</span>';
            } else {
                foreach ($readUser as $rows){
					$num++;
                    ?>
                    <tr>
                        <td><?php echo $num; ?></td>
                        <td><?php echo strtoupper($rows['raid']); ?></td>
                        <td><center><?php echo $rows['fase']; ?></center></td>
                        <td><?php echo $rows['nome']; ?></td>
                        <td>
							<?php echo $rows['char_1']; ?><?php echo ($rows['zeta_1'] == 1 ? " (Z)" : ""); ?> - Líder
							<?php echo ($rows['char_2'] == "" ? "" : "<br>"); ?>
							<?php echo $rows['char_2']; ?><?php echo ($rows['zeta_2'] == 1 ? " (Z)" : ""); ?>
							<?php echo ($rows['char_3'] == "" ? "" : "<br>"); ?>
							<?php echo $rows['char_3']; ?><?php echo ($rows['zeta_3'] == 1 ? " (Z)" : ""); ?>
							<?php echo ($rows['char_4'] == "" ? "" : "<br>"); ?>
							<?php echo $rows['char_4']; ?><?php echo ($rows['zeta_4'] == 1 ? " (Z)" : ""); ?>
							<?php echo ($rows['char_5'] == "" ? "" : "<br>"); ?>
							<?php echo $rows['char_5']; ?><?php echo ($rows['zeta_5'] == 1 ? " (Z)" : ""); ?>
						</td>
                        <td>
							<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
                            <a href="?pg=times&op=editar&id=<?php echo $rows['id']; ?>"><img src='images/edit.png'></a>
                            <a href="?pg=times&delete=<?php echo $rows['id']; ?>"><img src='images/del.png'></a>
							<?php } else { ?>
							&nbsp;
							<?php } ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
		</center>
		</td>
	</tr>
</table>
<?php
} elseif ($op == "novo" OR $op == "editar") {
// ==========================
// NOVO/EDITAR USUARIO
// ==========================

?>
<table width='700'>
	<tr>
		<td><b><center> <?php echo ($op == "editar" ? "Editar" : "Adicionar"); ?> Time</center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
		if (isset($_POST['enviar'])){
			unset($data['enviar']);

			if ($op == "editar") {
				$sql = update('times', $data, "id = '$id'");
			} else {
				$sql = create('times', $data);
			}
			
			$PDO->query( $sql );
			echo '<span class="ms ok">Pronto! Time salvo com sucesso!</span>';
			echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=times">';
		}
		if ($op == "editar") {
			$sql = "SELECT * FROM times WHERE id = '".$id."' ORDER BY raid LIMIT 1";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table class="table table-hover table-striped table-fixed">
				<tr>
					<td><b>Batalhas:</b></td>
					<td colspan='3'>
						<input type="radio" name="raid" value="rancor" <?=(($user['raid'] == 'rancor') ? "checked" : "");?>> Rancor &nbsp;&nbsp;
						<input type="radio" name="raid" value="tanque"  <?=(($user['raid'] == 'tanque') ? "checked" : "");?>> Tanque &nbsp;&nbsp;
						<input type="radio" name="raid" value="sith"  <?=(($user['raid'] == 'sith') ? "checked" : "");?>> Sith &nbsp;&nbsp;
						<br>
						<input type="radio" name="raid" value="tb-hoth"  <?=(($user['raid'] == 'tb-hoth' OR $op == "") ? "checked" : "");?>> TB-LS &nbsp;&nbsp;
						<input type="radio" name="raid" value="tb-ds"  <?=(($user['raid'] == 'tb-ds' OR $op == "") ? "checked" : "");?>> TB-DS &nbsp;&nbsp;
						<input type="radio" name="raid" value="tw-def"  <?=(($user['raid'] == 'tb-def' OR $op == "novo") ? "checked" : "");?>> TW-Def. &nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<td><b>Fase:</b></td>
					<td colspan='3'>
						<input type="radio" name="fase" value="1" <?=(($user['fase'] == 1 OR $op == "novo") ? "checked" : "");?>> 1 &nbsp;&nbsp;
						<input type="radio" name="fase" value="2" <?=(($user['fase'] == 2) ? "checked" : "");?>> 2 &nbsp;&nbsp;
						<input type="radio" name="fase" value="3" <?=(($user['fase'] == 3) ? "checked" : "");?>> 3 &nbsp;&nbsp;
						<input type="radio" name="fase" value="4" <?=(($user['fase'] == 4) ? "checked" : "");?>> 4 &nbsp;&nbsp;
						<input type="radio" name="fase" value="5" <?=(($user['fase'] == 5) ? "checked" : "");?>> 5 &nbsp;&nbsp;
						<input type="radio" name="fase" value="6" <?=(($user['fase'] == 6) ? "checked" : "");?>> 6
					</td>
				</tr>
				<tr>
					<td><b>Nome:</b></td>
					<td colspan='3'><input type="text" name="nome" value="<?php if (isset($user['nome'])) echo $user['nome']; ?>" style='width:400px'/></td>
				</tr>
				<?php
				for ($c = 1; $c <= 5; $c++) {
				?>
				<tr>
					<td><b><?=(($c == 1) ? "Líder" : "Char ".$c);?>:</b></td>
					<td>
						<select name="char_<?=$c;?>">
							<option value="" <?=(($user['char'] == "") ? "selected" : "");?>>--</option>
						<?php
							$sql_char = "SELECT * FROM characters ORDER BY name";
							$chars = $PDO->query( $sql_char );
							while ($char = $chars->fetch( PDO::FETCH_ASSOC )) {
								echo "<option value='".$char['name']."' ".($user['char_'.$c] == $char['name'] ? "selected" : "").">".$char['name']."</option>";
							}
						?>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td><b>Zeta <?=$c;?>:</b></td>
					<td width='150'>
						<input type="radio" name="zeta_<?=$c;?>" value="0" <?=(($user['zeta_'.$c] == 0 OR $op == "novo") ? "checked" : "");?>> Não &nbsp;&nbsp;
						<input type="radio" name="zeta_<?=$c;?>" value="1" <?=(($user['zeta_'.$c] == 1) ? "checked" : "");?>> Sim
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td></td>
					<td colspan='3'>
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