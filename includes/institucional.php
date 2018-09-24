<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Institucional</h1>
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
$sql = "SELECT * FROM institucional WHERE id = '".$_GET['op']."'";
$readTexto = $PDO->query( $sql );

foreach ($readTexto as $texto) { ?>
	<div class="colorlib-trainers">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 colorlib-heading animate-box fadeInUp animated-fast">
					<?=$texto['texto'];?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>