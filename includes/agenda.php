<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Agenda Semanal</h1>
							<h2><span>Raids | Previsão</span></h2>
						</div>
					</div>
				</div>
			</div>
		</li>
		</ul>
	</div>
</aside>
<?php
$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
?>
	<div class="colorlib-event">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Agenda</h2>
					<p>Previsão Semanal de Lançamentos das Raids</p>
					<p>
					
					<table width='400' class="table table-hover table-striped table-fixed">
					<form class="form-inline qbstp-header-subscribe" action="<?=$site;?>?pg=agenda" method="post">
					<tr>
						<td colspan='3'>
							<b>Dados do Banco antes do reset de:<br>
							Domingo, <?=strftime('%d de %B de %Y', (date('N')==7? strtotime('now'): strtotime('next sunday')));?>
						</td>
					</tr>
					<tr>
						<td><b>Rancor</b></td>
						<td><input type="number" class="form-control" id="a_rancor" name="a_rancor" placeholder="Banco do Rancor" value="<?php if ($data['a_rancor']) echo $data['a_rancor']; ?>"/></td>
						<td>
							<select name="t_rancor">
								<option value="60000" <?=($data['t_rancor'] == "60000" OR empty($data['t_rancor']) ? "SELECTED" : "");?>>Heróico</option>
								<option value="55000" <?=($data['t_rancor'] == "55000" ? "SELECTED" : "");?>>Tier VI</option>
								<option value="46000" <?=($data['t_rancor'] == "46000" ? "SELECTED" : "");?>>Tier V</option>
								<option value="42000" <?=($data['t_rancor'] == "42000" ? "SELECTED" : "");?>>Tier IV</option>
								<option value="38000" <?=($data['t_rancor'] == "38000" ? "SELECTED" : "");?>>Tier III</option>
								<option value="34000" <?=($data['t_rancor'] == "34000" ? "SELECTED" : "");?>>Tier II</option>
								<option value="30000" <?=($data['t_rancor'] == "30000" ? "SELECTED" : "");?>>Tier I</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>AAT</b></td>
						<td><input type="number" class="form-control" id="a_aat" name="a_aat" placeholder="Banco do AAT" value="<?php if ($data['a_aat']) echo $data['a_aat']; ?>"/></td>
						<td>
							<select name="t_aat">
								<option value="90000" <?=($data['t_aat'] == "90000" OR empty($data['t_aat']) ? "SELECTED" : "");?>>Heróico</option>
								<option value="80000" <?=($data['t_aat'] == "80000" ? "SELECTED" : "");?>>Tier VI</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Triunvirato<br>Sith</b></td>
						<td><input type="number" class="form-control" id="a_sith" name="a_sith" placeholder="Banco do Triunvirato Sith" value="<?php if ($data['a_sith']) echo $data['a_sith']; ?>"/></td>
						<td>
							<select name="t_sith">
								<option value="110000" <?=($data['t_sith'] == "110000" OR empty($data['t_sith']) ? "SELECTED" : "");?>>Heróico</option>
								<option value="95000" <?=($data['t_sith'] == "95000" ? "SELECTED" : "");?>>Tier VI</option>
								<option value="82000" <?=($data['t_sith'] == "82000" ? "SELECTED" : "");?>>Tier V</option>
								<option value="74000" <?=($data['t_sith'] == "74000" ? "SELECTED" : "");?>>Tier IV</option>
								<option value="66000" <?=($data['t_sith'] == "66000" ? "SELECTED" : "");?>>Tier III</option>
								<option value="58000" <?=($data['t_sith'] == "58000" ? "SELECTED" : "");?>>Tier II</option>
								<option value="50000" <?=($data['t_sith'] == "50000" ? "SELECTED" : "");?>>Tier I</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Média</b></td>
						<td><input type="number" class="form-control" id="a_media" name="a_media" placeholder="Média da Contribuição Diária" value="<?php if ($data['a_media']) echo $data['a_media']; ?>"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan='3'><button type="submit" name="enviar" class="btn btn-primary" value="enviar" title="Enviar">Enviar</button></td>
					</tr>
					</form>
					</table>
					
					</p>
					<?php if (!empty($data['enviar'])){ ?>
						<p>
						<table width='400' class="table table-hover table-striped table-fixed">
						<tr>
							<td colspan='7'><b>AGENDA<br></td>
						</tr>
						<tr>
							<td></td>
							<td colspan='2'><b>Rancor</b></td>
							<td colspan='2'><b>AAT</b></td>
							<td colspan='2'><b>Triunvirato<br>Sith</b></td>
						</tr>
						<?php 
						$rancor = $data['a_rancor'];
						$aat = $data['a_aat'];
						$sith = $data['a_sith'];

						for ($i = 0; $i < 7; $i++) {
							
							$alerta_rancor = "O";
							$alerta_aat = "O";
							$alerta_sith = "O";
							
							if ($i > 0) {
								$rancor += $data['a_media'];
								$aat += $data['a_media'];
								$sith += $data['a_media'];
							}
							
							if ($i == 0) {
								if ($rancor + $data['a_media'] > 150000) $alerta_rancor = "X";
								if ($aat + $data['a_media'] > 150000) $alerta_aat = "X";
								if ($sith + 2*$data['a_media'] > 150000) $alerta_sith = "X";
							} elseif ($i == 6) {
								if ($rancor + $data['a_media'] > 150000) $alerta_rancor = "X";
								if ($aat + $data['a_media'] > 150000) $alerta_aat = "X";
								if ($sith + 2*$data['a_media'] > 150000) $alerta_sith = "X";
							} else {
								if ($rancor > $data['t_rancor']) $alerta_rancor = "X";
								if ($aat > $data['t_aat']) $alerta_aat = "X";
								if ($sith + $data['a_media'] > $data['t_sith']) $alerta_sith = "X";
							}
													
						?>
						<tr>
							<td><?=date('d/m', strtotime("+".$i." days", (date('N')==7? strtotime('now'): strtotime('next sunday'))));?></td>
							<td><?=$rancor?></td>
							<td><?=$alerta_rancor?></td>
							<td><?=$aat?></td>
							<td><?=$alerta_aat?></td>
							<td><?=$sith?></td>
							<td><?=$alerta_sith?></td>
						</tr>
						<?php 
							if ($i == 0) {
								if ($rancor + $data['a_media'] > 150000) $rancor -= $data['t_rancor'];
								if ($aat + $data['a_media'] > 150000) $aat -= $data['t_aat'];
								if ($sith + $data['a_media'] > 150000) $sith -= $data['t_sith'];
							} else {
								if ($rancor > $data['t_rancor']) $rancor -= $data['t_rancor'];
								if ($aat > $data['t_aat']) $aat -= $data['t_aat'];
								if ($sith + $data['a_media'] > $data['t_sith']) $sith -= $data['t_sith'];
							}
						} ?>
						<tr>
							<td></td>
							<td colspan='2'>* Abrir Raid <br><b>antes</b> do Reset</td>
							<td colspan='2'>* Abrir Raid <br><b>antes</b> do Reset</td>
							<td colspan='2'>* Abrir Raid <br><b>após</b> o Reset</td>
						</tr>
						</table>
						</p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

