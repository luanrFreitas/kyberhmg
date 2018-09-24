<?php
$op = $_POST['op'];

$sql = "SELECT DISTINCT membros.*, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	ORDER BY membros.idguilda, membros.nome
	";

$result = $PDO->query( $sql );

$x = 0;

if ($op == "update") {


	$op == "";
}

if ($op == "editar") {
$sql_ed = "SELECT DISTINCT membros.*, guildas.nome AS guilda
	FROM membros
	INNER JOIN guildas ON membros.idguilda = guildas.id
	WHERE membros.id = '".$_POST['idmembro']."'
	ORDER BY membros.idguilda, membros.nome
	";

$result_ed = $PDO->query( $sql_ed );
$membro_ed = $result_ed->fetch( PDO::FETCH_ASSOC );

echo "<br>
	<table style='margin-left:auto; margin-right:auto;' width='600'>
		<tr>
			<td style='text-align: center; background-color:#000' colspan='5'><b>Editar</b></td>
		</tr>
	<form action='' method='post'>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right;' width='200'><b>Nome no Telegram</b></td>
		<td><input type='text' name='telegram' value='".$membro_ed['telegram']."' style='width: 400px'></td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right;'><b>Link no SWGOH</b></td>
		<td>https://swgoh.gg/u/".$membro_ed['link']."</td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right;'><b>Guilda</b></td>
		<td>
			<select name='idguilda' style='width: 400px'>
				<option value=''>--</option>";
				$sql_char = "SELECT * FROM guildas ORDER BY id";
				$chars = $PDO->query( $sql_char );
				while ($char = $chars->fetch( PDO::FETCH_ASSOC )) {
					echo "<option value='".$char['id']."' ".(($membro_ed['idguilda'] == $char['id']) ? "selected" : "").">".$char['nome']."</option>";
				}
echo "		</select>
		</td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: center;' colspan='2'>
			<input type='hidden' name='op' value='update'>
			<input type='hidden' name='idmembro' value='".$membro_ed['id']."'>
			<input type='Submit' value='Salvar'>
		</td>
	</tr>
	</form>
	</table><br><br>";
} else {
echo "<br>
	<table style='margin-left:auto; margin-right:auto;' width='600'>
	<form action='' method='post'>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right;' width='200'><b>Nome no Telegram</b></td>
		<td><input type='text' name='telegram' value='' style='width: 400px'></td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right;'><b>Link no SWGOH</b></td>
		<td>https://swgoh.gg/u/<input type='text' name='link' value='' style='width: 250px'></td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: right;'><b>Guilda</b></td>
		<td>
			<select name='idguilda' style='width: 400px'>
				<option value=''>--</option>";
				$sql_char = "SELECT * FROM guildas ORDER BY id";
				$chars = $PDO->query( $sql_char );
				while ($char = $chars->fetch( PDO::FETCH_ASSOC )) {
					echo "<option value='".$char['id']."'>".$char['nome']."</option>";
				}
echo "		</select>
		</td>
	</tr>
	<tr style='border-bottom: 1px solid #ddd;'>
		<td style='text-align: center;' colspan='2'>
			<input type='hidden' name='op' value='update'>
			<input type='hidden' name='idmembro' value='".$membro['id']."'>
			<input type='Submit' value='Salvar'>
		</td>
	</tr>
	</form>
	</table><br><br>";
}

echo "
	<table width='100%'>";
while ($membro = $result->fetch( PDO::FETCH_ASSOC )) {
	if ($membro['idguilda'] <> $guilda and $guilda != 0) {
		echo "
		</table>
		<br><br>
		<table width='100%'>";
	}
	if ($membro['idguilda'] <> $guilda) {
		$x = 0;
		$guilda = $membro['idguilda'];
		echo "
		<tr>
			<td style='border-bottom: 1px solid #ddd; text-align: center; background-color:#000' colspan='5'>
				<br><img src='".str_replace(' ', '_', $membro['guilda'])."_m.jpg'><br><br>
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
		<td>".$membro['link']."</td>
		<td>".$membro['allycode']."</td>
		<td>".$membro['telegram']."</td>
		<td><input type='hidden' name='op' value='editar'><input type='hidden' name='idmembro' value='".$membro['id']."'><input type='Submit' value='Editar'></td>
	</tr>
	</form>";
}
echo "
</table>
";
?>