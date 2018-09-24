<style>
a.ord {text-decoration: none; color:#000;} 
</style>
<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d", strtotime("-1 days")); else $dt = $_GET['dt'];
if (is_null($_GET['tp'])) $tp = "fosso"; else $tp = $_GET['tp'];

if (!isset($_SESSION['dt_ini'])) $_SESSION['dt_ini'] = date("Y-m-d", strtotime("-14 days"));
if (is_null($_POST['dt_ini'])) $dt_ini = $_SESSION['dt_ini']; else $dt_ini = $_POST['dt_ini'];
if (!isset($_SESSION['dt_ter'])) $_SESSION['dt_ter'] = date("Y-m-d", strtotime("-1 days"));
if (is_null($_POST['dt_ter'])) $dt_ter = $_SESSION['dt_ter']; else $dt_ter = $_POST['dt_ter'];

if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

$dt = date("Y-m-d", strtotime("now"));

$op = $_POST['op'];

if (!isset($_SESSION['ord'])) $_SESSION['ord'] = 'final';
if (is_null($_SESSION['ord'])) $ord = 'final';
if (is_null($_GET['ord'])) $ord = $_SESSION['ord']; else $ord = $_GET['ord'];


$sqlCiclo = "SELECT * FROM ciclos ORDER BY inicio DESC LIMIT 10";
$readUser = $PDO->query( $sqlCiclo );
foreach ($readUser as $rows){
	if (is_null($_GET['ciclo'])) {
		$date1 = new DateTime($rows['inicio']);
		$date2 = new DateTime($rows['termino']);
		$hoje  = new DateTime("now");

		if ($date1 < $hoje AND $date2 > $hoje) $_GET['ciclo'] = $rows['id'];
	}

	if ($_GET['ciclo'] ==  $rows['id']) {
		$dt_ini = $rows['inicio'];
		$dt_ter = $rows['termino'];
	}
	$opcao .= "<option value='".$rows['id']."' ".(($_GET['ciclo'] ==  $rows['id']) ? "selected" : "").">De ".date("d/m/Y", strtotime($rows['inicio']))." a ".date("d/m/Y", strtotime($rows['termino']))."</option>";
}

$dateStart = new DateTime(date("Y-m-d", strtotime($dt_ini)));
$dateNow   = new DateTime(date("Y-m-d", strtotime($dt_ter)));
 
$dateDiff = $dateStart->diff($dateNow);

// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT membros.id, membros.nome, membros.idguilda, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";

// Gera os registros caso nenhum exista ainda para o print
$result = $PDO->query( $sql );

$ini = 0;
$r_dias = ""; $t_r_dias = 0;
if ($tp == "fosso") 	{ $r_tipo = "rancor"; $n_tipo = "fosso"; }
if ($tp == "tanque") 	{ $r_tipo = "tanque"; $n_tipo = "tanque"; }
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$date = $dt_ini;
	while (strtotime($date) <= strtotime($dt_ter)) {
		$check_raid = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = '".$r_tipo."'");
		$num_raid = $check_raid->fetch(PDO::FETCH_ASSOC);
		if ($num_raid[total] > 0) {
			$atual = $PDO->query("SELECT COUNT(*) AS total FROM raid INNER JOIN membros ON raid.idmembro = membros.id WHERE idmembro = ".$membro['id']." AND membros.idguilda = '".$gd."' AND data = '".$date."' AND tipo = '".$n_tipo."'");
			$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
			if ($num_atual[total] > 0) {
			} else {
				$sql2 = "INSERT INTO `raid` VALUES (NULL, '".$membro['id']."', '".$date."',  '".$n_tipo."', 0)";
				$PDO->query( $sql2 );
			}
			if ($ini == 0) {
				$r_dias .= "<td style='width:60px; text-align: center'><b>".date("d", strtotime($date))."</b></td>";
				$t_r_dias += 1;
			}
		}
		
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	$ini += 1;
}

$guilda = 0;

// Busca os membros ativos na guilda
$sql = "SELECT DISTINCT membros.id, membros.nome, membros.telegram, membros.idguilda, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda = ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";
	
$result = $PDO->query( $sql );
$x = 0;

echo "
	<table width='100%'>";
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	if ($membro['idguilda'] <> $guilda and $guilda != 0) {
		echo "
		</table>
		<br><br>
		<table width='100%'>";
	}
	if ($membro['idguilda'] <> $guilda) {
		$guilda = $membro['idguilda'];
		echo "
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='".(8+$t_r_dias)."'>
				<br>".ucfirst($membro['guilda'])."<br><br>
			</td>
		</tr>
		<form action='' method='post'>
		<tr>
			<td colspan='".(8+$t_r_dias)."'>
				<b>Ciclo: </b> 
				<select name='ciclo' id='ciclo'>".$opcao."</select>
			</td>
		</tr>
		</form>
		";
	}
	
	
	$dados[$x]['nome'] = $membro['nome'];
	$dados[$x]['telegram'] = $membro['telegram'];
			
	for ($w = 0; $w <= $dateDiff->d; $w++) {
		$wdata = date("Y-m-d", strtotime($dt_ini." +".$w." days"));
		
		$wres = strtotime($dt_ini." +".$w." days");
		$watual = strtotime("-1 days");
		
		if ($wres < $watual) {
			$wresult 	= $PDO->query( "SELECT * FROM warning WHERE idmembro = '".$membro['id']."' AND data = '".$wdata."'", PDO::FETCH_ASSOC);
			while ($wmembro = $wresult->fetch( PDO::FETCH_ASSOC )) {
				//if ($membro['id'] == 592) ECHO $wmembro['qtd']." - ".$wdata."<br>";
				$dados[$x]['warning'] += $wmembro['qtd'];
			}
		}
	}

			
	$date = $dt_ini;
	$qtdraid = 0;
	$k = 0;
	$ataques = 0;
	$melhor = 0;
	$maiorA = 0;
	$maiorB = 0;	
	while (strtotime($date) <= strtotime($dt_ter)) {
		$check_raid = $PDO->query("SELECT COUNT(*) AS total FROM raid_lancada WHERE idguilda = ".$gd." AND data  = '".$date."' AND tipo  = '".$r_tipo."'");
		$num_raid = $check_raid->fetch(PDO::FETCH_ASSOC);
		if ($num_raid[total] > 0) {
			$atual = $PDO->query( "SELECT valor FROM raid WHERE idmembro = '".$membro['id']."' AND data = '".$date."' AND tipo = '".$n_tipo."'" );
			$m_atual = $atual->fetch(PDO::FETCH_ASSOC);
			
			$dados[$x]['total'] += $m_atual['valor'];
			$dados[$x]['melhor'] = ($m_atual['valor'] > $dados[$x]['melhor'] ? $m_atual['valor'] : $dados[$x]['melhor']);
			
			$dados[$x][$k] = $m_atual['valor'];
			$k++;

			$qtdraid += 1;
			if (strtotime($date) <= strtotime("-1 day")){
				if ($melhor == 0) $melhor = $m_atual['valor'];
				if ($m_atual['valor'] >= $melhor) {
					$maiorA = $melhor;
					$melhor = $m_atual['valor'];
				}
				if (strtotime($date) == ( strtotime("-1 day", strtotime($dt_ter)) ) AND $m_atual['valor'] < $melhor) {
					$maiorA = $melhor;
				}
				$maiorB = $m_atual['valor'];
			}
		} 
		
		$dados[$x]['media'] = ceil($dados[$x]['total']/$qtdraid);
		$dados[$x]['final'] = ceil($dados[$x]['media'] - ($dados[$x]['media']*$dados[$x]['warning'])/5);
		$dados[$x]['final'] = ($dados[$x]['final'] < 0 ? 0 : $dados[$x]['final']);
		$dados[$x]['crescimento'] 	= 100*($maiorB-$maiorA)/$maiorA;
		
		$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
	$x++;	
}



sksort($dados, $ord);

echo "
<tr style='border-bottom: 1px solid #ddd;'>
	<td width='150px'><b>Nomes</b></td>
	<td width='150px'><b>Telegram</b></td>"
	.$r_dias."
	<td style='width:100px; text-align: center;' onmouseover=\"this.bgColor='#CCC'\" onmouseout=\"this.bgColor='#F2F2F2'\">&nbsp;&nbsp;<b>".($ord != 'melhor' ? "<a href='?pg=$pg&tp=$tp&gd=$gd&ord=melhor&ciclo=$ciclo' class='ord'>Melhor Dano</a>" : "Melhor Dano <img src='images/ord.gif'>")."</b></a></td>
	<td style='width:100px; text-align: center;' onmouseover=\"this.bgColor='#CCC'\" onmouseout=\"this.bgColor='#F2F2F2'\">&nbsp;&nbsp;<b>".($ord != 'media' ? "<a href='?pg=$pg&tp=$tp&gd=$gd&ord=media&ciclo=$ciclo' class='ord'>Média Total</a>" : "Média Total <img src='images/ord.gif'>")."</b></a></td>
	<td style='width:30px; text-align: center;' onmouseover=\"this.bgColor='#CCC'\" onmouseout=\"this.bgColor='#F2F2F2'\">&nbsp;&nbsp;<b>".($ord != 'warning' ? "<a href='?pg=$pg&tp=$tp&gd=$gd&ord=warning&ciclo=$ciclo' class='ord'>Warn</a>" : "Warn <img src='images/ord.gif'>")."</b></td>
	<td style='width:100px; text-align: center;' onmouseover=\"this.bgColor='#CCC'\" onmouseout=\"this.bgColor='#F2F2F2'\">&nbsp;&nbsp;<b>".($ord != 'final' ? "<a href='?pg=$pg&tp=$tp&gd=$gd&ord=final&ciclo=$ciclo' class='ord'>Média Final</a>" : "Média Final <img src='images/ord.gif'>")."</b></td>
	<td style='width:40px; text-align: center;' onmouseover=\"this.bgColor='#CCC'\" onmouseout=\"this.bgColor='#F2F2F2'\">&nbsp;&nbsp;<b>".($ord != 'crescimento' ? "<a href='?pg=$pg&tp=$tp&gd=$gd&ord=crescimento&ciclo=$ciclo' class='ord'>Cresc.(%)</a>" : "Cresc.(%) <img src='images/ord.gif'>")."</b></a></td>
	<td style='text-align: center;'><b></b></td>
</tr>
";
		
for ($y = 0; $y < count($dados); $y++) {
	echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".($y+1)." - ".$dados[$y]['nome']."</td>
		<td>".$dados[$y]['telegram']."</td>";
	for ($d = 0; $d < $k; $d++) {
		echo "<td style='text-align: right;'>".number_format($dados[$y][$d], 0, ',', '.')."</td>";
	}
	echo "
		<td style='text-align: right;'><b>".number_format($dados[$y]['melhor'], 0, ',', '.')."<b></td>
		<td style='text-align: right;'><b>".number_format($dados[$y]['media'], 0, ',', '.')."<b></td>
		<td style='text-align: right;'><b>".number_format($dados[$y]['warning'], 0, ',', '.')."<b></td>
		<td style='text-align: right;'><b>".number_format($dados[$y]['final'], 0, ',', '.')."<b></td>
		<td style='text-align: right;'><b>".number_format($dados[$y]['crescimento'], 0, ',', '.')."%<b></td>
		<td></td>
	</tr>";
}

echo "
</table>
</form>";



function sksort(&$array, $subkey="id", $sort_ascending=false) {

    if (count($array))
        $temp_array[key($array)] = array_shift($array);

    foreach($array as $key => $val){
        $offset = 0;
        $found = false;
        foreach($temp_array as $tmp_key => $tmp_val)
        {
            if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
            {
                $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                            array($key => $val),
                                            array_slice($temp_array,$offset)
                                          );
                $found = true;
            }
            $offset++;
        }
        if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
    }

    if ($sort_ascending) $array = array_reverse($temp_array);

    else $array = $temp_array;
}
?>

<script>
document.getElementById("ciclo").addEventListener("change", myFunction);

function myFunction() {
    var x = document.getElementById("ciclo");
    x.value = x.value.toUpperCase();
	location.href="<?=$site;?>?pg=<?=$pg;?>&tp=<?=$tp;?>&gd=<?=$gd;?>&ciclo="+x.value
}
</script>