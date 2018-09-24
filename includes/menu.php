<?php 
$op_adm = array("guildas", "collection", "ciclos", "jogadores", "tb", "times", "users", "textos", "warnings");
if (empty($_SESSION['autUser']) AND ((in_array($_GET['pg'], $op_adm)) OR ($_GET['pg'] == "batalhas" AND $_GET['op'] == "novo")))
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=".$site."'>";
?>

<nav class="colorlib-nav" role="navigation">
	<div class="top-menu">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div id="colorlib-logo"><a href="<?=$site;?>"><img src="images/p_logokyber.png"></a></div>
				</div>
				<div class="col-md-10 text-right menu-1">
					<ul>
						<li class="active"><a href="<?=$site;?>">Home</a></li>
						<li class="has-dropdown">
							<a href="#">Institucional</a>
							<ul class="dropdown">
								<li><a href="<?=$site;?>?pg=institucional&op=1">Regras</a></li>
								<?php // Textos apenas para os oficiais
								if (!empty($_SESSION['autUser'])) { ?>
								<li><a href="<?=$site;?>?pg=institucional&op=2">Manual dos Oficiais</a></li>
								<li><a href="<?=$site;?>?pg=institucional&op=3">Estratégia para a Heróica</a></li>
								<?php } ?>
							</ul>
						</li>						
						<li class="has-dropdown">
							<a href="#">Guildas</a>
							<ul class="dropdown">
							<?php
							// Guildas Ativas
							$sql = "SELECT link, nome FROM guildas WHERE ativo = '1' ORDER BY nome ASC";
							$readUser = $PDO->query( $sql );

							foreach ($readUser as $rows){
								$link = explode("/", $rows['link']);
								echo "
								<li><a href='".$site."?pg=guilda&gd=".$link[0]."'>".$rows['nome']."</a></li>";
							}
							?>
							</ul>
						</li>
						<li class="has-dropdown">
							<a href="#">Datas</a>
							<ul class="dropdown">
								<li><a href="<?=$site;?>?pg=calendario">Calendário</a></li>
								<li><a href="<?=$site;?>?pg=agenda">Agenda</a></li>
							</ul>
						</li>
						<li class="has-dropdown">
							<a href="#">Informações</a>
							<ul class="dropdown">
								<li><a href="<?=$site;?>?pg=rotacao">Rotação</a></li>
								<li><a href="<?=$site;?>?pg=rank">Times para Rotação</a></li>
								<li><a href="<?=$site;?>?pg=filtro">Filtro de Chars</a></li>
								<li><a href="<?=$site;?>?pg=filtronaves">Filtro de Naves</a></li>
								<li><a href="<?=$site;?>?pg=batalhas">Batalhas (TW/TB)</a></li>
								<li><a href="<?=$site;?>?pg=eventos">Chars para os Eventos</a></li>
							</ul>
						</li>
						<!--<li><a href="<?=$site;?>?pg=eventos">Eventos</a></li>-->
						<!--<li><a href="<?=$site;?>?pg=rotacao">Rotação</a></li>-->
						<!--<li class="btn-cta"><a href="#"><span><i class="icon-cart"></i></span></a></li>-->
					<?php
					if (!empty($_SESSION['autUser'])) {
						// Menu ADMIN ?>

						<li class="has-dropdown">
							<a href="#">Admin</a>
							<ul class="dropdown">
								<li><a href="<?=$site;?>?pg=tb">Batalhas por Território</a></li>
								<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
								<li><a href="<?=$site;?>?pg=ciclos">Ciclos</a></li>
								<li><a href="#" onClick='javascript:if (confirm("Atualizar todos os personagens agora?")){location.href="<?=$site;?>?pg=collection"}'>Coleções</a></li>
								<li><a href="<?=$site;?>?pg=textos">Institucional</a></li>
								<?php } ?>
								<li><a href="<?=$site;?>?pg=guildas&op=lista">Guildas</a></li>
								<li><a href="<?=$site;?>?pg=jogadores">Jogadores</a></li>
								<li><a href="<?=$site;?>?pg=times">Times</a></li>
								<li><a href="<?=$site;?>?pg=users&op=lista">Usuários</a></li>
								<li><a href="<?=$site;?>?pg=users&op=perfil">Meu Perfil</a></li>
							</ul>
						</li>						
						<li class="has-dropdown">
							<a href="#">Warnings</a>
							<ul class="dropdown">
							<?php
							// Guildas Ativas
							$sql = "SELECT link, nome FROM guildas WHERE ativo = '1' ORDER BY nome ASC";
							$readUser = $PDO->query( $sql );

							foreach ($readUser as $rows){
								$link = explode("/", $rows['link']);
								echo "
								<li><a href='".$site."?pg=warnings&gd=".$link[0]."'>".$rows['nome']."</a></li>";
							}
							?>
							</ul>
						</li>
						<li><a href="<?=$site;?>?sair=true">Sair</a></li>
						
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</nav>
<?php /*
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
					</ul>
					
*/
?>
