<?php
$guildas = array(
	"https://swgoh.gg/g/9854/brazil-empire/zetas/",
	"https://swgoh.gg/g/9863/brazil-rebels/zetas/",
	"https://swgoh.gg/g/10349/brazil-republic/zetas/",
	"https://swgoh.gg/g/10060/brazil-resistance/zetas/",
	"https://swgoh.gg/g/21466/brazil-dagobah/zetas/",
);

for ($i = 0; $i <= count($guildas)-1; $i++) {
	$url = file_get_contents($guildas[$i]);
	$players = explode("<strong>", $url);
	
	for ($o = 2; $o <= count($players)-1; $o++) {
		preg_match_all("/(.*)<\/strong>/", $players[$o], $player);
		preg_match_all("/g\" data-toggle=\"tooltip\" data-container=\"body\" title=\"(.*)\">/", $players[$o], $zetas);
		
		print "<pre>";
		print ($o-1)." - ".$player[1][0]."<br>";
		print_r($zetas[1]);
		print "</pre>";
	}
}

?>