<?PHP
header('Content-Type: text/html; charset=utf-8');
function jsonEncodeArray( $array ){
    array_walk_recursive( $array, function(&$item) { 
       $item = utf8_encode( $item ); 
    });
    return json_encode( $array );
}
/*
$curl = curl_init("https://discordapp.com/api/webhooks/464088057017139218/6gw21sAIs8iE2UcC0zoR0bLAGHXus0wcf94XaFOh0Ia8cxB624azkqsSi5ndb-T2iqw5");
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, jsonEncodeArray(array("content" => "test", "username" => "BotTeste Terrtório")));
print jsonEncodeArray(array("content" => "test", "username" => "BotTeste Terrtório"));

echo curl_exec($curl);
*/

$url = "https://discordapp.com/api/webhooks/464088057017139218/6gw21sAIs8iE2UcC0zoR0bLAGHXus0wcf94XaFOh0Ia8cxB624azkqsSi5ndb-T2iqw5";
$image = 'http://kyber.arcomclube.com.br/images/trainer-2.jpg';
$data = jsonEncodeArray([
	// These 2 should usually be left out
	// as it will overwrite whatever your
	// users have set
	// 'username' => 'Test WebHook',
	// 'avatar_url' => $image,
	'content' => 'Batalha por Território',
	'embeds' => [
		[
			'title' => 'Território 1',
			'description' => 'Orientações',
			//'url' => 'https://example.com',
			'color' => 0xF2D525,
			'timestamp' => (new DateTime())->format('c'),
			'author' => [
				'name' => 'Kyber - Manto da Força',
				'url' => 'http://kyber.arcomclube.com.br',
				'icon_url' => $image
			],
			'video' => [
				'url' => 'https://github.com/mediaelement/mediaelement-files/blob/master/big_buck_bunny.mp4?raw=true'
			],
			'thumbnail' => [
				'url' => $image
			],
			/*
			'footer' => [
				'text' => 'Footer Text',
				'icon_url' => $image
			],
			*/
			/*
			'image' => [
				'url' => $image
			],
			*/
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
	]
]);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Content-Length: ' . strlen($data)
]);
curl_exec($ch);
?>