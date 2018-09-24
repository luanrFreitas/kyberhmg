<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Usuários</h1>
							<h2><span>Oficial | Administrador</span></h2>
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
					<h2>Usuários</h2>
					<p>
<?php
if (is_null($_GET['op'])) $op = "lista"; else $op = $_GET['op'];
if (is_null($_GET['id'])) $id = 0;		 else $id = $_GET['id'];

if ($id == 1) $op = "lista";

if ($op == "lista") { 
// ==========================
// LISTAR USUARIOS 
// ==========================
?>
<table width='600' class="table table-hover table-striped table-fixed" style="font-size: 13px">
	<tr>
		<td style="text-align: right;" colspan='5'>
			<button type="button" name="novo" class="btn btn-primary" value="novo" title="Novo" onclick="location.href='?pg=users&op=novo';">Novo</button>
		</td>
	</tr>
	<tr>
		<td><b>Nome</b></td>
		<td><b>Login</b></td>
		<td><b>Nível</b></td>
		<td><b>Status</b></td>
		<td width='80'></td>
	</tr>

	<?php
	//deleta usuarios
	if (!empty($_GET['delete'])){
		$delUserId = $_GET['delete'];
		$userId = $_SESSION['autUser']['id'];

		if ($delUserId == $userId){
			echo '<span class="ms no">Oppss! Você não pode deletar seu perfil!</span>';
		} else {
			$sql = "DELETE FROM users WHERE id = '$delUserId'" ;
			$PDO->query( $sql );
			echo '<span class="ms ok">Pronto! Usuario deletado com sucesso!</span>';
			echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=users">';
		}
	}

	//leitura da tabela users
	$sql = "SELECT * FROM users WHERE id != '1' ORDER BY nome ASC";
	$readUser = $PDO->query( $sql );
	if ($readUser->rowCount() < 1) {
		echo '<span class="ms no">Oppss! Não existe usuários cadastrados no momento!!</span>';
	} else {
		foreach ($readUser as $rows){
			$ico	= ($rows['status'] == 1 ? 'active' : 'inactive');
			$status	= ($rows['status'] == 1 ? 'Ativo'  : 'Bloqueado');
			$nivel	= ($rows['nivel']  == 1 ? 'Oficial': 'Administrador');
			?>
			<tr>
				<td><?php echo $rows['nome']; ?></td>
				<td><?php echo $rows['login']; ?></td>
				<td><?php echo $nivel; ?></td>
				<td><?php echo $status; ?></td>
				<td>
					<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
					<a href="?pg=users&op=editar&id=<?php echo $rows['id']; ?>"><img src='images/edit.png'></a>
					<a href="?pg=users&delete=<?php echo $rows['id']; ?>"><img src='images/del.png'></a>
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

<?php
} elseif ($op == "novo" OR $op == "editar") {
// ==========================
// NOVO/EDITAR USUARIO
// ==========================

?>
<table width='600' class="table table-hover table-striped table-fixed">
	<tr>
		<td><b><center> <?php echo ($op == "editar" ? "Editar" : "Adicionar"); ?> Usuário</center></b></td>
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
				} elseif (empty($data['email'])) {
					echo '<span class="ms no">Oppss! E-mail está em branco!</span>';
				} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
					echo '<span class="ms no">Oppss! E-mail inválido. Por favor, coloque um e-mail válido!</span>';
				} else {
					//verifica se o email existe no banco de dados e nao deixa cadastrar
					$sql = "SELECT * FROM users WHERE (email = '$data[email]' OR login = '$data[login]') AND id != '$id'";
					$readUser = $PDO->query( $sql );
					if ($readUser->rowCount() >= 1) {
						echo '<span class="ms no">Oppss! Login e/ou E-mail já existe no sistema. Por favor, cadastre um diferente!</span>';
					} else {
						//remove a repetição de senha
						//unset($data['senha_re']);

						//armazena a data e senha para gravar no banco
						$data['data'] = date("Y-m-d H:i:s");
						$data['code'] = $data['senha'];
						$data['senha'] = md5($data['code']);
						//$data['code'] = "";

						//verifica se a senha esta em branco para nao gravar no banco
						if (empty($data['code'])) {
							unset($data['senha']);
							unset($data['code']);
						}

						$sql = update('users', $data, "id = '$id'");
						$PDO->query( $sql );
						echo '<span class="ms ok">Pronto! Users salvo com sucesso!</span>';
						echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=users">';
					}
				}
			} else {
				//valida os campos 
				if (in_array('', $data)) {
					echo '<span class="ms no">Oppss! Por favor, preencha todos os campos!</span>';
				} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
					echo '<span class="ms no">Oppss! E-mail inválido. Por favor, coloque um e-mail válido!</span>';
				//} elseif ($data['senha'] != $data['senha_re']) {
				//	echo '<span class="ms no">Oppss! Por favor, repita a senha igual o campo senha!</span>';
				} else {
					//faz a leitura verificando se existe o mesmo e-mail
					$sql = "SELECT * FROM users WHERE (email = '$data[email]' OR login = '$data[login]')";
					$readUser = $PDO->query( $sql );
					if ($readUser->rowCount() >= 1) {
						echo '<span class="ms no">Oppss! Login e/ou E-mail já existe no sistema. Por favor, cadastre um diferente!</span>';
					} else {
						//remove a repetição de senha
						//unset($data['senha_re']);

						//armazena a data e senha para gravar no banco
						$data['data'] = date("Y-m-d H:i:s");
						$data['code'] = $data['senha'];
						$data['senha'] = md5($data['code']);
						//$data['code'] = "";

						//grava no banco
						$sql = create('users', $data);
						$PDO->query( $sql );
						echo '<span class="ms ok">Pronto! Usuario salvo com sucesso!</span>';
						echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=users">';
					}
				}
			}
		}
		if ($op == "editar") {
			$sql = "SELECT * FROM users WHERE id = '".$id."' ORDER BY nome LIMIT 1";
			$editar = $PDO->query( $sql );
			$user = $editar->fetch(PDO::FETCH_ASSOC);
		}
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table width='700' class="table table-hover table-striped table-fixed">
				<tr>
					<td width='70'>Nome:</td>
					<td width='300'><input type="text" name="nome" value="<?php if (isset($user['nome'])) echo $user['nome']; ?>" style='width:300px'/></td>
				</tr>
				<tr>
					<td>Login:</td>
					<td><input type="text" name="login" value="<?php if (isset($user['login'])) echo $user['login']; ?>" style='width:300px'/></td>
				</tr>
				<tr>
					<td>E-mail:</td>
					<td><input type="text" name="email" value="<?php if (isset($user['email'])) echo $user['email']; ?>" style='width:300px'></td>
				</tr>
				<tr>
					<td>Senha:</td>
					<td><input type="password" name="senha" value="" style='width:150px'></td>
				</tr>
				<!--
				<tr>
					<td>Repita a senha:</td>
					<td><input type="password" name="senha_re" value=""></td>
				</tr>
				-->
				<tr>
					<td>Status:</td>
					<td>
						<select name="status" style='width:150px; height:30px'>
							<option value="1" <?php if (isset($user['status']) && $user['status'] == 1) echo 'selected="selected"'; ?>>Ativo</option>
							<option value="2" <?php if (isset($user['status']) && $user['status'] == 2) echo 'selected="selected"'; ?>>Bloqueado</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Nivel:</td>
					<td>
						<select name="nivel" style='width:150px; height:30px'>
							<option value="1" <?php if (isset($user['nivel']) && $user['nivel'] == 1) echo 'selected="selected"'; ?>>Oficial</option>
							<option value="2" <?php if (isset($user['nivel']) && $user['nivel'] == 2) echo 'selected="selected"'; ?>>Administrador</option>
						</select>
					</td>
				</tr>
				<tr>
					<td valign='top'>Guildas:</td>
					<td>
							<?php
							$guildas = explode(",", $user['guilda']);

							$sql = "SELECT DISTINCT * FROM guildas WHERE ativo = 1 ORDER BY nome";
							$result = $PDO->query( $sql );
							while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
								echo "<input type='checkbox' name='guilda[]' value='".$guilda['id']."' ".( in_array($guilda['id'], $guildas, true) ? "checked" : "")."> ".$guilda['nome']."<br>";
							}
							?>
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
} elseif ($op == "perfil") { 
// ==========================
// PERFIL USUARIO ATUAL
// ==========================
?>

<table width='600' class="table table-hover table-striped table-fixed">
	<tr>
		<td><b><center>Editar Meu Perfil</center></b></td>
	</tr>
	<tr>
		<td>
		<?php
		$userId = $_SESSION['autUser']['id'];
		$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (isset($_POST['enviar'])){
			unset($data['enviar']);

			if (empty($data['nome'])){
				echo '<span class="ms no">Oppss! Nome está em branco!</span>';
			} elseif (empty($data['email'])){
				echo '<span class="ms no">Oppss! E-mail está em branco!</span>';
			} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
				echo '<span class="ms no">Oppss! E-mail inválido. Por favor, coloque um e-mail válido!</span>';
			} else {
				//verifica se o email existe no banco de dados e nao deixa cadastrar
				$sql = "SELECT * FROM users WHERE (email = '$data[email]' OR login = '$data[login]') AND id != '$userId'";
				$readEmail = $PDO->query( $sql );
				if ($readEmail->rowCount() >= 1) {
					echo '<span class="ms no">Oppss! Login e/ou E-mail já existe no sistema. Por favor, cadastre um diferente!</span>';
				} else {

					//remove a repetição de senha
					//unset($data['senha_re']);

					//armazena a data e senha para gravar no banco
					$data['data'] = date("Y-m-d H:i:s");
					$data['code'] = $data['senha'];
					$data['senha'] = md5($data['code']);
					//$data['code'] = "";

					//verifica se a senha esta em branco para nao gravar no banco
					if (empty($data['code'])){
						unset($data['senha']);
						unset($data['code']);
					}

					$sql = update('users', $data, "id = '$userId'");
					$PDO->query( $sql );
					echo '<span class="ms ok">Pronto! Users salvo com sucesso!</span>';
					echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=users">';
			}
			}
		}
		
		$sql = "SELECT * FROM users WHERE id='$userId'";
		$readUser = $PDO->query( $sql );
		if ($readUser->rowCount() < 1) {
			echo '<span class="ms no">Oppss! Não existe esse usuario, tente novamente!</span>';
			//echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=users&op=lista">';
		} else {
			foreach ($readUser as $data){
		?>
		<center>
			<form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data" style="padding-top: 20px;">
			<table width='600' class="table table-hover table-striped table-fixed">
				<tr>
					<td width='70'>Nome:</td>
					<td width='500'><input type="text" name="nome" value="<?php if (isset($data['nome'])) echo $data['nome']; ?>" style='width:500px'/></td>
				</tr>
				<tr>
					<td>Login:</td>
					<td><input type="text" name="login" value="<?php if (isset($data['login'])) echo $data['login']; ?>" style='width:500px'/></td>
				</tr>
				<tr>
					<td>E-mail:</td>
					<td><input type="text" name="email" value="<?php if (isset($data['email'])) echo $data['email']; ?>" style='width:500px'></td>
				</tr>
				<tr>
					<td>Senha:</td>
					<td><input type="password" name="senha" value="" style='width:500px'></td>
				</tr>
				<!--
				<tr>
					<td>Repita a senha:</td>
					<td><input type="password" name="senha_re" value=""></td>
				</tr>
				-->
				<tr>
					<td></td>
					<td style="float:right">
						<button type="submit" name="enviar" class="btn btn-primary" value="Salvar" title="Salvar">Salvar</button>
					</td>
				</tr>
			</table>
			</form>
		</center>
		<?php } 
		} ?>
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