<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Guilda</h1>
							<h2><span>União Kyber</span></h2>
						</div>
					</div>
				</div>
			</div>
		</li>
		</ul>
	</div>
</aside>
<?php
// Guildas Ativas
$sql = "SELECT `nome`, `canais`, `link` FROM guildas WHERE ativo = '1' and link LIKE '%".$_GET['gd']."%' ORDER BY nome ASC";
$readGuild = $PDO->query( $sql );

$sql2 = "SELECT DISTINCT `url`, `player` FROM `units` WHERE guilda='".$_GET['gd']."' AND `url` != '' ORDER BY `player` ASC";
$readUser = $PDO->query( $sql2 );

foreach ($readGuild as $guild) { ?>
	<div class="colorlib-event">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2><?=$guild['nome'];?></h2>
					<p><a href='https://swgoh.gg/g/<?=$guild['link'];?>' target='_blank' rel='noopener'>https://swgoh.gg/g/<?=$guild['link'];?></a>
					<br><br>
					<?=$guild['canais'];?>
					
					<table width='400' class="table table-hover table-striped table-fixed">
					<tr>
						<td colspan='3'><b><a href="?pg=guildas&op=novo">Jogadores</b></td>
					</tr>
					<?php 
					$x = 0;
					foreach ($readUser as $user) { 
						$x++;
						echo "<tr><td>".$x."</td><td>".$user['player']."</td><td><a href='https://swgoh.gg".$user['url']."' target='_blank' rel='noopener'><IMG SRC='http:".$site."/images/view.ico'></a></td></tr>";
					} ?>
					</table>
					</p>
				</div>
			</div>
		</div>
	</div>
<?php } ?>