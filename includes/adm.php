<?php
$nivel   = $_SESSION['autUser']['nivel'];
$guildas = explode(",", $_SESSION['autUser']['guilda']);

$sql = "SELECT DISTINCT * FROM guildas WHERE ativo = 1 ORDER BY nome";
?>

<div class="menu-container">
	<ul class="menu clearfix">
		<li>
			<a href="#"><img src='images/deathstar.png'></a>
			<ul class="sub-menu clearfix">
				<li>
					<a href="#">Atualização</a>
					<ul class="sub-menu clearfix">
						<!--<li><a href="<?=$site;?>?pg=lanca">Lançar Raids</a></li>-->
						<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
						<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os personagens agora?")){location.href="<?=$site;?>?pg=chars"}'>Chars</a></li>
						<?php } ?>
						<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os membros das guildas?")){location.href="<?=$site;?>?pg=membros"}'>Membros</a></li>
						<li>
							<a href="#">Coleção</a>
							<ul class="sub-menu">
							<?php 
							$result = $PDO->query( $sql );
							while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
								if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
									echo "
									<li>
									<a href='#' onClick='javascript:if (confirm(\"Atualizar a coleção dos membros da guilda ".str_replace("Brazil ", "", $guilda['nome'])."?\")){location.href=\"".$site."?pg=colecao&lmt=0&gd=".$guilda['id']."\"}'>".str_replace("Brazil ", "", $guilda['nome'])."</a>
									</li>";
							}
							?>
							</ul>
						</li>
<?php /*
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
*/ ?>
					</ul>
				</li>
				<li>
					<a href="#">Guildas</a>
					<ul class="sub-menu clearfix">
						<li><a href="<?=$site;?>?pg=guildas&op=lista">Listar Todas</a></li>
						<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
						<li><a href="<?=$site;?>?pg=guildas&op=novo">Adicionar Nova</a></li>
						<?php } ?>
					</ul>
				</li>
				<li>
					<a href="#">Usuários</a>
					<ul class="sub-menu clearfix">
						<li><a href="<?=$site;?>?pg=users&op=lista">Listar Todos</a></li>
						<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
						<li><a href="<?=$site;?>?pg=users&op=novo">Adicionar Novo</a></li>
						<?php } ?>
						<li><a href="<?=$site;?>?pg=users&op=perfil">Meu Perfil</a></li>
					</ul>
				</li>
				<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
				<li><a href="<?=$site;?>?pg=ciclostb">Ciclos TB</a></li>

				<li>
					<a href="#">Ciclos</a>
					<ul class="sub-menu clearfix">
						<li><a href="<?=$site;?>?pg=ciclos">Rotação</a></li>
						<li><a href="<?=$site;?>?pg=ciclostb">Territory Battle</a></li>
					</ul>
				</li>

				<?php } ?>
				<li>
					<a href="<?=$site;?>?pg=times">Times</a>
				</li>
			</ul>
		</li>


		<li><a href="<?=$site;?>">Início</a></li>
		<li>
			<a href="#">Contribuições</a>
			<ul class="sub-menu clearfix">
				<li><a href="#">Lançar Raid</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=lancar&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				<li>
					<a href="#">Warning</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=warning&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				<!--
				<li>
					<a href="#">Warnings</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=print&tp=600&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				<li>
					<a href="#">O Fosso</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=raids&tp=fosso&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				-->
				<li>
					<a href="#">Eliminação de Tanque</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=raids&tp=tanque&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				<li>
					<a href="#">Territory Battle</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=danotb&tp=tb&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#">Relatórios</a>
			<ul class="sub-menu clearfix">
				<li>
					<a href="<?=$site;?>?pg=danos">Maiores Danos</a>
				</li>
				<!--
				<li>
					<a href="#">Warnings</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=relprint&tp=600&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				<li>
					<a href="#">O Fosso</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=relraid&tp=fosso&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				-->
				<li>
					<a href="#">Eliminação de Tanque</a>
					<ul class="sub-menu">
					<?php 
					$result = $PDO->query( $sql );
					while ($guilda = $result->fetch( PDO::FETCH_ASSOC )) {
						if ( in_array($guilda['id'], $guildas, true) OR $nivel == 2 )
							echo "<li><a href='".$site."?pg=relraid&tp=tanque&gd=".$guilda['id']."'>".str_replace("Brazil ", "", $guilda['nome'])."</a></li>";
					}
					?>
					</ul>
				</li>
				<li>
					<a href="<?php echo $site."?pg=tb"; ?>">Territory Battle</a>
				</li>
			</ul>
		</li>
		<li><a href="<?=$site;?>?sair=true">Sair</a></li>
	</ul>
</div>

<?php
//if (is_null($_GET['pg'])) $pg = "filtro"; else $pg = $_GET['pg'];
if (is_null($_GET['pg'])) $pg = "rank"; else $pg = $_GET['pg'];
include ("includes/".$pg.".php");
?>