<?php
$op = $_POST['op'];

$result_g = $PDO->query( "UPDATE `membros` SET idguilda = '0'" );

/*
=========================
CAPTURA DE DADOS DO C3PO
=========================
*/

// url do C3P0
$url = file_get_contents("http://brazilguild.appspot.com/reports/members/");

// separando as guildas
$guildas = explode("<h4 id=\"SemGuild\">", $url);
$guildas = explode("<h5 class=\"logo\" id=\"", $guildas[0]);

for ($i = 1; $i <= count($guildas)-1; $i++) {

	// nome da guilda
	$guilda = explode("\"> <i class=\"", $guildas[$i]);
	$nome_guilda = (strlen($guilda[0]) > 6) ? str_replace("Brazil", "Brazil ", $guilda[0]) : $guilda[0] ;
	
	// encontra dados das guildas cadastradas na base de dados
	$sql_g = "SELECT DISTINCT id FROM guildas WHERE nome = '".$nome_guilda."'";
	$result_g = $PDO->query( $sql_g );
	$idguilda = $result_g->fetch( PDO::FETCH_ASSOC );
	//print $idguilda['id']." <===";

	// localizando os membros de cada guilda
	$dados = explode("<td class=\"mdl-data-table__cell--non-numeric\">", $guilda[1]);

	// localizando os dados dos membros encontrados
	for ($o = 1; $o <= count($dados)-1; $o++) {
		// 1² parte (id, nome, swgoh)
		if($o % 2 == 1) {
			$dado1 = explode("</b>", $dados[$o]);
			$dado = explode("<b>", $dado1[0]);
			// id
			$id = str_replace("<input type=\"hidden\" name=\"id\" value=\"", "", $dado[0]);
			$id = str_replace("\">", "", $id);
			$id = str_replace("\n", "", $id);
			$id = preg_replace('/\s+/', '', $id);
			// nome
			$nome = str_replace("\n", "", $dado[1]);
			$nome = trim(preg_replace('/\t+/', '', $nome));
			$nome = str_replace("<b>", "", $nome);
			// swgoh
			$swgoh = str_replace("<a href=\"https://swgoh.gg/u", "", $dado1[1]);
			$swgoh = str_replace("https://swgoh.gg/u", "", $swgoh);
			$swgoh = str_replace("https://swgoh.gg/", "", $swgoh);
			$swgoh = str_replace("\" target=\"_blank\">swgoh.gg</a>", "", $swgoh);
			$swgoh = str_replace("/", "", $swgoh);
			$swgoh = str_replace("/", "", $swgoh);
			$swgoh = str_replace("<i class=\"material-icons officer\">stars<i><td>", "", $swgoh);
			$swgoh = str_replace("<td>", "", $swgoh);
			$swgoh = str_replace("\n", "", $swgoh);
			$swgoh = trim(preg_replace('/\t+/', '', $swgoh));
			$o++;
		}
		
		// 2ª parte (id no telegram)
		if($o % 2 == 0) {
			$dado2 = explode("<a target=\"_blank\" href=\"https://telegram.me/", $dados[$o]);
			// telegram
			if ($dado2[1]) {
			} else {
				$dado2 = explode("</td>", $dados[$o]);
			}
			$telegram = str_replace("\n", "", $dado2[0]);
			$telegram = trim(preg_replace('/\t+/', '', $telegram));
			$telegram = explode("<small>", $telegram);
			$telegram = $telegram[0];	
		}
		
		// 3ª parte (dados swgoh)
		//$url2 = file_get_contents("https://swgoh.gg/u/".$swgoh."/");
		//preg_match_all("/Ally Code <strong class=\"pull-right\">(.*)<\/strong></p>/", $url2, $ally);
		//print_r($ally);
		/*
		preg_match_all("/Collection Score</span>
<h5 class=\"m-y-0\">(.*)<\/h5>/", $url2, $collection);
		preg_match_all("/Characters
<h5 class=\"m-y-0\">(.*)<\/h5>/", $url2, $charteres);
		preg_match_all("/Level
<h5 class=\"m-y-0\">(.*)<\/h5>/", $url2, $level);
		preg_match_all("/Arena Rank
<h5 class=\"m-y-0\">(.*)<\/h5>/", $url2, $arena);
*/
		
		// INSERÇÃO ou ATUALIZAÇÃO dos dados
		
		// verifica se o membros já existe na base de dados
		$atual = $PDO->query( "SELECT COUNT(*) AS total FROM membros WHERE idtelegram = '".$id."'" );
		$num_atual = $atual->fetch(PDO::FETCH_ASSOC);

		if ($num_atual[total] > 0) {
			// atualiza os dados caso o membro já existir na base de dados
			// allycode 	= \"".$ally[1][1]."\", 
			$sql = "UPDATE `membros` SET 
				link 		= \"".$swgoh."\", 
				idguilda 	= \"".$idguilda['id']."\", 
				
				telegram 	= \"".$telegram."\",
				nome 		= \"".$nome."\"
				WHERE 
				idtelegram 	= \"".$id."\"
				";
			$acao = "Atualizado";
		} else {
			// insere os dados caso o membro não existir na base de dados
			$sql = "INSERT INTO `membros` VALUES (NULL, '".$nome."', '', '', '', '', '', '', '".$swgoh."', '".$idguilda['id']."', '".$telegram."', '".$id."', '', '', '')";
			$acao = "<b>Incluído</b>";
		}

		$x = $x + 1;
		// executa sql
		$result = $PDO->query( $sql );
		
	}
}
/*
=========================
 FIM DA CAPTURA DE DADOS
=========================
*/

/*
=========================
EXIBINDO LISTA DE MEMBROS
=========================
*/

$sql = "SELECT DISTINCT membros.*, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
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
		<table width='100%'>";
	}
	if ($membro['idguilda'] <> $guilda) {
		$x = 0;
		$guilda = $membro['idguilda'];
		echo "
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000; font-weight: bold; color: #FFF;font-size: larger;' colspan='5'>
				<br><b>".$membro['guilda']."<b><br><br>
			</td>
		</tr>

		<tr style='border-bottom: 1px solid #ddd;'>
			<td style='width:250px'><b>Membro</b></td>
			<td style='width:200px'><b>Link SWGOH</b></td>
			<td style='width:120px'><b>Ally Code</b></td>
			<td style='width:250px'><b>Nome no Telegram</b></td>
			<td></td>
		</tr>
		";
	}
	$membro_nome = "membro_".$membro['id'];
	
	if ($op == 'salvar'){
		$membro['telegram'] = $_POST[$membro_nome];
		$sql2 = "UPDATE `membros` SET 
			telegram	= '".$_POST[$membro_nome]."' 
			WHERE id 	= '".$membro['id']."' 
			";
		$acao = "Atualizado";

		$PDO->query( $sql2 );
	}
	
	$x = $x + 1;
	echo "
	<form action='' method='post'>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x." - ".$membro['nome']."</td>
		<td><a href='https://swgoh.gg/u/".$membro['link']."' target='_blank'>".$membro['link']."</a></td>
		<td>".$membro['allycode']."</td>
		<td>".$membro['telegram']."</td>
		<td>
			<!--
			<input type='hidden' name='op' value='editar'>
			<input type='hidden' name='idmembro' value='".$membro['id']."'>
			<input type='Submit' value='Editar'>
			-->
		</td>
	</tr>
	</form>";
}
echo "
</table>
";
/*
=========================
 FIM DA LISTA DE MEMBROS
=========================
*/

?>