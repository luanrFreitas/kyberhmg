<?php
print 1;

$nivel = array(
    "I"			=> 1,
    "II"		=> 2,
    "III"		=> 3,
    "IV"		=> 4,
    "V"			=> 5,
    "VI"		=> 6,
    "VII"		=> 7,
    "VIII"		=> 8,
    "IX"		=> 9,
    "X"			=> 10,
    "XI"		=> 11,
    "XII"		=> 12
);
print 1;
$urlprincipal = "https://swgoh.gg";

$busca = array('">', '</div>', '</a>', 'char-portrait-full-level', 'char-portrait-full-gear-level', 'char-portrait-full-gear');
$troca = array('',    '',      '',     '',                         '',                              '');

if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['pt'])) $lmt = 0; else $lmt = $_GET['pt']*28;

$x = 0;

$membros = $PDO->query( "SELECT * FROM `membros` WHERE idguilda = ".$gd." LIMIT ".$lmt.", 28" );
while ($membro = $membros->fetch( PDO::FETCH_ASSOC )) {

	$url = file_get_contents($urlprincipal."/u/".$membro['link']."/collection/");
//	print $urlprincipal."/u/".$membro['link']."/collection/"."<br>";
//	print "<b>".$membro['nome']."</b><br>";
	
	$colecao = explode("\" title=\"", $url);
	
	for ($i = 2; $i <= $membro['characters']+1; $i++) {
		$colecao[$i] = str_replace($busca, $troca, $colecao[$i]);
		$atual = explode("<div class=\"", $colecao[$i]);
		
//		print $colecao[$i];
//		for ($o = 0; $o <= 10; $o++) {
//			print $o." - ".$atual[$o]."<br>";
//		}
		$idmembro  = $membro['id'];
		$nome  = trim(str_replace("&quot;", "`", $atual[0]));
		$level = preg_replace('/\s/', '', $atual[9]);
		$gear  = preg_replace('/\s/', '', $atual[10]);
		$gear  = $nivel[$gear];
		$star1 = (preg_replace('/\s/', '', $atual[2]) == "starstar1") ? 1 : 0;
		$star2 = (preg_replace('/\s/', '', $atual[3]) == "starstar2") ? 1 : 0;
		$star3 = (preg_replace('/\s/', '', $atual[4]) == "starstar3") ? 1 : 0;
		$star4 = (preg_replace('/\s/', '', $atual[5]) == "starstar4") ? 1 : 0;
		$star5 = (preg_replace('/\s/', '', $atual[6]) == "starstar5") ? 1 : 0;
		$star6 = (preg_replace('/\s/', '', $atual[7]) == "starstar6") ? 1 : 0;
		$star7 = (preg_replace('/\s/', '', $atual[8]) == "starstar7") ? 1 : 0;
//		print $star1."-".$star2."-".$star3."-".$star1."-".$star5."-".$star6."-".$star7."<br>";

//		print "Atualizados 25 membros a partir do registro ".$pg." em um total de ".$num['total']." membros cadastrados<br>";

		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM colecao WHERE idmembro = '".$idmembro."' AND nome = '".$nome."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);

		if ($num_atual[total] > 0) {
			$sql = "UPDATE `colecao` SET 
				nome = '".$nome."', 
				level = '".$level."', 
				gear  = '".$gear."', 
				star1 = ".$star1.", 
				star2 = ".$star2.", 
				star3 = ".$star3.", 
				star4 = ".$star4.", 
				star5 = ".$star5.", 
				star6 = ".$star6.", 
				star7 = ".$star7." 
				WHERE idmembro = '".$idmembro." AND nome = '".$nome."'')";
			$acao = "Atualizado";
		} else {
			$sql = "INSERT INTO `colecao` VALUES (NULL, ".$idmembro.", '".$nome."', '".$level."', '".$gear."', 
				".$star1.", ".$star2.", ".$star3.", ".$star4.", ".$star5.", ".$star6.", ".$star7.")";
			$acao = "<b>Incluído</b>";
		}

		$x = $x + 1;
		$result = $PDO->query( $sql );
		print $x." =>".$acao.": Char ".$nome." - Membro".$idmembro."<br>";
	}
}
?>