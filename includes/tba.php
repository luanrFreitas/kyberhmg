<?php
$sql = "SELECT membros.id, membros.nome, membros.link, membros.idguilda, membros.collection, membros.gp, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda > 0
	ORDER BY membros.idguilda, membros.nome
	";
$result = $PDO->query( $sql );
echo "<table cellpadding='3'>
	<tr>
		<td style='text-align: center;'><b>#</b></td>
		<td style='text-align: center;'><b>Player</b></td>
		<td style='text-align: center;'><b>Guilda</b></td>
		<td style='text-align: center;'><b>GP</b></td>
		<td style='text-align: center;'><b>CLS</b></td>
		<td style='text-align: center;'><b>Scout</b></td>
		<td style='text-align: center;'><b>Soldier</b></td>
		<td style='text-align: center;'><b>Phoenix</b></td>
		<td style='text-align: center;'><b>RogueOne</b></td>
		<td style='text-align: center;'><b>C. Score</b></td>
		<td style='text-align: center;'><b>TOTAL</b></td>
	</tr>
	";
	
$x = 0;
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	$sqlitem = "SELECT * FROM colecao
		WHERE 
			nome 		= 'Commander Luke Skywalker' and 
			idmembro 	= ".$membro['id']."
		";
	$item = $PDO->query( $sqlitem );
	$itemCLS = $item->fetch( PDO::FETCH_ASSOC );

	$sqlitem = "SELECT * FROM colecao
		WHERE 
			nome 		= 'Hoth Rebel Scout' and 
			idmembro 	= ".$membro['id']."
		";
	$item = $PDO->query( $sqlitem );
	$itemScout = $item->fetch( PDO::FETCH_ASSOC );

	$sqlitem = "SELECT * FROM colecao
		WHERE 
			nome 		= 'Hoth Rebel Soldier' and 
			idmembro 	= ".$membro['id']."
		";
	$item = $PDO->query( $sqlitem );
	$itemSoldier = $item->fetch( PDO::FETCH_ASSOC );

	$sqlitem = "SELECT COUNT(*) AS total 
				FROM colecao
				WHERE idmembro = ".$membro['id']."
				AND 
				(
					(nome = 'Chopper' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Ezra Bridger' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Garazeb `Zeb` Orrelios'  and gear >= 7 and star7 = 1)
					OR
					(nome = 'Hera Syndulla' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Kanan Jarrus' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Sabine Wren' and gear >= 7 and star7 = 1)
				)
		";
	$item = $PDO->query( $sqlitem );
	$itemPhoenix = $item->fetch( PDO::FETCH_ASSOC );

	$sqlitem = "SELECT COUNT(*) AS total 
				FROM colecao
				WHERE idmembro = ".$membro['id']."
				AND 
				(
					(nome = 'Baze Malbus' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Bistan' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Bodhi Rook'  and gear >= 7 and star7 = 1)
					OR
					(nome = 'Cassian Andor' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Chirrut Îmwe' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Jyn Erso' and gear >= 7 and star7 = 1)
					OR
					(nome = 'K-2SO' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Pao' and gear >= 7 and star7 = 1)
					OR
					(nome = 'Scarif Rebel Pathfinder' and gear >= 7 and star7 = 1)
				)
		";
	$item = $PDO->query( $sqlitem );
	$itemRogueOne = $item->fetch( PDO::FETCH_ASSOC );

	$player[$x]['nome'] 			= $membro['nome'];
	$player[$x]['guilda'] 			= $membro['guilda'];
	$player[$x]['gp'] 				= $membro['gp'];
	
	$player[$x]['totalgp'] 			= round($membro['gp']*100/1800000); 
	$player[$x]['totalgp'] 			= round((($membro['gp'] > 1800000 ? 1800000 : $membro['gp'])*100/1800000));
	
	$player[$x]['levelCLS'] 		= ($itemCLS['level'] == ""		? "-" : ($itemCLS['star1']+$itemCLS['star2']+$itemCLS['star3']+$itemCLS['star4']+$itemCLS['star5']+$itemCLS['star6']+$itemCLS['star7'])."* ".$itemCLS['level']." G".$itemCLS['gear']);
	$player[$x]['totalCLS'] 		= round(((($itemCLS['star1']+$itemCLS['star2']+$itemCLS['star3']+$itemCLS['star4']+$itemCLS['star5']+$itemCLS['star6']+$itemCLS['star7'])*100/7)+($itemCLS['gear']*100/11)+($itemCLS['level']*100/85))/3);
	
	$player[$x]['corteScout'] 		= 5;
	$player[$x]['levelScout'] 		= ($itemScout['level'] == ""	? "-" : ($itemScout['star1']+$itemScout['star2']+$itemScout['star3']+$itemScout['star4']+$itemScout['star5']+$itemScout['star6']+$itemScout['star7'])."* ".$itemScout['level']." G".$itemScout['gear']);
	$player[$x]['itemScout'] 		= $itemScout['star1']+$itemScout['star2']+$itemScout['star3']+$itemScout['star4']+$itemScout['star5']+$itemScout['star6']+$itemScout['star7'];
	$player[$x]['totalScout'] 		= round(((($itemScout['star1']+$itemScout['star2']+$itemScout['star3']+$itemScout['star4']+$itemScout['star5']+$itemScout['star6']+$itemScout['star7'])*100/7)+($itemScout['gear']*100/11)+($itemScout['level']*100/85))/3);
	$player[$x]['totalScout'] 		= ($player[$x]['itemScout'] < $player[$x]['corteScout'] ? 0 : $player[$x]['totalScout']);
	
	$player[$x]['corteSoldier'] 	= 5;	
	$player[$x]['levelSoldier'] 	= ($itemSoldier['level'] == ""	? "-" : ($itemSoldier['star1']+$itemSoldier['star2']+$itemSoldier['star3']+$itemSoldier['star4']+$itemSoldier['star5']+$itemSoldier['star6']+$itemSoldier['star7'])."* ".$itemSoldier['level']." G".$itemSoldier['gear']);
	$player[$x]['itemSoldier'] 		= $itemSoldier['star1']+$itemSoldier['star2']+$itemSoldier['star3']+$itemSoldier['star4']+$itemSoldier['star5']+$itemSoldier['star6']+$itemSoldier['star7'];
	$player[$x]['totalSoldier'] 	= round(((($itemSoldier['star1']+$itemSoldier['star2']+$itemSoldier['star3']+$itemSoldier['star4']+$itemSoldier['star5']+$itemSoldier['star6']+$itemSoldier['star7'])*100/7)+($itemSoldier['gear']*100/11)+($itemSoldier['level']*100/85))/3);
	$player[$x]['totalSoldier'] 	= ($player[$x]['itemSoldier'] < $player[$x]['corteSoldier'] ? 0 : $player[$x]['totalSoldier']);
	
	$player[$x]['levelPhoenix'] 	= $itemPhoenix['total'];
	$player[$x]['totalPhoenix'] 	= round((($itemPhoenix['total'] > 5 ? 5 : $itemPhoenix['total'])*100/5));
	$player[$x]['totalPhoenix'] 	= $player[$x]['totalPhoenix'] < 50 ? 0 : $player[$x]['totalPhoenix'];
	
	$player[$x]['levelRogueOne'] 	= $itemRogueOne['total'];
	$player[$x]['totalRogueOne'] 	= round((($itemRogueOne['total'] > 5 ? 5 : $itemRogueOne['total'])*100/5));
	$player[$x]['totalRogueOne'] 	= $player[$x]['totalRogueOne'] < 50 ? 0 : $player[$x]['totalRogueOne'];
	
	$player[$x]['collection'] 		= $membro['collection'];
	$player[$x]['total'] 			= ($player[$x]['totalgp']+$player[$x]['totalCLS']+$player[$x]['totalScout']+$player[$x]['totalSoldier']+$player[$x]['totalPhoenix']+$player[$x]['totalRogueOne'])/6;

	$x++;
}

sksort($player, 'total');

for ($y = 0; $y < count($player); $y++) {
	echo "<tr>
		<td><b>".($y+1)."</b></td>
		<td>".$player[$y]['nome']."</td>
		<td>".$player[$y]['guilda']."</td>
		<td style='text-align: right;  background-color: ".bkcor($player[$y]['totalgp'], 0, 1).";'>".number_format($player[$y]['gp'], 0, ',', '.')."</td>
		<td style='text-align: center; background-color: ".bkcor($player[$y]['totalCLS'], 0, 1).";'>".$player[$y]['levelCLS']."<br>".$player[$y]['totalCLS']."%</td>
		<td style='text-align: center; background-color: ".bkcor($player[$y]['totalScout'], $player[$y]['corteScout'], $player[$y]['itemScout']).";'>".$player[$y]['levelScout']."<br>".($player[$y]['totalScout'] == 0 ? "-" : $player[$y]['totalScout']."%")."</td>
		<td style='text-align: center; background-color: ".bkcor($player[$y]['totalSoldier'], $player[$y]['corteSoldier'], $player[$y]['itemSoldier']).";'>".$player[$y]['levelSoldier']."<br>".($player[$y]['totalSoldier'] == 0 ? "-" : $player[$y]['totalSoldier']."%")."</td>
		<td style='text-align: center; background-color: ".bkcor($player[$y]['totalPhoenix'], 0, 1).";'>".$player[$y]['levelPhoenix']."<br>".($player[$y]['totalPhoenix'] == 0 ? "-" : $player[$y]['totalPhoenix']."%")."</td>
		<td style='text-align: center; background-color: ".bkcor($player[$y]['totalRogueOne'], 0, 1).";'>".$player[$y]['levelRogueOne']."<br>".($player[$y]['totalRogueOne'] == 0 ? "-" : $player[$y]['totalRogueOne']."%")."</td>
		<td style='text-align: center;'>".$player[$y]['collection'] ."</td>
		<td style='text-align: center;'>".number_format($player[$y]['total'], 2, ',', '.')."%</td>
	</tr>
	";
}


echo "</table>";


function bkcor ($valor, $corte, $item) {
	if ($valor >= 95) $cor = '#CCFFCC';
	elseif ($valor >= 60) $cor = '#FFFFCC';
	else $cor = '#FFCCCC';
	
	if ($item < $corte) $cor = '#FFCCCC';
	
	return $cor;
}

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