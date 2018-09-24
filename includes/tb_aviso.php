<aside id="colorlib-hero">
	<div class="flexslider">
		<ul class="slides">
		<li style="background-image: url(images/img_bg_2.jpg);">
			<div class="overlay"></div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-md-offset-3 slider-text">
						<div class="slider-text-inner text-center">
							<h1>Aviso</h1>
							<h2><span>Discord | Mensagem</span></h2>
						</div>
					</div>
				</div>
			</div>
		</li>
		</ul>
	</div>
</aside>
	<div class="colorlib-event"  style ="overflow-x: auto;">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2>Mensagem Enviada ao Discord com sucesso</h2>
					<p><input type="button" onclick="location.href='http:<?php echo $site; ?>/?pg=tb'" value="Voltar" />
<?PHP
function randomHex() {
	$chars = 'ABCDEF0123456789';
	//$color = '0x';
	for ( $i = 0; $i < 6; $i++ ) {
		$color .= $chars[rand(0, strlen($chars) - 1)];
	}
	return $color;
}

function jsonEncodeArray( $array ){
    array_walk_recursive( $array, function(&$item) { 
       $item = utf8_encode( $item ); 
    });
    return json_encode( $array );
}

$guilda 		= filter_input(INPUT_POST, 'guilda', FILTER_SANITIZE_STRING);
$lado 			= filter_input(INPUT_POST, 'lado', FILTER_SANITIZE_STRING);
$fase 			= filter_input(INPUT_POST, 'fase', FILTER_SANITIZE_STRING);
$territorio 	= filter_input(INPUT_POST, 'territorio', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$guilda_nome = $PDO->query( "SELECT link, nome, webhook_tb FROM guildas WHERE ativo = '1' AND link LIKE '%".$guilda."%' ORDER BY nome ASC" );
$guilda_nome = $guilda_nome->fetch(PDO::FETCH_ASSOC);

$sql_nave = "SELECT `ships`.`name`, `ships`.`base_id`, `ships`.`image`, sum(CASE WHEN `units`.`rarity` >= '".($fase+1)."' THEN 1 ELSE 0 END) AS total
	FROM `ships` 
	LEFT JOIN `units` ON `units`.`base_id` = `ships`.`base_id`
	WHERE `units`.`guilda` = '".$guilda."'
	GROUP BY `ships`.`base_id`  
	ORDER BY `total`, `ships`.`name` ASC";
$resultado_sql_nave = $PDO->prepare($sql_nave);
$resultado_sql_nave->execute();

while ($char = $resultado_sql_nave->fetch(PDO::FETCH_ASSOC)) {
	if ($char['total'] < 10) {
		$imagem_char = str_replace("//swgoh.gg/static/img/assets/", "http:".$site."/chars/", $char['image']);

		$p_sql = "SELECT `units`.`player`,`jogadores`.`discord` FROM `units`
			LEFT JOIN `jogadores` ON `jogadores`.`url` = `units`.`url` 
			WHERE `base_id` = '".$char['base_id']."' AND `guilda` = '".$guilda."' AND `rarity` >= '".($fase+1)."'
			ORDER BY `units`.`player` ASC";
		$resultado_p_sql = $PDO->prepare($p_sql);
		$resultado_p_sql->execute();
		$jogador = "";
		while ($player = $resultado_p_sql->fetch(PDO::FETCH_ASSOC)) {
			$jogador .= " - ".mb_convert_encoding($player['player'], "ISO-8859-1", "auto")."(".$player['discord'].")\n";
			//$jogador .= " - ".mb_convert_encoding($player['player'], "ISO-8859-1", "auto")."\n";
		}
		$jogador = $jogador == "" ? "Nenhum" : $jogador;
		
		$ter = "**Território Superior**";
		for ($i = 0; $i < 6; $i++) {
			if (isset($territorio[$char['base_id']][0][$i])) { 
				$ter .= "\n- Esquadrão ".$territorio[$char['base_id']][0][$i];
			}
		}
		
		if ($ter != "**Território Superior**") {
			$cor = hexdec(randomHex());
			$mensagens[] = [
				'title' => "**".str_replace("'", "\'", $char['name'])."**",
				'description' => $char['total']." disponíveís".($char['total'] == 0 ? "\n\n**ATENÇÃO**: Impossível fechar os esquadrões indicados abaixo por esta nave." :""),
				//'url' => 'https://example.com',
				'color' => $cor,
				'timestamp' => (new DateTime())->format('c'),
				'author' => [
					'name' => mb_convert_encoding("Nave Rara na Guilda", "ISO-8859-1", "auto"),
					//'url' => "https://swgoh.gg/g/".$guilda_nome['link'],
					'icon_url' => $imagem_char
				],
				'thumbnail' => [
					'url' => $imagem_char
				],
				'fields' => [
					[
						'name' => 'Jogadores',
						'value' => $jogador,
						'inline' => true
					],
					[
						'name' => 'Esquadrões',
						'value' => ($ter != "**Território Superior**" ? $ter : ""),
						'inline' => true
					]
				]
			];
		}
	}
}

$sql = "SELECT `characters`.`name`, `characters`.`base_id`, `characters`.`image`, sum(CASE WHEN `units`.`rarity` >= '".($fase+1)."' THEN 1 ELSE 0 END) AS total
	FROM `characters` 
	LEFT JOIN `chars` ON `chars`.`nome` = `characters`.`name` 
	LEFT JOIN `units` ON `units`.`base_id` = `characters`.`base_id`
	WHERE `chars`.`".$lado."` = '1' AND `units`.`guilda` = '".$guilda."'
	GROUP BY `characters`.`base_id`  
	ORDER BY `total`, `characters`.`name` ASC";
$resultado_sql = $PDO->prepare($sql);
$resultado_sql->execute();

while ($char = $resultado_sql->fetch(PDO::FETCH_ASSOC)) {
	if ($char['total'] < 10) {
		$imagem_char = str_replace("//swgoh.gg/static/img/assets/", "http:".$site."/chars/", $char['image']);

		$p_sql = "SELECT `units`.`player`,`jogadores`.`discord` FROM `units`
			LEFT JOIN `jogadores` ON `jogadores`.`url` = `units`.`url` 
			WHERE `base_id` = '".$char['base_id']."' AND `guilda` = '".$guilda."' AND `rarity` >= '".($fase+1)."'
			ORDER BY `units`.`player` ASC";
		$resultado_p_sql = $PDO->prepare($p_sql);
		$resultado_p_sql->execute();
		$jogador = "";
		while ($player = $resultado_p_sql->fetch(PDO::FETCH_ASSOC)) {
			$jogador .= " - ".mb_convert_encoding($player['player'], "ISO-8859-1", "auto")."(".$player['discord'].")\n";
			//$jogador .= " - ".mb_convert_encoding($player['player'], "ISO-8859-1", "auto")."\n";
		}
		$jogador = $jogador == "" ? "Nenhum" : $jogador;
		
		$ter_a = "**Território Superior/Central**";
		$ter_b = "**Território Inferior**";
		for ($i = 0; $i < 6; $i++) {
			if (isset($territorio[$char['base_id']][1][$i])) { 
				$ter_a .= "\n- Pelotão ".$territorio[$char['base_id']][1][$i];
			}
			if (isset($territorio[$char['base_id']][2][$i])) { 
				$ter_b .= "\n- Pelotão ".$territorio[$char['base_id']][2][$i];
			}
		}
		
		if ($ter_a != "**Território Superior/Central**" OR $ter_b != "**Território Inferior**") {
			$cor = hexdec(randomHex());
			$mensagens[] = [
				'title' => "**".str_replace("'", "\'", $char['name'])."**",
				'description' => $char['total']." disponíveís".($char['total'] == 0 ? "\n\n**ATENÇÃO**: Impossível fechar os pelotões indicados abaixo por este char." :""),
				//'url' => 'https://example.com',
				'color' => $cor,
				'timestamp' => (new DateTime())->format('c'),
				'author' => [
					'name' => mb_convert_encoding("Personagem Raro na Guilda", "ISO-8859-1", "auto"),
					//'url' => "https://swgoh.gg/g/".$guilda_nome['link'],
					'icon_url' => $imagem_char
				],
				'thumbnail' => [
					'url' => $imagem_char
				],
				'fields' => [
					[
						'name' => 'Jogadores',
						'value' => $jogador,
						'inline' => true
					],
					[
						'name' => 'Pelotões',
						'value' => ($ter_a != "**Território Superior/Central**" ? $ter_a."\n\n" : ""). ($ter_b != "**Território Inferior**" ? $ter_b : ""),
						'inline' => true
					]
				]
			];
		}
	}
}

if ($territorio) {
	// echo "<pre>"; var_dump ($territorio); echo "</pre>";
	// echo "<pre>"; print_r ($mensagens); echo "</pre>";
	
	
	if (isset($_POST['teste'])) {
		$url = "https://discordapp.com/api/webhooks/464088057017139218/6gw21sAIs8iE2UcC0zoR0bLAGHXus0wcf94XaFOh0Ia8cxB624azkqsSi5ndb-T2iqw5"; //Outro Servidor
		//$url = "https://discordapp.com/api/webhooks/468759356939436039/mamzzYK7CXmPuBWkoiL5GukURvxpEbXk1I9Ngc2RnGr_wNgSzgMmm9SWQsedxfHTyjcX"; //Servidor Luan
	} else $url = $guilda_nome['webhook_tb'];
	$image = 'http://kyber.arcomclube.com.br/images/trainer-2.jpg';
	$data = jsonEncodeArray([
		// These 2 should usually be left out as it will overwrite whatever your users have set
		'username' => 'Alerta TB',
		'avatar_url' => $image,
		'content' => "**Batalha por Território** - Fase ".$fase,
		'embeds' => $mensagens
	]);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data)
	]);
	curl_exec($ch);
}


	/*
		$mensagens = [
			[
				'title' => "Fase ".$fase,
				'description' => 'Orientações',
				//'url' => 'https://example.com',
				'color' => 0xF2D525,
				'timestamp' => (new DateTime())->format('c'),
				'author' => [
					'name' => mb_convert_encoding($guilda_nome['nome'], "ISO-8859-1", "auto"),
					'url' => "https://swgoh.gg/g/".$guilda_nome['link'],
					'icon_url' => $image
				],
				'video' => [
					'url' => 'https://github.com/mediaelement/mediaelement-files/blob/master/big_buck_bunny.mp4?raw=true'
				],
				'thumbnail' => [
					'url' => $image
				],
				'footer' => [
					'text' => 'Footer Text',
					'icon_url' => $image
				],
				'image' => [
					'url' => $image
				],
				'fields' => [
					[
						'name' => 'My First Field Name',
						'value' => 'My First Field Value',
						'inline' => true
					],
					[
						'name' => 'My Second Field Name',
						'value' => 'My Second Field Value',
						'inline' => true
					]
				]
			]
		];
	*/
?>
					</p>
				</div>
			</div>
		</div>
	</div>