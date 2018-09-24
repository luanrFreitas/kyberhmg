<html lang="pt-br">
<?php
error_reporting(0);
session_name('gdcon');
session_start();

$url =  "//{$_SERVER['HTTP_HOST']}/swgoh";
//$url =  "//{$_SERVER['HTTP_HOST']}";
$site = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );

include ("config.php");
?>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<link rel="stylesheet" href="toolkit.css" type="text/css"/>
</head>
<body>

<div class="menu-container">
	<ul class="menu clearfix">
		<li><a href="<?=$site;?>">Início</a></li>
		<li>
			<a href="#">Contribuções</a>
			<ul class="sub-menu clearfix">
				<li>
					<a href="#">Print 600</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=4">Troopers</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=5">Republic</a></li>
					</ul>
				</li>
				<li>
					<a href="#">O Fosso</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=4">Troopers</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=5">Republic</a></li>
					</ul>
				</li>
				<li>
					<a href="#">Tank Takedown</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=4">Troopers</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=5">Republic</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#">Atualização</a>
			<ul class="sub-menu clearfix">
				<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os personagens agora?")){location.href="<?=$site;?>?pg=chars"}'>Chars</a></li>
				<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os membros das guildas?")){location.href="<?=$site;?>?pg=membros"}'>Membros</a></li>
				<li>
					<a href="#">Coleção</a>
					<ul class="sub-menu">
						<li>
							<a href="#">Brazil</a>
							<ul class="sub-menu">
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil (Parte 1)?")){location.href="<?=$site;?>?pg=colecao&gd=1&pt=1"}'>Parte 1</a></li>
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil (Parte 2)?")){location.href="<?=$site;?>?pg=colecao&gd=1&pt=2"}'>Parte 2</a></li>
							</ul>
						</li>
						<li>
							<a href="#">Brazil Empire</a>
							<ul class="sub-menu">
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Empire (Parte 1)?")){location.href="<?=$site;?>?pg=colecao&gd=2&pt=1"}'>Parte 1</a></li>
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Empire (Parte 2)?")){location.href="<?=$site;?>?pg=colecao&gd=2&pt=2"}'>Parte 2</a></li>
							</ul>
						</li>
						<li>
							<a href="#">Brazil Rebels</a>
							<ul class="sub-menu">
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Rebels (Parte 1)?")){location.href="<?=$site;?>?pg=colecao&gd=3&pt=1"}'>Parte 1</a></li>
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Rebels (Parte 2)?")){location.href="<?=$site;?>?pg=colecao&gd=3&pt=2"}'>Parte 2</a></li>
							</ul>
						</li>
						<li>
							<a href="#">Brazil Troopers</a>
							<ul class="sub-menu">
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Troopers (Parte 1)?")){location.href="<?=$site;?>?pg=colecao&gd=4&pt=1"}'>Parte 1</a></li>
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Troopers (Parte 2)?")){location.href="<?=$site;?>?pg=colecao&gd=4&pt=2"}'>Parte 2</a></li>
							</ul>
						</li>
						<li>
							<a href="#">Brazil Republic</a>
							<ul class="sub-menu">
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Republic (Parte 1)?")){location.href="<?=$site;?>?pg=colecao&gd=5&pt=1"}'>Parte 1</a></li>
								<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Republic (Parte 2)?")){location.href="<?=$site;?>?pg=colecao&gd=5&pt=2"}'>Parte 2</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
</div>

<?php
if (is_null($_GET['pg'])) $pg = "filtro"; else $pg = $_GET['pg'];
include ($pg.".php");
?>



</body>
</html>