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

<?php require 'includes/rank_dados.php'; 
      require 'includes/eventos_dados.php';?>

<div class="text-center">
    
    <form action="" method="get">
    <p>Selecione um jogador da União Kyber para exibir seus dados<p>
    <input type="text" name ="pg" value="raids_new" hidden />
    </p>
        <select name="jogador">
            <option>Selecione...</option>
            <?php
            foreach ($readJogadores as $jogadores)
                echo '<option value="'.$jogadores['player'].'">'.$jogadores['player'].'</option>' ;
            ?>
        </select>
        &nbsp;&nbsp;    
         <select name="raid" >
            <option>...</option>
            <option value="rancor">Rancor</option>
            <option value="tanque">Tanque</option>
            <option value="sith">Sith</option>
        </select>
        <input type="submit" value="Ok" />					
    </p>
    </form>

<?php
if(isset($_GET['raid']))
{
?>
    <h2> PHASE 1 </h2>
<?php
$TimesRaids= $PDO->query("SELECT * FROM `times` WHERE `raid` = '".$_GET['raid']."' ");
    for ($i =0 ; $i <= count($TimesRaids) -1; $i++) {
   
        if ($time['fase']== 1){
            
            echo 'Nome do Time: '. $TimesRaids[$i]; 
            echo 'Fase do Time: '. $TimesRaids[$i]; 
            echo '</br>';
            
            
        }
//       echo 'Nome do Time: '. $time['nome']; 
//       echo '</br>';
    }
echo '<pre>'.var_export($char['AAYLASECURA']['name']).'</pre>';

}

?>

</div>