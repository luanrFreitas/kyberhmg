<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Institucional</h1>
							<h2><span>Textos</span></h2>
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
					<h2>Textos</h2>
					<p>
<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];

if ($op == "lista") { 
// ==========================
// LISTAR TEXTOS 
// ==========================
?>
<table width='700' class="table table-hover table-striped table-fixed">
	<td style="text-align: right;" colspan='5'>
		<button type="button" name="novo" class="btn btn-primary" value="novo" title="Novo" onclick="location.href='?pg=textos&op=novo';">Novo</button>
	</td>

	<tr>
		<td>
        <table cellpadding='4' width='700' class="table table-hover table-striped table-fixed">
            <tr>
                <td width='30'><b>id</b></td>
                <td width='640'><b>Título</b></td>
                <td width='30'></td>
            </tr>

            <?php
            //deleta usuarios
            if (!empty($_GET['delete'])){
                $delUserId = $_GET['delete'];
                $userId = $_SESSION['autUser']['id'];

                if ($delUserId == $userId){
                    echo '<span class="ms no">Oppss! Você não pode deletar seu perfil!</span>';
                } else {
					$sql = "DELETE FROM institucional WHERE id = '$delUserId'" ;
					$PDO->query( $sql );
                    echo '<span class="ms ok">Pronto! Texto deletado com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=textos">';
                }
            }

            //leitura da tabela users
			$sql = "SELECT * FROM institucional ORDER BY titulo ASC";
			$readUser = $PDO->query( $sql );
			if ($readUser->rowCount() < 1) {
                echo '<span class="ms no">Oppss! Não existe textos cadastrados no momento!!</span>';
            } else {
                foreach ($readUser as $rows){
                    ?>
                    <tr>
                        <td><?php echo $rows['id']; ?></td>
                        <td><?php echo $rows['titulo'] ?></td>
                        <td>
							<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
                            <a href="?pg=textos&op=editar&id=<?php echo $rows['id']; ?>"><img src='images/edit.png'></a>
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
				if (empty($data['titulo'])) {
					echo '<span class="ms no">Oppss! O título está em branco!</span>';
				} else {
					$sql = update('institucional', $data, "id = '$id'");
					$PDO->query( $sql );
					echo '<span class="ms ok">Pronto! Guilda salva com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=textos">';
				}
			} else {
				//valida os campos 
				if (in_array('', $data)) {
					echo '<span class="ms no">Oppss! Por favor, preencha todos os campos!</span>';
				} else {
					$sql = create('institucional', $data);
					$PDO->query( $sql );
					echo '<span class="ms ok">Pronto! Guilda salvo com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=textos">';
				}
			}
		}
		if ($op == "editar") {
			$sql = "SELECT * FROM institucional WHERE id = '".$id."' ORDER BY titulo LIMIT 1";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table width='700'>
				<tr>
					<td width='60'>Título:</td>
					<td width='640'><input type="text" name="titulo" value="<?php if (isset($user['titulo'])) echo $user['titulo']; ?>" style='width:450px'/></td>
				</tr>
				<tr>
					<td>Texto:</td>
					<td>
						<textarea name="texto" id="texto" rows="40" cols="90" style="height: 200px"><?php if (isset($user['texto'])) echo $user['texto']; ?></textarea>
						<script>
							// Replace the <textarea id="texto"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.replace( 'texto' );
							CKEDITOR.config.height = 450;
						</script>
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