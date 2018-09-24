<?php 
$PDO->query('SET lc_time_names = "pt_br"');
if (is_null($_POST['tipo'])) $tipo = "TB LS"; else $tipo = $_POST['tipo'];
$readGuildas = $PDO->query("SELECT `nome` FROM `guildas` ORDER BY `nome` ");
$readMediaVitorias= $PDO->query("SELECT distinct `tipo` ,Monthname(`data`) as mes ,count(`resultado`)/6 as media FROM `batalhas`  where `tipo` = 'TW'  and `resultado`= 'vitória' group by Monthname(`data`) order by `data`");
$readMediaEstrelas= $PDO->query("SELECT distinct `tipo` ,Monthname(`data`) as mes ,AVG(`estrelas`) as media FROM `batalhas`  where `tipo` = '".$tipo."' group by Monthname(`data`) order by `data`");
$readMeses= $PDO->query("SELECT distinct Monthname(`data`) as mes FROM `batalhas` group by Monthname(`data`) order by `data`");
$readEstrelas= $PDO->query("SELECT `guilda`,`estrelas` FROM `batalhas` where `tipo` = '".$tipo."' order by `data`,`guilda`");
$readVitorias= $PDO->query("SELECT  Monthname(`data`), (CASE `resultado`WHEN 'vitória' THEN 1 ELSE 0 END) as vitorias, `guilda` FROM `batalhas` WHERE tipo = 'tw' GROUP by 1,3 order by `data` ");
if ($_POST['tipo']=='TW'){
    $label ='Vitórias Conquistadas' ;
    foreach ($readVitorias as $vitorias) {
        if($vitorias['guilda']== "Sacrifício de Bnar"){
            $listaBnar .= $vitorias['vitorias'] .",";
        }else if ($vitorias['guilda']== "Manto da Força") {
            $listaManto .= $vitorias['vitorias'] .",";
        }else if($vitorias['guilda']== "Solari"){
            $listaSolari .= $vitorias['vitorias'] .",";
        }else if($vitorias['guilda']== "Aquamarine"){
            $listaAquamarine .= $vitorias['vitorias'] .",";
        }else if($vitorias['guilda']== "Lambent"){
            $listaLambent .= $vitorias['vitorias'] .",";
        }else if($vitorias['guilda']== "Kaiburr"){
            $listaKaiburr .= $vitorias['vitorias'] .",";
        }
    }
    unset($listaMedia);
    foreach ($readMediaVitorias as $media) {
        $listaMedia .= $media['media'].',';
        
    }

}else {
    $label ='Estrelas Conquistadas' ;
    foreach ($readEstrelas as $estrelas) {
        if($estrelas['guilda']== "Sacrifício de Bnar"){
            $listaBnar .= $estrelas['estrelas'] .",";
        }else if ($estrelas['guilda']== "Manto da Força") {
            $listaManto .= $estrelas['estrelas'] .",";
        }else if($estrelas['guilda']== "Solari"){
            $listaSolari .= $estrelas['estrelas'] .",";
        }else if($estrelas['guilda']== "Aquamarine"){
            $listaAquamarine .= $estrelas['estrelas'] .",";
        }else if($estrelas['guilda']== "Lambent"){
            $listaLambent .= $estrelas['estrelas'] .",";
        }else if($estrelas['guilda']== "Kaiburr"){
            $listaKaiburr .= $estrelas['estrelas'] .",";
        }
    } 
    
        unset($listaMedia);
    foreach ($readMediaEstrelas as $media) {
        $listaMedia .= $media['media'].',';
        
    }

}

foreach ($readMeses as $meses) {
    $listaMeses .= "'".$meses['mes'] ."',";  
}


function ajustaValor($valor)
{
    $tamanhoValor = strlen($valor);
    $valorAjustado = substr($valor ,0,$tamanhoValor-1);
    return $valorAjustado;
}
$listaBnar = ajustaValor($listaBnar);
$listaManto = ajustaValor($listaManto);
$listaSolari = ajustaValor($listaSolari);
$listaAquamarine = ajustaValor($listaAquamarine);
$listaLambent = ajustaValor($listaLambent);
$listaKaiburr = ajustaValor($listaKaiburr);
$listaMedia = ajustaValor($listaMedia);
$listaMeses = ajustaValor($listaMeses);
$tipoBatalha = $_GET['tipo'];

//echo var_export($listaMedia);
?> 
<body onload="setForm('<?php echo $tipoBatalha?>')">
<aside id="colorlib-hero">
    <div class="flexslider">
        <ul class="slides">
            <li style="background-image: url(images/img_bg_2.jpg);">
                <div class="overlay"></div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
                            <div class="slider-text-inner text-center">
                                <h1>Batalhas</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</aside>
<div class="colorlib-event" style ="overflow-x: auto;">
    <div class="container">
	<div class="row">
            <div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
                                    
<?php
if (is_null($_GET['op'])) $op = null; else $op = $_GET['op'];
if (is_null($op)) { 
    // ==========================
    // GRÁFICO DE BATALHAS 
    // ==========================
?>
                <form action="#" method="post">                         
                    <p>
                    <select name="tipo" onchange="this.form.submit()">
                        <option>Selecione o tipo de batalha...</option>
                        <option value="TB LS">TB LS</option>
                        <option value="TB DS">TB DS</option>
                        <option value="TW">TW</option>
                    </select>
                    </p>
                </form>
	            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	<?php 

        if (!empty($_SESSION['autUser'])) { ?>
                        <button type="button" name="listar" class="btn btn-primary" value="Listar" title="Listar" onclick="location.href='?pg=batalhas&op=listar';">Listar</button>
	                                             
	<?php

          }
          
          //deleta usuarios
          if (!empty($_GET['delete'])){
              $delId = $_GET['delete'];

              $sql = "DELETE FROM batalhas WHERE id = '$delId'" ;
              $PDO->query( $sql );
              echo '<meta HTTP-EQUIV="refresh" CONTENT="1;URL=?pg=batalhas&op=listar">';
          }
} elseif ($op == "novo" OR $op == "editar" && !empty($_SESSION['autUser']) ) {
    // ==========================
    // NOVA BATALHA
    // ==========================
    ?>
                    <h2>Cadastro de Batalhas</h2>
                    <div id="main" class="container-fluid">
	                    <table class="table">
	                        <tr>
		                    <td>
		<?php
    $dado = filter_input_array(INPUT_POST, FILTER_DEFAULT);	
    if (isset($_POST['enviar'])){
        unset($dado['enviar']);
        
        if ($op == "editar") {
            
            $sql = update('batalhas', $dado, "id = '$id'");
        } else {
            $sql = create('batalhas', $dado);
        }
        
        $PDO->query( $sql );
        echo '<span class="ms ok">Pronto! Batalha salva com sucesso!</span>';
        echo '<meta HTTP-EQUIV="refresh" CONTENT="3;URL=?pg=batalhas">';
    }
    if ($op == "editar") {
        $id = $_GET['id'];
        $sql = "SELECT * FROM batalhas WHERE id = '".$id."' ORDER BY id";
        $editar = $PDO->query( $sql );
        $batalha = $editar->fetch(PDO::FETCH_ASSOC);
    }
        ?>
		                    <center>
			                <form class="form-horizontal" action="#" method="POST">
			                    <div class="form-group">
				                <label class="control-label col-sm-2" for="tipo">Tipo:</label>
									<div class="col-sm-10">
                                                                     
									<select name="tipo" onchange="setForm(this.value)">
										<option value="">Selecione </option> 
										<option value="TB LS">TB LS</option>
										<option value="TB DS">TB DS</option>
										<option value="TW">TW</option>
                                                                                <option value="<?php echo $batalha['tipo']."\"  selected>".$batalha['tipo']."</option>";?>
									</select>
									</div>
								</div>
								<div class="form-group">
								<label class="control-label col-sm-2" for="data">Data:</label>
									<div class="col-sm-10"> 
									<input type="date" id="data"  name="data" value ="<?php echo $batalha['data']?>" class="form-control" required>
									</div>
								</div>
								<div class="form-group">
								<label class="control-label col-sm-2" for="guilda">Guilda:</label>
									<div class="col-sm-10">
									<select name="guilda">
									<?php foreach ($readGuildas as $guilda){
                                                                              echo '<option value="'.$guilda['nome'].'">'.$guilda['nome'].'</option>';
                                                                              }
                                                                              echo '<option value="'.$batalha['guilda'].'" selected>'.$batalha['guilda'].'</option>';
                                                                              ?>
									</select>
									</div>
								</div>
							<div id ="estrelas" class="form-group" >
							<label class="control-label col-sm-2" for="estrelas">Estrelas:</label>
								<div class="col-sm-10">
								<input id ="estrelasInput" type="number" name="estrelas" class="form-control"  value ="<?php echo $batalha['estrelas']?>" required>
								</div>
							</div>
							<div id ="resultado" class="form-group" style="display:none;">
							<label class="control-label col-sm-2" for="resultado">Resultado:</label>
								<div class="col-sm-10">
								<select  id ="resultadoInput" name="resultado" required>
									<option value=""></option>
                                                                        <option value="Vitória">Vitória</option>
									<option value="Derrota">Derrota</option>
                                                                        <option value="<?php echo $batalha['resultado']."\"  selected>".$batalha['resultado']."</option>";?>
								</select>
								</div>
							</div>
							<div class="form-group"> 
								<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" name="enviar" class="btn btn-primary" value="Salvar" title="Salvar">Salvar</button>
								</div>
							</div>
							</form>
							</center>
							</td>
							</tr>
	                    </table>
			</div>
                    
  
<?php
} elseif ($op == "listar" && !empty($_SESSION['autUser']) ) {
?>
<table width='650'>
    <tr>
        <td style="text-align: right;" colspan='5'>
	    <button type="button" name="novo" class="btn btn-primary" value="novo" title="Novo" onclick="location.href='?pg=batalhas&op=novo';">Novo</button>
	</td>
    </tr>    
   	<tr>
		<td>
		<center>
        <table cellpadding='4' class="table table-hover table-striped table-fixed">
            <tr>
                <td><b>#</b></td>
                <td><b>Tipo</b></td>
                <td><b>Data</b></td>
                <td><b>Guilda</b></td>
                <td><b>Estrelas</b></td>
                <td><b>Resultado</b></td>
                <td></td>
            </tr>

            <?php
    //leitura da tabela Batalhas
    $sql = "SELECT * FROM batalhas ORDER BY data";
    $readBatalhas = $PDO->query( $sql );
    $num = 0;
    if ($readBatalhas->rowCount() < 1) {
        echo '<span class="ms no">Oppss! Não existem Batalhas cadastrados no momento!!</span>';
    } else {
        foreach ($readBatalhas as $rows){
            $num++;
            ?>
                    <tr>
                        <td><?php echo $num; ?></td>
                        <td><?php echo strtoupper($rows['tipo']); ?></td>
                        <td><center><?php echo $rows['data']; ?></center></td>
                        <td><?php echo $rows['guilda']; ?></td>
                        <td><?php echo $rows['estrelas']; ?></td>
                        <td><?php echo $rows['resultado']; ?></td>
                        <td>
							<?php if ($_SESSION['autUser']['nivel'] == 2 ) { ?>
                            <a href="?pg=batalhas&op=editar&id=<?php echo $rows['id']; ?>&tipo=<?php echo $rows['tipo']; ?>"><img src='images/edit.png'></a>
                            <a href="?pg=batalhas&delete=<?php echo $rows['id']; ?>"><img src='images/del.png'></a>
							<?php } else { ?>
							&nbsp;
							<?php } ?>
                        </td>
                    </tr>
                    <?php
        }
    }
                    ?>
        </table>
		</center>
		</td>
	</tr>
</table>          
<?php
}

else {
	echo "Opção inexistente.";
}
?>
            </div>
        </div>
    </div>
</div>
</body>        

<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
Highcharts.chart('container', {
  title: {
    text: 'Histórico de <?php echo $tipo; ?>'
  },
  xAxis: {
    categories: [
        <?php echo $listaMeses;?>
    ]
  },
  labels: {
    items: [{
      html:' <?php echo $label; ?>',
      style: {
        left: '50px',
        top: '18px',
        color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
      }
    }]
  },
  series: [{
    type: 'column',
    name: 'Bnar',
    color: '#FF0000',
    data: [<?php echo $listaBnar;?>]
  }, {
    type: 'column',
    name: 'Manto',
    color: '#FFD700',
    data: [<?php echo $listaManto;?>]
  }, {
    type: 'column',
    name: 'Solari',
    color: '#00BFFF',
    data: [<?php echo $listaSolari;?>]
  }, {
    type: 'column',
    name: 'Aquamarine',
    color: '#FF8C00',
    data: [<?php echo $listaAquamarine;?>]
  }, {
    type: 'column',
    name: 'Lambent',
    color: '#9932CC',
    data: [<?php echo $listaLambent;?>]
  }, {
    type: 'column',
    name: 'Kaiburr',
    color: '#F08080',
    data: [<?php echo $listaKaiburr;?>]
    }
//  ,
//  {
//    type: 'spline',
//    name: 'Média',
//    data: [<?php echo $listaMedia;?>],
//    marker: {
//      lineWidth: 2,
//      lineColor: Highcharts.getOptions().colors[3],
//      fillColor: 'white'
//    }
//  }
    ]
});

 function setForm(value) {

    if(value != 'TW'){
                document.getElementById('estrelas').style='display:block;';
                document.getElementById('resultado').style='display:none;';
                document.getElementById("resultadoInput").required = false;
            }
            else {
                document.getElementById('resultado').style = 'display:block;';
                document.getElementById('estrelas').style = 'display:none;';
               document.getElementById("estrelasInput").required = false;
            }
};
</script>