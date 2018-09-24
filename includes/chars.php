<?php
$PDO->query( "TRUNCATE `chars`" );

$urlprincipal = "https://swgoh.gg";
$url = file_get_contents('https://swgoh.gg');
$chars = explode("<li class=\"media list-group-item p-0 character\" data-name-lower=\"", $url);

$busca = array('');
$troca = array('');

//for ($i = 1; $i <= 3; $i++) {
for ($i = 1; $i <= count($chars)-1; $i++) {
	$char = explode("\" data-tags=\"", $chars[$i]);
	$nome_curto = $char[0];
	
	$char = explode("href=\"/characters/", $char[1]);
	$char = explode("/\">", $char[1]);
	$link = trim(str_replace("/\">", "", $char[0]));
	
	$char = explode("swgoh.gg/static/img/assets/", $char[1]);
	$char = explode("\" alt=\"", $char[1]);
	$foto = $char[0];
	
	$char = explode("alt=\"", $char[1]);
	$char = explode("\" height=\"80\" width=\"80\"></div>", $char[0]);
	//$nome = $char[0];
	$nome  = trim(str_replace("&quot;", "`", $char[0]));
	
	$char = explode("13px;\"><span class=\"hidden-xs\">", $char[1]);
	$char = explode("</small>", $char[1]);
	$tipo = str_replace(" · ", "/", $char[0]);

//	print $i."=> ".$nome." - ".$nome_curto." - ".$link." - ".$foto." - ".$tipo."<br>";
//	print $tipo."<br>";

	for ($o = 0; $o <= 27;$o++) { $tp[$o] = 0; }
	
	$tipo = explode("/", $tipo);
	for ($o = 0; $o < count($tipo);$o++) {
		$tipo[$o] = str_replace("<", "", $tipo[$o]);
		$tipo[$o] = str_replace("span>", "", $tipo[$o]);
		//PRINT $tipo[$o]."<BR>";
		
		if ($tipo[$o] == 'Dark Side') 			{ $tp[0] = 1; }
		if ($tipo[$o] == 'Light Side') 			{ $tp[1] = 1; }
		if ($tipo[$o] == 'Attacker') 			{ $tp[2] = 1; }
		if ($tipo[$o] == 'Bounty Hunter')		{ $tp[3] = 1; }
		if ($tipo[$o] == 'Clone Trooper')		{ $tp[4] = 1; }
		if ($tipo[$o] == 'Droid') 				{ $tp[5] = 1; }
		if ($tipo[$o] == 'Empire') 				{ $tp[6] = 1; }
		if ($tipo[$o] == 'Ewok') 				{ $tp[7] = 1; }
		if ($tipo[$o] == 'First Order') 		{ $tp[8] = 1; }
		if ($tipo[$o] == 'Fleet Commander') 	{ $tp[9] = 1; }
		if ($tipo[$o] == 'Geonosian') 			{ $tp[10] = 1; }
		if ($tipo[$o] == 'Healer') 				{ $tp[11] = 1; }
		if ($tipo[$o] == 'Human') 				{ $tp[12] = 1; }
		if ($tipo[$o] == 'Jawa') 				{ $tp[13] = 1; }
		if ($tipo[$o] == 'Jedi') 				{ $tp[14] = 1; }
		if ($tipo[$o] == 'Nightsister') 		{ $tp[15] = 1; }
		if ($tipo[$o] == 'Rebel') 				{ $tp[16] = 1; }
		if ($tipo[$o] == 'Resistance') 			{ $tp[17] = 1; }
		if ($tipo[$o] == 'Scoundrel') 			{ $tp[18] = 1; }
		if ($tipo[$o] == 'Separatist') 			{ $tp[19] = 1; }
		if ($tipo[$o] == 'Sith') 				{ $tp[20] = 1; }
		if ($tipo[$o] == 'Support') 			{ $tp[21] = 1; }
		if ($tipo[$o] == 'Tank') 				{ $tp[22] = 1; }
		if ($tipo[$o] == 'Tusken') 				{ $tp[23] = 1; }
		if ($tipo[$o] == 'Capital Ship') 		{ $tp[24] = 1; }
		if ($tipo[$o] == 'Phoenix') 			{ $tp[25] = 1; }
		if ($tipo[$o] == 'Galactic Republic')	{ $tp[26] = 1; }
		if ($tipo[$o] == 'Imperial Trooper')	{ $tp[27] = 1; }
	}
	
	$sql = "INSERT INTO `chars` VALUES (NULL, '".$nome."', '".$link."', ".$tp[0].", 
		".$tp[1].",  ".$tp[2].",  ".$tp[3].",  ".$tp[4].",  ".$tp[5].",  ".$tp[6].",  ".$tp[7].",  ".$tp[8].",  ".$tp[9].",  ".$tp[10].", 
		".$tp[11].", ".$tp[12].", ".$tp[13].", ".$tp[14].", ".$tp[15].", ".$tp[16].", ".$tp[17].", ".$tp[18].", ".$tp[19].", ".$tp[20].", 
		".$tp[21].", ".$tp[22].", ".$tp[23].", ".$tp[24].", ".$tp[25].", ".$tp[26].", ".$tp[27].")";
	//print $sql."<br>";
	$PDO->query( $sql );
	//mysql_query($sql);
	//$chars[1] = str_replace($busca, $troca, $chars[1]);
	print ($i)." - ".$nome."<br>";

	//print (count($chars)-1)."<br>";
	//$chars[1] = explode("</table", $usuarios[1]);
	//$chars[1] = $chars[1][0];
}
?>