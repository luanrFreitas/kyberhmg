<?php
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
$op = $_POST['op'];

$sql = "SELECT DISTINCT membros.id, membros.nome, membros.idguilda, membros.telegram, membros.link, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.idguilda 	= ".$gd."
	ORDER BY membros.idguilda, membros.nome
	";

$result = $PDO->query( $sql );

$x = 0;

echo "
	<form action='' method='post'>
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
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000' colspan='2'>
				<br><img src='includes/".str_replace(' ', '_', $membro['guilda'])."_m.jpg'><br><br>
			</td>
		</tr>

		<tr style='border-bottom: 1px solid #ddd;'>
			<td width='200px'><b>Membro</b></td>
			<td width='80%'><b>Nome no Telegram</b></td>
			</td>
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
	<tr style='border-bottom: 1px solid #ddd;'>
		<td>".$x." - ".$membro['nome']." (<a href='https://swgoh.gg/u/".$membro['link']."' target='_blank'>".$membro['link']."</a>)</td>
		<td><input type='text' name='".$membro_nome."' value='".$membro['telegram']."'>
		</td>
	</tr>";
}
echo "
	<tr style='border-bottom: 1px solid #ddd;'>
		<td width='200px'><input type='hidden' name='op' value='salvar'></td>
		<td width='90%'><input type='Submit' value='Salvar'></td>
		</td>
	</tr>
</table>
</form>";
?>