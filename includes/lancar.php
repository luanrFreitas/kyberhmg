<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d"); else $dt = $_GET['dt'];

// ==========================
// NOVA RAID
// ==========================
?>
<table width='600'>
	<tr>
		<td><b><center> Adicionar Raid </center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (isset($_POST['enviar'])){
			unset($data['enviar']);
			
			//valida os campos 
			//faz a leitura verificando se existe o raid cadastrada
			$sql = "SELECT * FROM raid_lancada WHERE (idguilda = '$data[idguilda]' AND data = '$data[data]' AND tipo = '$data[tipo]')";
			$readUser = $PDO->query( $sql );
			if ($readUser->rowCount() >= 1) {
				echo '<span class="ms no">Oppss! Já existe uma raid igual cadastrada no sistema. Por favor, cadastre uma diferente!</span>';
			} else {
				//armazena a data e senha para gravar no banco
				$data['idguilda'] = $gd;
				$data['data'] = $data['data'];
				$data['tipo'] = $data['tipo'];

				//grava no banco
				$sql = create('raid_lancada', $data);
				$PDO->query( $sql );
				echo '<span class="ms ok">Pronto! Raid salva com sucesso!</span>';
				echo "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"1;URL=?pg=lancar&gd=".$gd."\">";
			}
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table>
				<tr>
					<td>Raid:</td>
					<td width='150'>
						<input type="radio" name="tipo" value="rancor" checked> Rancor<br>
						<input type="radio" name="tipo" value="tanque"> Tanque<br>
						<input type="radio" name="tipo" value="bonus"> Bônus
					</td>
					<td width='20'>&nbsp;</td>
					<td>Data:</td>
					<td><input type='date' value='<?php echo $dt; ?>' name='data' id='data'></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td style="float:right"><input type="submit" name="enviar" value="Salvar"></td>
				</tr>
			</table>
			</form>
		</center>

		</td>
	</tr>
</table>

<br>
<?php
// ==========================
// LISTAR USUARIOS 
// ==========================
?>
<table width='600'>
	<tr>
		<td><b><center> Lista de Raids (Últimas 30) </center></b></td>
	</tr>
	<tr>
		<td><center>
        <table cellpadding='4'>
            <tr>
                <td width='100'><b>Data</b></td>
                <td width='100'><b>Raid</b></td>
                <td></td>
            </tr>

            <?php
            //deleta usuarios
            if (!empty($_GET['delete'])){
                $delUserId = $_GET['delete'];
                $userId = $_SESSION['autUser']['idguilda'];

                if ($delUserId == $userId){
                    echo '<span class="ms no">Oppss! Você não pode deletar esta Raid!</span>';
                } else {
					$sql = "DELETE FROM raid_lancada WHERE id = '$delUserId'";
					$PDO->query( $sql );
                    echo '<span class="ms ok">Pronto! Raid deletado com sucesso!</span>';
					echo "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"3;URL=?pg=lancar&gd=".$gd."\">";
                }
            }

            //leitura da tabela
			$sql = "SELECT * FROM raid_lancada WHERE idguilda = '".$gd."' ORDER BY data DESC limit 30";
			$readUser = $PDO->query( $sql );
			if ($readUser->rowCount() < 1) {
                echo '<span class="ms no">Oppss! Não existe raid cadastrada no momento!!</span>';
            } else {
                foreach ($readUser as $rows){
                    ?>
                    <tr>
                        <td><?php echo date("d/m/Y", strtotime($rows['data'])); ?></td>
                        <td><?php echo ucfirst($rows['tipo']); ?></td>
                        <td>
                            <a href="?pg=lancar&gd=<?php echo $gd; ?>&delete=<?php echo $rows['id']; ?>"><img src='images/del.png'></a>
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
