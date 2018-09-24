<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Guildas</h1>
							<h2><span>União | Kyber</span></h2>
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
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Lista de Guildas</h2>
					<p>
<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];

if ($op == "lista") { 
// ==========================
// LISTAR GUILDAS 
// ==========================
?>
<table width='700' class="table table-hover table-striped table-fixed">
	<td style="text-align: right;" colspan='5'>
		<button type="button" name="novo" class="btn btn-primary" value="novo" title="Novo" onclick="location.href='?pg=guildas&op=novo';">Novo</button>
	</td>

	<tr>
		<td>
        <table cellpadding='4' width='700' class="table table-hover table-striped table-fixed">
            <tr>
                <td width='350'><b>Nome</b></td>
                <td width='150'><b>Tipo</b></td>
                <td width='150'><b>Status</b></td>
                <td></td>
            </tr>

            <?php
            //deleta usuarios
            if (!empty($_GET['delete'])){
                $delUserId = $_GET['delete'];
                $userId = $_SESSION['autUser']['id'];

                if ($delUserId == $userId){
                    echo '<span class="ms no">Oppss! Você não pode deletar seu perfil!</span>';
                } else {
					$sql = "DELETE FROM guildas WHERE id = '$delUserId'" ;
					$PDO->query( $sql );
                    echo '<span class="ms ok">Pronto! Guilda deletado com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=guildas">';
                }
            }

            //leitura da tabela users
			$sql = "SELECT * FROM guildas ORDER BY nome ASC";
			$readUser = $PDO->query( $sql );
			if ($readUser->rowCount() < 1) {
                echo '<span class="ms no">Oppss! Não existe guildas cadastradas no momento!!</span>';
            } else {
                foreach ($readUser as $rows){
                    $status	= ($rows['ativo'] == 1 ? 'Ativa'  : 'Inativa');
                    $tipo	= ($rows['tipo'] == 'H' ? 'Heróica': ($rows['tipo'] == 'C' ? 'Casual': 'Normal'));
                    ?>
                    <tr>
                        <td><?php echo $rows['nome']; ?></td>
                        <td><?php echo $tipo; ?></td>
                        <td><?php echo $status; ?></td>
                        <td>
							<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
                            <a href="?pg=guildas&op=editar&id=<?php echo $rows['id']; ?>"><img src='images/edit.png'></a>
							<?php } else { ?>
							&nbsp;&nbsp;
							<?php } ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
		</td>
	</tr>
</table>
<?php
} elseif ($op == "novo" OR $op == "editar") {
// ==========================
// NOVO/EDITAR GUILDA
// ==========================

?>
<table width='700' class="table table-hover table-striped table-fixed">
	<tr>
		<td><b><center> <?php echo ($op == "editar" ? "Editar" : "Adicionar"); ?> Guilda</center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (isset($_POST['enviar'])){
			unset($data['enviar']);

			if ($op == "editar") {
				if (empty($data['nome'])) {
					echo '<span class="ms no">Oppss! Nome está em branco!</span>';
				} else {
					$sql = update('guildas', $data, "id = '$id'");
					$PDO->query( $sql );
					echo '<span class="ms ok">Pronto! Guilda salva com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=guildas">';
				}
			} else {
				//valida os campos 
				if (in_array('', $data)) {
					echo '<span class="ms no">Oppss! Por favor, preencha todos os campos!</span>';
				} else {
					$sql = create('guildas', $data);
					$PDO->query( $sql );
					echo '<span class="ms ok">Pronto! Guilda salvo com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=guildas">';
				}
			}
		}
		if ($op == "editar") {
			$sql = "SELECT * FROM guildas WHERE id = '".$id."' ORDER BY nome LIMIT 1";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table>
				<tr>
					<td width='100'>Nome:</td>
					<td width='450'><input type="text" name="nome" value="<?php if (isset($user['nome'])) echo $user['nome']; ?>" style='width:450px'/></td>
				</tr>
				<tr>
					<td>Link no swgoh:</td>
					<td>https://swgoh.gg/g/<input type="text" name="link" value="<?php if (isset($user['link'])) echo $user['link']; ?>" style='width:250px'/></td>
				</tr>
				<tr>
					<td>WebHook TB:</td>
					<td width='450'><input type="text" name="webhook_tb" value="<?php if (isset($user['webhook_tb'])) echo $user['webhook_tb']; ?>" style='width:450px'/></td>
				</tr>
				<tr>
					<td>Descrição:</td>
					<td><textarea name="canais" rows="7" cols="50" style='width:450px'><?php if (isset($user['canais'])) echo $user['canais']; ?></textarea></td>
				</tr>
				<tr>
					<td>Tipo:</td>
					<td>
						<select name="tipo" style='width:150px; height:30px'>
							<option value="H" <?php if (isset($user['tipo']) && $user['tipo'] == 'H') echo 'selected="selected"'; ?>>Heróica</option>
							<option value="N" <?php if (isset($user['tipo']) && $user['tipo'] == 'N') echo 'selected="selected"'; ?>>Normal</option>
							<option value="C" <?php if (isset($user['tipo']) && $user['tipo'] == 'C') echo 'selected="selected"'; ?>>Casual</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Status:</td>
					<td>
						<select name="ativo" style='width:150px; height:30px'>
							<option value="1" <?php if (isset($user['ativo']) && $user['ativo'] == 1) echo 'selected="selected"'; ?>>Ativa</option>
							<option value="2" <?php if (isset($user['ativo']) && $user['ativo'] == 2) echo 'selected="selected"'; ?>>Inativa</option>
						</select>
					</td>
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