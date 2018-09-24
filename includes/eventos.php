<?php 
require 'includes/eventos_dados.php';
?>
<aside id="colorlib-hero">
    <div class="flexslider">
        <ul class="slides">
            <li style="background-image: url(images/img_bg_2.jpg);">
                <div class="overlay"></div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
                            <div class="slider-text-inner text-center">
                                <h1>Guia de Eventos</h1>
								<h2><span>Jogadores | Chars</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</aside>

<div class="colorlib-trainers" style ="overflow-x: auto;">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
				<h2>Jogadores</h2>
				<form action="" method="get">
				<p>Selecione um jogador da União Kyber para exibir seus dados<p>
				<input type="text" name ="pg" value="eventos" hidden />
				</p>
					<select name="jogador">
						<option>Selecione...</option>
						<?php
						foreach ($readJogadores as $jogadores)
							echo '<option value="'.$jogadores['player'].'">'.$jogadores['player'].'</option>' ;
						?>
					</select>
					&nbsp;&nbsp;
					<input type="submit" value="Ok" />					
				</p>
				</form>
			</div>
		</div>
	</div>
</div>

<?php

if(isset($rank)){
for($i=0;$i <= count($eventos)-1;$i++)
{
    $view .= '
        <div style ="overflow-x: auto;">
	<div id="colorlib-testimony" class="testimony-img" style="background-image: url('.str_replace('.jpg', '2.jpg', $eventos[$i]['imagem']).');" data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h3>'.$eventos[$i]['nome'].'</h3>
					<h2>Raridade  '.$eventos[$i]['raridade'].' | Equipamento '.$eventos[$i]['equipamento'].' | Nível '.$eventos[$i]['nivel'].'</h2>
				</div>
			</div>
		</div>
	</div>
	</br>
    <div style="color:black" class="bg-alt-gray">
        <div style="color:black" class="bg-alt-gray">
            <div class="container ">
                <div class="row event">
                    <div class="col-md-4 col-sm-12 portfolio-item">
                        <img src="'.$eventos[$i]['imagem'].'"/>
                        <h4 class="section-bar">'.$eventos[$i]['recompensa'].'</h4>
                        <h5 class="section-bar"> Status : '.(($rank[1][$eventos[$i]['status']]['rarity'] > 4) ? 'Raridade '.$rank[1][$eventos[$i]['status']]['rarity'].' ' : 'Não possui' ).'</h5>
                        <p style="background-color:red"></p>
                    </div>
                    <div class="col-md-8 col-sm-12 portfolio-item">
                        <div class="portfolio-caption">
                            <p id="analysis" class="text-muted">
                             <h4>'.$eventos[$i]['time'] .'</h4>
                                <table class="table raidtable rancorraid" align="center">
                                    <tr>
                                        <td>Nome</td>
                                        <td>Raridade</td>
                                        <td>Equipt</td>
                                        <td>Nível</td>
                                    </tr>';
            for($j=0;$j <= count($eventos[$i]['chars'])-1;$j++ ){
                $view .=' <tr>
                                    <td>'.$char[$eventos[$i]['chars'][$j]['nome']]['name'].'</td>
                                    <td bgcolor="'.bg($eventos[$i]['raridade'],$rank[1][$eventos[$i]['chars'][$j]['nome']]['rarity']).'">'.$rank[1][$eventos[$i]['chars'][$j]['nome']]['rarity'].'</td>
                                    <td bgcolor="'.bg($eventos[$i]['equipamento'],$rank[1][$eventos[$i]['chars'][$j]['nome']]['gear_level']).'">'.$rank[1][$eventos[$i]['chars'][$j]['nome']]['gear_level'].'</td>
                                    <td bgcolor="'.bg($eventos[$i]['nivel'],$rank[1][$eventos[$i]['chars'][$j]['nome']]['level']).'">'.$rank[1][$eventos[$i]['chars'][$j]['nome']]['level'].'</td>
                                </tr>
                    '; }
                   $view .='</p>
                            <table class="table table-hover table-striped table-fixed">
                                <thead class="thead-dark">
                                    <tr>
                                        <td>Legenda</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Atende requisito</td>
                                        <td bgcolor="009900"></td>
                                    </tr>
                                    <tr>
                                        <td>Atende requisito Parcialmente</td>
                                        <td bgcolor="FFB90F"></td>
                                    </tr>
                                    <tr>
                                        <td>Não atende requisito</td>
                                        <td bgcolor="FF0000"></td>
                                    </tr>
                                </thead>
                            </table>    
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    </div>
 </br>';
}
echo $view;
}

?>
    


