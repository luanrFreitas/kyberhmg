<div class="account-container">
	<div class="content clearfix">
		<form action="?" method="post">
			<h1>Faça seu Login</h1>

			<?php
			$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

			if (!empty($data['logar'])){
				unset($data['logar']);
				//valida os campos
				if (in_array('', $data)) {
					echo '<span class="ms no">Oppss! Por favor, preencha todos os campos!</span>';
				//} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
				//	echo '<span class="ms no">Oppss! E-mail inválido. Por favor, coloque um e-mail válido!</span>';
				} else {
					$email = $data['email'];
					$login = $data['login'];
					$senha = md5($data['senha']);

					//faz a leitura e extrai os dados do usuario atraves do email e senha
					$sql_readUser = "SELECT * FROM users WHERE login = '$login' AND senha = '$senha'";
					$readUser = $PDO->query( $sql_readUser );
					if ($readUser->rowCount() >= 1) {
						while ($autUser = $readUser->fetch( PDO::FETCH_ASSOC )) {
							//verifica se o usuario digitou o login e senha correta no banco
							if ($login == $autUser['login'] && $senha == $autUser['senha']){
								//verifica se o usuario esta ativo
								if ($autUser['status'] != '1'){
									echo '<span class="ms no">Oppss! Você não tem permissão de acesso em nosso sistema!</span>';
								} else {
									//logon do usuario no sistema
									$_SESSION['autUser'] = $autUser;
									header('Location: ?');
								}
							} else {
								echo '<span class="ms no">Oppss! E-mail ou senha não correspondem. Por favor, tente novamente!</span>';
							}
						}
					} else {
						echo '<span class="ms no">Oppss! Login ou Senha inválidos. Por favor, tente novamente!</span>';
					}
				}
			}

			//mensagem de sair do sistema
			//$mg = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
			if (!empty($mg)){
				if ($mg == 'sair'){
					echo '<span class="ms ok">SESSEÃO ENCERRADA! <br>Que a força esteja com você!</span>';
					//echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=index.php">';
				}
			}
			?>

			<div class="login-fields">
				<p>Entre com seus dados:</p>
				<?php /*
				<div class="field">
					<label for="username">E-mail</label>
					<input type="email" name="email" placeholder="E-mail" class="login username-field"
						   value="<?php if ($data['email']) echo $data['email']; ?>"/>
				</div> <!-- /field -->
				*/?>

				<div class="field">
					<label for="username">Login</label>
					<input type="login" name="login" placeholder="Login" class="login username-field" value="<?php if ($data['login']) echo $data['login']; ?>"/>
				</div> <!-- /field -->

				<div class="field">
					<label for="password">Senha:</label>
					<input type="password" id="password" name="senha" placeholder="Senha" class="login password-field" value="<?php if ($data['senha']) echo $data['senha']; ?>"/>
				</div> <!-- /password -->

			</div> <!-- /login-fields -->
			<div class="login-actions">
				<input type="submit" name="logar" class="button btn btn-success btn-large" value="Logar" class="bt"
					   title="Logar"/>
			</div> <!-- .actions -->
		</form>
	</div> <!-- /content -->
</div> <!-- /account-container -->
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/signin.js"></script>