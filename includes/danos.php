<style>
a.ord {text-decoration: none; color:#000;} 
</style>
<?php

$dt = date("Y-m-d", strtotime("now"));

// Busca os membros ativos na guilda
$sql = "SELECT 
			`membros`.id, `membros`.nome, `membros`.telegram, `membros`.idguilda, `raid`.valor, `guildas`.nome AS guilda
		FROM `membros` 
		INNER JOIN `raid` ON 
			`membros`.id = `raid`.`idmembro`
		INNER JOIN `guildas` ON 
			`membros`.idguilda = `guildas`.`id`
		WHERE 
			`membros`.idguilda > 0  AND
			`raid`.valor = (SELECT MAX(valor) FROM `raid` WHERE `membros`.id = `raid`.idmembro AND `raid`.tipo = 'tanque' AND `raid`.nivel = 'H' GROUP BY `idmembro` LIMIT 1) 
		GROUP BY `membros`.nome			
		ORDER BY `raid`.valor DESC
	";

$result = $PDO->query( $sql );

$x = 0;

echo "
	<table width='100%'>
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='5'>
				<br>Brazil<br><br>
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='150px'><b>Nomes</b></td>
			<td width='150px'><b>Telegram</b></td>
			<td style='width:100px; text-align: center;'>&nbsp;&nbsp;<b>Melhor Dano</b></a></td>
			<td style='width:100px; text-align: center;'><b>Guilda Atual</b></a></td>
			<td style='text-align: center;'><b></b></td>
		</tr>
";

$total = 0;
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$x++;
	echo "
		<tr style='border-bottom: 1px solid #ddd;'>
			<td>".($x)." - ".$membro['nome']."</td>
			<td>".$membro['telegram']."</td>
			<td style='text-align: right;'><b>".number_format($membro['valor'], 0, ',', '.')."<b></td>
			<td>&nbsp;&nbsp;".$membro['guilda']."</td>
			<td></td>
		</tr>";
		
		$total += $membro['valor'];
}

	echo "
		<tr style='border-bottom: 1px solid #ddd;'>
			<td></td>
			<td style='text-align: right;'><b>Total</b></td>
			<td style='text-align: right;'><b>".number_format($total, 0, ',', '.')."<b></td>
			<td></td>
			<td></td>
		</tr>";
echo "
</table>
</form><br><br>";



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