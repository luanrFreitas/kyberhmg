<?php
$op = $_POST['op'];
if (is_null($_GET['gd'])) $gd = 1; else $gd = $_GET['gd'];
if (is_null($_GET['dt'])) $dt = date("Y-m-d"); else $dt = $_GET['dt'];

if(strtotime($dt) > strtotime("-1 days")) { $dt = date("Y-m-d", strtotime("-1 days")); }

$sql = "SELECT nome FROM guildas WHERE id = '".$gd."'";
$result = $PDO->query( $sql );
$nome_guilda = $result->fetch( PDO::FETCH_ASSOC );
$nome_guilda = str_replace(' ', '', $nome_guilda['nome']);
//print $nome_guilda;

$url = "http://brazilguild.appspot.com/reports/dailycoins/?start=".$dt."%2019%3a00%3a00&guild=".$nome_guilda;
$url = file_get_contents($url);

$enviados = explode("visibility</i></a>", $url);

for ($i = 1; $i <= count($enviados)-1; $i++) {

	$membro = explode("</b>", $enviados[$i]);
	$membro = str_replace('</small>', '', $membro[0]);
	$membro = str_replace('<b>', '', $membro);
	$membro = str_replace("\n", "", $membro);
	$membro = trim(preg_replace('/\t+/', '', $membro));

	$sql_m = "SELECT id FROM membros WHERE nome = '".$membro."'";
	$result_m = $PDO->query( $sql_m );
	$id_membro = $result_m->fetch( PDO::FETCH_ASSOC );
	$id_membro = $id_membro['id'];
	
	$sql = "UPDATE `print` SET print_ok = '1' WHERE idmembro = '".$id_membro."' AND data = '".$dt."'";
	$acao = "Atualizado";
	$result = $PDO->query( $sql );

	print $i." - ".$membro." - ".$acao."<br>";
}
echo "
</table>
";

header("Location: ".$site."?pg=print&gd=".$gd."&dt=".$dt);
exit;
?>