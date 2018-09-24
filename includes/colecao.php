<?php
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
$urlprincipal = "https://swgoh.gg";
$membro_atual = "";

$busca = array('">', '</div>', '</a>', 'char-portrait-full-level', 'char-portrait-full-gear-level', 'char-portrait-full-gear');
$troca = array('',    '',      '',     '',                         '',                              '');

if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['lmt'])) $lmt = 0; else $lmt = $_GET['lmt']*27;

$x = 0;

$membros = $PDO->query( "SELECT * FROM `membros` WHERE idguilda = ".$gd." AND link != '' ORDER BY nome ASC" );
//$membros = $PDO->query( "SELECT * FROM `membros` WHERE idguilda = ".$gd." AND link != '' LIMIT 1" );
//$membros = $PDO->query( "SELECT * FROM `membros` WHERE idguilda = ".$gd." LIMIT ".$lmt.", 28" );

echo "<table cellpadding='5'>
	<tr>
		<td>#</td>
		<td>Membro</td>
		<td>GP</td>
		<td>GP Chars</td>
		<td>GP Ships</td>
		<td>AllyCode</td>
		<td>Score</td>
		<td>Chars</td>
		<td>Level</td>
		<td>Arena</td>
		<td>Chars Power</td>
	</tr>
";
while ($membro = $membros->fetch( PDO::FETCH_ASSOC )) {

	$GP = "";
	$GPC = "";
	$GPS = "";
	$ally = "";
	$dados = "";
	$chars = "";
	$nome = "";
	$level = "";
	$gear = "";
	$star1 = "";
	$star2 = "";
	$star3 = "";
	$star4 = "";
	$star5 = "";
	$star6 = "";
	$star7 = "";
	$power = "";

	$url = file_get_contents($urlprincipal."/u/".$membro['link']);
	preg_match_all("/<p>Galactic Power <strong class=\"pull-right\">(.*)<\/strong><\/p>/", $url, $GP);
	preg_match_all("/<p>Galactic Power \(Characters\) <strong class=\"pull-right\">(.*)<\/strong><\/p>/", $url, $GPC);
	preg_match_all("/<p>Galactic Power \(Ships\) <strong class=\"pull-right\">(.*)<\/strong><\/p>/", $url, $GPS);
	preg_match_all("/<p>Ally Code <strong class=\"pull-right\">(.*)<\/strong><\/p>/", $url, $ally);
	preg_match_all("/<h5 class=\"m-y-0\">(.*)<\/h5>/", $url, $dados);

	try {
		//$url2 = file_get_contents($urlprincipal."/u/".$membro['link']."/collection/");
		
		$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n")); 
		/*
		$opts = array('http'=>array(
			'header'=>"Accept-language: en\r\n" .
				"Cookie: foo=bar\r\n" .
				"User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)\r\n")); 
		*/
		//Basically adding headers to the request
		$context = stream_context_create($opts);
		$url2 = file_get_contents($urlprincipal."/u/".$membro['link']."/collection/", false, $context);
		//$url2 = htmlspecialchars($url2);

		if ($url2 === false) {
			$error = error_get_last();
			echo "HTTP request failed. Error was: ".$error['message']."<br>";
			$power[1][0] = 1;
		} else {
			preg_match_all("/Characters \((.*)\)/", $url2, $chars);
			preg_match_all("/alt=\"(.*)\">/", $url2, $nome);
			preg_match_all("/<div class=\"char-portrait-full-level\">(.*)<\/div>/", $url2, $level);
			preg_match_all("/<div class=\"char-portrait-full-gear-level\">(.*)<\/div>/", $url2, $gear);
			preg_match_all("/star star1(.*)\"><\/div>/", $url2, $star1);
			preg_match_all("/star star2(.*)\"><\/div>/", $url2, $star2);
			preg_match_all("/star star3(.*)\"><\/div>/", $url2, $star3);
			preg_match_all("/star star4(.*)\"><\/div>/", $url2, $star4);
			preg_match_all("/star star5(.*)\"><\/div>/", $url2, $star5);
			preg_match_all("/star star6(.*)\"><\/div>/", $url2, $star6);
			preg_match_all("/star star7(.*)\"><\/div>/", $url2, $star7);
			preg_match_all("/title=\"Power (.*) \//", $url2, $power);

			$search = ','; $replace = '';
			array_walk($power[1],
				function (&$v) use ($search, $replace){
					$v = str_replace($search, $replace, $v);
				}
			);   
			
			//print $urlprincipal."/u/".$membro['link']."/collection/"."<br>";
			//print "<b>".$membro['nome']."</b><br>";

			//$colecao = explode(" alt=\"", $url);
			$linhas  = "";
			//print_r ($colecao);
			
			//for ($i = 2; $i <= $membro['characters']+1; $i++) {
			//echo $chars[1][0]."<br>";
			for ($i = 0; $i <= $chars[1][0]-1; $i++) {
				//if ($i == 0) echo $membro['id']."<br>";

				if ($linhas != "" ) $linhas .= ", ";
				$linhas .= "('".$membro['id']."', \"".trim(str_replace("&quot;", "`", $nome[1][$i]))."\", '".$level[1][$i]."', '".$nivel[$gear[1][$i]]
					."', ".($star1[1][$i] == "" ? 1 : 0 ).", ".($star2[1][$i] == "" ? 1 : 0 ).", ".($star3[1][$i] == "" ? 1 : 0 )
					.", ".($star4[1][$i] == "" ? 1 : 0 ).", ".($star5[1][$i] == "" ? 1 : 0 ).", ".($star6[1][$i] == "" ? 1 : 0 )
					.", ".($star7[1][$i] == "" ? 1 : 0 ).", ".str_replace(",", "", $power[1][$i]).")";
				
			}
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		// Handle exception
		$power[1][0] = 2;
	}

	$x = $x + 1;
	$membro_atual = $membro['nome'];
	echo "
	<tr>
		<td><b>".$x."</b></td>
		<td><b>".$membro['nome']."</b></td>
		<td>".$GP[1][0]."</td>
		<td>".$GPC[1][0]."</td>
		<td>".$GPS[1][0]."</td>
		<td>".$ally[1][0]."</td>
		<td>".$dados[1][3]."</td>
		<td>".$dados[1][4]."</td>
		<td>".$dados[1][2]."</td>
		<td>".$dados[1][1]."</td>
		<td>".str_replace(",", "", array_sum($power[1]))."</td>
	</tr>";

	$sql = "UPDATE `membros` SET 
		allycode 	= '".$ally[1][0]."', 
		collection 	= '".$dados[1][3]."', 
		characters  = '".$dados[1][4]."', 
		level 		= '".$dados[1][2]."', 
		arena 		= '".$dados[1][1]."', 
		gp  		= ".str_replace(",", "", $GP[1][0]).", 
		gpc  		= ".str_replace(",", "", $GPC[1][0]).", 
		gps  		= ".str_replace(",", "", $GPS[1][0])." 
		WHERE id = '".$membro['id']."'";
	
	$PDO->query( $sql );
	//print "&nbsp;&nbsp;&nbsp;".$x." => ".$acao.": Char ".$nome."<br>";
	//print "&nbsp;&nbsp;&nbsp;".$x." => ".$sql.": Char ".$nome."<br>";

	$sql = "INSERT INTO `colecao` (idmembro, nome, level, gear, star1, star2, star3, star4, star5, star6, star7, power)
		VALUES ".$linhas."
		ON DUPLICATE KEY UPDATE
		level = VALUES(level), gear = VALUES(gear), star1 = VALUES(star1), star2 = VALUES(star2), star3 = VALUES(star3), 
		star4 = VALUES(star4), star5 = VALUES(star5), star6 = VALUES(star6), star7 = VALUES(star7), power = VALUES(power)";
	
	$result = $PDO->query( $sql );

}
echo "</table>";


function fOpenRequest($url) {
   $file = fopen($url, 'r');
   $data = stream_get_contents($file);
   fclose($file);
   return $data;
}

function url_get_contents ($url) {
    if (function_exists('curl_exec')){ 
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        $url_get_contents_data = (curl_exec($conn));
        curl_close($conn);
    }elseif(function_exists('file_get_contents')){
        $url_get_contents_data = file_get_contents($url);
    }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
        $handle = fopen ($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
    }else{
        $url_get_contents_data = false;
    }
return $url_get_contents_data;
} 

function http_get_contents($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if(FALSE === ($retval = curl_exec($ch))) {
		echo curl_error($ch);
	} else {
		return $retval;
	}
}
?>