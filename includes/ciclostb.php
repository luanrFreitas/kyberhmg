<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];
if (is_null($_POST['inicio'])) $inicio = date("Y-m-d", strtotime("now")); else $inicio = $_POST['inicio'];
if (is_null($_POST['termino'])) $termino = date("Y-m-d", strtotime("+14 days")); else $termino = $_POST['termino'];

if ($op == "lista") { 
// ==========================
// LISTAR CICLOS 
// ==========================
?>
<table>
	<tr>
		<td><b><center> Ciclos da Territory Battle</center></b></td>
	</tr>
	<tr>
		<td style="text-align: right;"><b><a href="?pg=ciclostb&op=novo">Novo</b></b></td>
	</tr>
	<tr>
		<td>
        <table cellpadding='4'>
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

				$sql = "DELETE FROM ciclostb WHERE id = '$delCicloId'" ;
				$PDO->query( $sql );
				echo '<span class="ms ok">Pronto! Ciclo deletado com sucesso!</span>';
				echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclostd">';
            }

            //leitura da tabela ciclos
			$x = 0;
			$sql = "SELECT * FROM ciclostb ORDER BY inicio, termino DESC";
			$readUser = $PDO->query( $sql );
			if ($readUser->rowCount() < 1) {
                echo '<span class="ms no">Oppss! Não existe ciclos cadastrados no momento!!</span>';
            } else {
                foreach ($readUser as $rows){
					$x++;
                    ?>
                    <tr>
                        <td><?php echo $x; ?></td>
                        <td style="text-align: center;"><?php echo date("d/m/Y", strtotime($rows['inicio'])); ?></td>
                        <td style="text-align: center;"><?php echo date("d/m/Y", strtotime($rows['termino'])); ?></td>
                        <td>
							<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
                            <a href="?pg=ciclostb&op=editar&id=<?php echo $rows['id']; ?>"><img src='images/edit.png'></a>
							&nbsp;&nbsp;
                            <a href="?pg=ciclostb&delete=<?php echo $rows['id']; ?>"><img src='images/del.png'></a>
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
// NOVO/EDITAR CICLO
// ==========================

?>
<table width='600'>
	<tr>
		<td><b><center> <?php echo ($op == "editar" ? "Editar" : "Adicionar"); ?> Ciclo</center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (isset($_POST['enviar'])){
			unset($data['enviar']);

			if ($op == "editar") {
				if (empty($data['inicio'])) {
					echo '<span class="ms no">Oppss! Data de Início está em branco!</span>';
				} elseif (empty($data['termino'])) {
					echo '<span class="ms no">Oppss! Data de Término está em branco!</span>';
				} else {
					//verifica se o ciclo existe no banco de dados e nao deixa cadastrar
					$sql = "SELECT * FROM ciclostb WHERE inicio = '$data[inicio]' AND termino = '$data[termino]' AND id != '$id'";
					$readUser = $PDO->query( $sql );
					if ($readUser->rowCount() >= 1) {
						echo '<span class="ms no">Oppss! Inicio e Término já existem no sistema. Por favor, cadastre um diferente!</span>';
					} else {
						$sql = update('ciclostb', $data, "id = '$id'");
						$PDO->query( $sql );
						echo '<span class="ms ok">Pronto! Ciclo salvo com sucesso!</span>';
						echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclostb">';
					}
				}
			} else {
				//valida os campos 
				if (in_array('', $data)) {
					echo '<span class="ms no">Oppss! Por favor, preencha todos os campos!</span>';
				} else {
					//faz a leitura verificando se já existe o mesmo ciclo
					$sql = "SELECT * FROM ciclostb WHERE inicio = '$data[inicio]' AND termino = '$data[termino]'";
					$readUser = $PDO->query( $sql );
					if ($readUser->rowCount() >= 1) {
						echo '<span class="ms no">Oppss! Inicio e Término já existem no sistema. Por favor, cadastre um diferente!</span>';
					} else {
						$sql = create('ciclostb', $data);
						$PDO->query( $sql );
						echo '<span class="ms ok">Pronto! Ciclo salvo com sucesso!</span>';
						echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=ciclostb">';
					}
				}
			}
		}
		if ($op == "editar") {
			$sql = "SELECT * FROM ciclostb WHERE id = '".$id."' LIMIT 1";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table>
				<tr>
					<td width='70'>Início:</td>
					<td width='150'><input type="date" name="inicio" value="<?php if (isset($user['inicio'])) echo $user['inicio']; else echo $inicio; ?>" style='width:150px'/></td>
				</tr>
				<tr>
					<td>Término:</td>
					<td><input type="date" name="termino" value="<?php if (isset($user['termino'])) echo $user['termino']; else echo $termino; ?>" style='width:150px'/></td>
				</tr>
				<tr>
					<td></td>
					<td style="float:right"><input type="submit" name="enviar" value="Salvar"></td>
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