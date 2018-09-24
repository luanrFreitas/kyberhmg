<?php
ob_start();

error_reporting(0);
require 'config.php';
//require 'config/funcoes.php';

if (is_null($_GET['pg'])) $pg = "start"; else $pg = $_GET['pg'];

if ($pg == "bot") {
	require ("includes/".$pg.".php");
} else {

session_name('gdcon');
session_start();

$sair = filter_input(INPUT_GET, 'sair', FILTER_VALIDATE_BOOLEAN);
if ($sair):
    unset($_SESSION['autUser']);
	$mg = "sair";
endif;

function update($tabela, array $data, $where) {
	//if (isset($data['guilda'])) $data['guilda'] = implode(",", $data['guilda']);
	if (isset($data['guilda']) && is_array($data['guilda'])) $data['guilda'] = implode(",", $data['guilda']);

    foreach ($data as $fields => $values) {
        $campos[] = "$fields = '$values'";
    }

    $campos = implode(", ", $campos);
    $query = "UPDATE {$tabela} SET $campos WHERE {$where}";    
	return $query;
}
function create($tabela, array $data) {
	//if (isset($data['guilda'])) $data['guilda'] = implode(",", $data['guilda']);
	if (isset($data['guilda']) && is_array($data['guilda'])) $data['guilda'] = implode(",", $data['guilda']);

    $campos = implode(", ", array_keys($data));
    $valor = "'" . implode("', '", array_values($data)) . "'";
    $query = "INSERT INTO {$tabela} ($campos) VALUES ($valor)";
    return $query;
}

    ?>

<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>União Kyber</title>
	<link href="crystal.ico" rel="icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

  <!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />
	
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,700,900" rel="stylesheet">
	<link href="css/toolkit.css" rel="stylesheet" type="text/css"/>
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">

	<!-- Magnific Popup -->
	<link rel="stylesheet" href="css/magnific-popup.css">

	<!-- Flexslider  -->
	<link rel="stylesheet" href="css/flexslider.css">

	<!-- Owl Carousel -->
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">
	
	<!-- Flaticons  -->
	<link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	
	<script src="http:<?=$site?>/ckeditor/ckeditor.js"></script>
	</head>
	<body>
	<div class="colorlib-loader"></div>
	<div id="page">
		<?php
		require 'includes/menu.php';
		//verifica se existe uma sessão do usuário
//		if (!empty($_SESSION['autUser'])) {
			//require 'includes/adm.php';
//		} else {
			require ("includes/".$pg.".php");
//		} 
		?>


<?php if (empty($_SESSION['autUser'])) { ?>
<div id="colorlib-subscribe" class="subs-img" style="background-image: url(images/img_bg_2.jpg);" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
				<h2>Administração - Login</h2>
				<p>Faça seu Login</p>
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
			</div>
		</div>
		<div class="row animate-box">
			<div class="col-md-6 col-md-offset-3">
				<div class="row">
					<div class="col-md-12">
					<form class="form-inline qbstp-header-subscribe" action="?" method="post">
						<div class="col-three-forth">
							<div class="form-group">
								<input type="login" class="form-control" id="login" name="login" placeholder="Login" value="<?php if ($data['login']) echo $data['login']; ?>"/>
								<input type="password" class="form-control" id="password" name="senha" placeholder="Senha" value="<?php if ($data['senha']) echo $data['senha']; ?>"/>
							</div>
						</div>
						<div class="col-one-third">
							<div class="form-group">
								<button type="submit" name="logar" class="btn btn-primary" value="Logar" title="Logar">Logar</button>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

		<footer id="colorlib-footer">
			<div class="container">
				<div class="row row-pb-md">
					<div class="col-md-3 colorlib-widget">
						<h4>Sobre a União Kyber</h4>
						<p>Somos uma união de guildas do jogo Star Wars Galaxy of Heroes com o objetivo de ajudar no desenvolvimento dos jogadores.</p>
						<!--
						<p>
							<ul class="colorlib-social-icons">
								<li><a href="#"><i class="icon-twitter"></i></a></li>
								<li><a href="#"><i class="icon-facebook"></i></a></li>
								<li><a href="#"><i class="icon-linkedin"></i></a></li>
								<li><a href="#"><i class="icon-dribbble"></i></a></li>
							</ul>
						</p>
						-->
					</div>
					<div class="col-md-3 colorlib-widget">
						<h4>Links Rápidos</h4>
						<p>
							<ul class="colorlib-footer-links">
								<li><a href="#"><i class="icon-check"></i> Agenda</a></li>
								<li><a href="#"><i class="icon-check"></i> Times</a></li>
								<li><a href="#"><i class="icon-check"></i> Eventos</a></li>
								<li><a href="#"><i class="icon-check"></i> Rotação</a></li>
							</ul>
						</p>
					</div>

					<!--
					<div class="col-md-3 colorlib-widget">
						<h4>Recent Post</h4>
						<div class="f-blog">
							<a href="blog.html" class="blog-img" style="background-image: url(images/blog-1.jpg);">
							</a>
							<div class="desc">
								<h2><a href="blog.html">Tips for sexy body</a></h2>
								<p class="admin"><span>18 April 2018</span></p>
							</div>
						</div>
						<div class="f-blog">
							<a href="blog.html" class="blog-img" style="background-image: url(images/blog-2.jpg);">
							</a>
							<div class="desc">
								<h2><a href="blog.html">Tips for sexy body</a></h2>
								<p class="admin"><span>18 April 2018</span></p>
							</div>
						</div>
						<div class="f-blog">
							<a href="blog.html" class="blog-img" style="background-image: url(images/blog-3.jpg);">
							</a>
							<div class="desc">
								<h2><a href="blog.html">Tips for sexy body</a></h2>
								<p class="admin"><span>18 April 2018</span></p>
							</div>
						</div>
					</div>
					-->

					<div class="col-md-3 colorlib-widget">
						<h4>Contato</h4>
						<ul class="colorlib-footer-links">
							<li>Contato apenas no <br> aplicativo Discord</li>
							<li><a href="http://discord.me/uniaokyber" target='_blank' rel='noopener'><i class="icon-server"></i> Servidor no Discord</a></li>
							<li><a href="http://kyber.arcomclube.com.br" target='_blank' rel='noopener'><i class="icon-location2"></i> http://kyber.arcomclube.com.br</a></li>
							<li><a href="https://www.youtube.com/channel/UC8-gW9wKyB0lNbrPX46rHRg" target='_blank' rel='noopener'><i class="icon-youtube"></i> Canal no Youtube</a></li>
							<li><a href="https://www.facebook.com/Uni%C3%A3o-Kyber-221089145369909/" target='_blank' rel='noopener'><i class="icon-facebook22"></i> Página no Facebook</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="copy">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<p>
								<small class="block">&copy; <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></small><br> 
								<small class="block">Demo Images: <a href="http://unsplash.co/" target="_blank">Unsplash</a>, <a href="http://pexels.com/" target="_blank">Pexels</a></small>
							</p>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<div class="gototop js-top">
		<a href="#" class="js-gotop"><i class="icon-arrow-up2"></i></a>
	</div>
	
	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Stellar Parallax -->
	<script src="js/jquery.stellar.min.js"></script>
	<!-- Flexslider -->
	<script src="js/jquery.flexslider-min.js"></script>
	<!-- Owl carousel -->
	<script src="js/owl.carousel.min.js"></script>
	<!-- Magnific Popup -->
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/magnific-popup-options.js"></script>
	<!-- Counters -->
	<script src="js/jquery.countTo.js"></script>
	<!-- Main -->
	<script src="js/main.js"></script>

	</body>
</html>

<?php
}
ob_end_flush();
?>
