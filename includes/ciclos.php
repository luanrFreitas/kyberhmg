<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Ciclos</h1>
							<h2><span>Rotação | Período</span></h2>
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
					<h2>Ciclos</h2>
					<p>
<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];
if (is_null($_POST['inicio'])) $inicio = date("Y-m-d", strtotime("now")); else $inicio = $_POST['inicio'];
if (is_null($_POST['termino'])) $termino = date("Y-m-d", strtotime("+13 days")); else $termino = $_POST['termino'];

if ($op == "lista") {
// ==========================
// LISTAR CICLOS 
// ==========================
?>
<table width='600' class="table table-hover table-striped table-fixed">
	<tr>
		<td><b><center> Listar Ciclos</center></b></td>
	</tr>
	<tr>
		<td style="text-align: right;" colspan='5'>
			<button type="button" name="novo" class="btn btn-primary" value="novo" title="Novo" onclick="AddNovo()">Novo</button>
		</td>
	</tr>
	<tr>
		<td>
        <table width='600' class="table table-hover table-striped table-fixed">
            <tr>
                <td>#</td>
                <td width='120' style="text-align: center;"><b>Início</b></td>
                <td width='120' style="text-align: center;"><b>Término</b></td>
                <td></td>
            </tr>

            <?php
            //deleta usuarios
            if (!empty($_GET['delete'])){
                $delCicloId = $_GET['delete'];
                $userId = $_SESSION['autUser']['id'];

				$sql = "DELETE FROM ciclos WHERE id = '$delCicloId'" ;
				$PDO->query( $sql );
				echo '<span class="ms ok">Pronto! Ciclo deletado com sucesso!</span>';
				echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclos">';
            }

            //leitura da tabela ciclos
			$x = 0;
			$opt_new = 0;
			$sql = "SELECT * FROM ciclos ORDER BY inicio DESC";
			$readUser = $PDO->query( $sql );
			if ($readUser->rowCount() < 1) {
                echo '<span class="ms no">Oppss! Não existe usuários cadastrados no momento!!</span>';
            } else {
                foreach ($readUser as $rows){
					$x++;
                    ?>
                    <tr>
                        <td><?php echo $x; ?></td>
                        <td style="text-align: center;"><?=date("d/m/Y", strtotime($rows['inicio'])); ?></td>
                        <td style="text-align: center;"><?=date("d/m/Y", strtotime($rows['termino'])); ?></td>
                        <td>
							<?php 
							if ($_SESSION['autUser']['nivel'] == 2 AND $rows['status'] == 1 ) {
								$opt_img = "edit.png";
								//$opt_op  = "editar";
								$opt_op  = "ver";
								$opt_new = $rows['id'];
							} else {
								$opt_img = "view.ico";
								$opt_op  = "ver";
							}
							echo "<a href='?pg=ciclos&op=".$opt_op."&id=".$rows['id']."'><img src='images/".$opt_img."'></a>";
							?>
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
// ==========================
// FINALIZAR CICLO
// ==========================
} elseif ($op == "finalizar" AND $id > 0) {
	require 'includes/rank_dados.php'; 
	$resultado['times'] = $times;
	$resultado['rank']  = $rank;
	$resultado['char']  = $char;
	$resultado['chars'] = $chars;
	$dados = str_replace("'", "\'", serialize($resultado));

	$sql = "UPDATE ciclos SET status='0', resultado='".$dados."' WHERE id = '".$id."'";

	$PDO->query( $sql );
	echo "\nPDO::errorCode(): ", $PDO->errorCode();
	echo '<span class="ms ok">Pronto! Ciclo finalizado com sucesso!</span>';
	echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclos&op=novo">';

// ==========================
// NOVO/VER CICLO
// ==========================
} elseif ($op == "novo" OR $op == "ver") {
	$sql = "SELECT * FROM ciclos WHERE status = '1'";
	$readUser = $PDO->query( $sql );
	if ($readUser->rowCount() >= 1 AND $op == "novo") {
		echo '<span class="ms ok">Impossível criar ciclo novo!<br>Finalize os ciclos em aberto antes!</span>';
		echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclos">';
	}

?>
<table width='600' class="table table-hover table-striped table-fixed">
	<tr>
		<td><b><center> <?php echo ($op == "editar" ? "Editar" : "Adicionar"); ?> Ciclo</center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (isset($_POST['enviar'])){
			unset($data['enviar']);

			require 'includes/rank_dados.php'; 
			$resultado['times'] = $times;
			$resultado['rank']  = $rank;
			$resultado['char']  = $char;
			$resultado['chars'] = $chars;
			$data['resultado'] = str_replace("'", "\'", serialize($resultado));

			if ($op == "editar") {
				if (empty($data['inicio'])) {
					echo '<span class="ms no">Oppss! Nome está em branco!</span>';
				} elseif (empty($data['termino'])) {
					echo '<span class="ms no">Oppss! Nome está em branco!</span>';
				} else {
					//verifica se o ciclo existe no banco de dados e nao deixa cadastrar
					$sql = "SELECT * FROM ciclos WHERE inicio = '".$data['inicio']."' AND termino = '".$data['termino']."' AND id != '".$id."'";
					$readUser = $PDO->query( $sql );
					if ($readUser->rowCount() >= 1) {
						echo '<span class="ms no">Oppss! Inicio e Término já existem no sistema. Por favor, cadastre um diferente!</span>';
					} else {
						$sql = update('ciclos', $data, "id = '$id'");
						$PDO->query( $sql );
						//echo "\nPDO::errorCode(): ", $PDO->errorCode();
						echo '<span class="ms ok">Pronto! Ciclo salvo com sucesso!</span>';
						echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclos">';
					}
				}
			} else {
				//valida os campos 
				if (in_array('', $data)) {
					echo '<span class="ms no">Oppss! Por favor, preencha todos os campos!</span>';
				} else {
					
					
					//faz a leitura verificando se já existe o mesmo ciclo
					$sql = "SELECT * FROM ciclos WHERE inicio = '$data[inicio]' AND termino = '$data[termino]'";
					$readUser = $PDO->query( $sql );
					if ($readUser->rowCount() >= 1) {
						echo '<span class="ms no">Oppss! Inicio e Término já existem no sistema. Por favor, cadastre um diferente!</span>';
					} else {
						$sql = create('ciclos', $data);
						$PDO->query( $sql );
						echo '<span class="ms ok">Pronto! Ciclo salvo com sucesso!</span>';
						echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclos">';
					}
				}
			}
		}
		if ($op == "editar" OR $op == "ver") {
			$sql = "SELECT * FROM ciclos WHERE id = '".$id."' LIMIT 1";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table width='600' class="table table-hover table-striped table-fixed">
				<tr>
					<td width='70'>Início:</td>
					<td width='150'>
						<?php if ($op == "ver")
							echo date("d/m/Y", strtotime($user['inicio']));
						else 
							echo "<input type='date' name='inicio' value='".((isset($user['inicio'])) ? $user['inicio']: $inicio)."' style='width:150px'/>";
						?>
					</td>
				</tr>
				<tr>
					<td>Término:</td>
					<td>
						<?php if ($op == "ver")
							echo date("d/m/Y", strtotime($user['termino']));
						else 
							echo "<input type='date' name='termino' value='".((isset($user['termino'])) ? $user['termino']: $termino)."' style='width:150px'/>";
						?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td style="float:right">
					<?php if ($op == "ver") { ?>
						<button type="button" name="voltar" class="btn btn-primary" value="Voltar" title="Voltar" onclick="location.href='?pg=ciclos';">Voltar</button>
					<?php } else { ?>
						<input type="hidden" name="resultado" value="1">
						<input type="hidden" name="status" value="1">
						<button type="submit" name="enviar" class="btn btn-primary" value="Salvar" title="Salvar">Salvar</button>
					<?php } ?>
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

<?php
if ($op == "ver") {
	if ($_SESSION['autUser']['nivel'] == 2 AND $rows['status'] == 1 ) {
		require 'includes/rank_dados.php'; 
	} else {
		$resultado = str_replace("\'", "'", unserialize($user['resultado']));
		$times = $resultado['times'];
		$rank = $resultado['rank'];
		$char = $resultado['char'];
		$chars = $resultado['chars'];
	}
	require 'includes/rank_tabela.php'; 
}
?>

<script>
function AddNovo() {
	<?php if ($opt_new >= 1) { ?>
    var r = confirm("Ainda existe ciclo em aberto.\n\n ##ATENÇÃO##\n\nFinalizar o ciclo anterior??");
	if (r == true) { location.href='?pg=ciclos&op=finalizar&id=<?=$opt_new;?>'; }
	<?php } else { ?>
	location.href='?pg=ciclos&op=novo';
	<?php } ?>
}
</script>