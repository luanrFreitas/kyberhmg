<html lang="pt-br">
<?php
error_reporting(0);
session_name('gdcon');
session_start();

include ("config.php");
?>
<head>
	<title>BRAZIL</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<link rel="stylesheet" href="toolkit.css" type="text/css"/>
</head>
<body>

<div class="menu-container">
	<ul class="menu clearfix">
		<li><a href="<?=$site;?>">Início</a></li>
		<li>
			<a href="#">Contribuições</a>
			<ul class="sub-menu clearfix">
				<li>
					<a href="#">Print 600</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=print&tp=600&gd=4">Troopers</a></li>
					</ul>
				</li>
				<li>
					<a href="#">O Fosso</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=fosso&gd=4">Troopers</a></li>
					</ul>
				</li>
				<li>
					<a href="#">Eliminação de Tanque</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=raid&tp=tanque&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=tanque&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=tanque&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=tanque&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=raid&tp=tanque&gd=4">Troopers</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#">Relatórios</a>
			<ul class="sub-menu clearfix">
				<li>
					<a href="#">Print 600</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=relprint&tp=600&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=relprint&tp=600&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=relprint&tp=600&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=relprint&tp=600&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=relprint&tp=600&gd=4">Troopers</a></li>
					</ul>
				</li>
				<li>
					<a href="#">O Fosso</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=relraid&tp=fosso&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=fosso&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=fosso&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=fosso&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=fosso&gd=4">Troopers</a></li>
					</ul>
				</li>
				<li>
					<a href="#">Eliminação de Tanque</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=relraid&tp=tanque&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=tanque&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=tanque&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=tanque&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=relraid&tp=tanque&gd=4">Troopers</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#">Atualização</a>
			<ul class="sub-menu clearfix">
				<!--<li><a href="<?=$site;?>?pg=lanca">Lançar Raids</a></li>-->
				<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os personagens agora?")){location.href="<?=$site;?>?pg=chars"}'>Chars</a></li>
				<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os membros das guildas?")){location.href="<?=$site;?>?pg=membros"}'>Membros</a></li>
				<li>
					<a href="#">Coleção</a>
					<ul class="sub-menu">
						<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil?")){location.href="<?=$site;?>?pg=colecao&gd=1"}'>Brazil</a></li>
						<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Empire?")){location.href="<?=$site;?>?pg=colecao&gd=2"}'>Empire</a></li>
						<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Rebels?")){location.href="<?=$site;?>?pg=colecao&gd=3"}'>Rebels</a></li>
						<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Republic?")){location.href="<?=$site;?>?pg=colecao&gd=5"}'>Republic</a></li>
						<li><a href="#" onClick='javascript:if (confirm("Atualizar a coleção dos membros da guilda Brazil Troopers?")){location.href="<?=$site;?>?pg=colecao&gd=4"}'>Troopers</a></li>
					</ul>
				</li>
				<!--
				<li>
					<a href="#">Telegram</a>
					<ul class="sub-menu">
						<li><a href="<?=$site;?>?pg=telegram&gd=1">Brazil</a></li>
						<li><a href="<?=$site;?>?pg=telegram&gd=2">Empire</a></li>
						<li><a href="<?=$site;?>?pg=telegram&gd=3">Rebels</a></li>
						<li><a href="<?=$site;?>?pg=telegram&gd=5">Republic</a></li>
						<li><a href="<?=$site;?>?pg=telegram&gd=4">Troopers</a></li>
					</ul>
				</li>
				-->
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