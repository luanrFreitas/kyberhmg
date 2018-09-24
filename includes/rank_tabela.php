<?php 
// =============================
// Tabela com dados gerais e
// Dados para visualização individual
// =============================

$tabela = "
<div class=\"colorlib-event\">
	<div class=\"container\">
		<div class=\"row\">
			<div class=\"col-md-8 col-md-offset-2 text-center colorlib-heading animate-box\" style=\"font-size: 13px; overflow-x: auto;\">
			<h2>Times de Importantes</h2>
			<TABLE style='border-collapse: collapse;' class=\"table table-hover table-striped table-fixed\">";
for ($i = 0; $i <= count($rank)-1; $i++) {
	if ( $i == 0 ) {
		$tabela .= "<TR><TD COLSPAN='2' style='padding: 1px;'><BR><BR><B>Players</B></TD>";
		for ($o = 0; $o <= count($times)-1; $o++) {
			$tit_time .= "<TD style='transform: rotate(-90deg); padding: 0px;' height='110'><BR><BR><CENTER><B>".$times[$o]['nome']."</B></CENTER></TD>";
		}
		$tabela .= $tit_time."<TD style='padding: 0px;'><BR><BR><B>FINAL</B></TD></TR>";
	} else {
		if ($rank[$i]['player'] === "lucas") $rank[$i]['player'] = "LucasCattanio";
		$linha[$i] = "<TR>
			<TD style='padding: 1px;'>".$rank[$i]['player']."</TD>
			<TD style='padding: 1px;'><A HREF='http:".$site."/?pg=rank&player=".$rank[$i]['player']."'><IMG SRC='http:".$site."/images/view.ico'></A></TD>";
		$peso = 0;
		for ($o = 0; $o <= count($times)-1; $o++) {
			$tit_chars = "";
			$k = 0;
			for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) {
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
					$key = (array_keys($times[$o]['chars'][$u]));
					if ($key[$a]."" == "zeta") {
					} else {
						$k++;
						$czeta = "";
						for ($z = 0; $z < count($times[$o]['chars'][$u][$a]['zeta']); $z++) {
							$czeta .= "<img src='http:".$site."/chars/tex.skill_zeta.png' alt=\"".$times[$o]['chars'][$u][$a]['zeta'][$z]."\" title=\"".$times[$o]['chars'][$u][$a]['zeta'][$z]."\" height='12' width='12'>";
							
						}
						$imagem = str_replace("//swgoh.gg/static/img/assets/", "http:".$site."/chars/", $char[$times[$o]['chars'][$u][$a]['nome']]['image']);
					
						$tit_chars .= "
						<TD style='padding: 1px;'>
							<CENTER>
								".$czeta."<br>
								<img src='".$imagem."' alt=\"".$char[$times[$o]['chars'][$u][$a]['nome']]['name']."\" title=\"".$char[$times[$o]['chars'][$u][$a]['nome']]['name']."\" height='30' width='30'>
							</CENTER>
						</TD>";
					}
				}
			}
			
			$peso += $times[$o]['peso'];
			$time[$o] = 0;
			$time_final = 0;
			$item = "";
			for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) {
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
					$c_atual = $times[$o]['chars'][$u][$a];
					$teste = "";
					if ($rank[$i][$c_atual['nome']]['base_id'] > 0) {
						$czeta = "";
						$reducao = 0;
						for ($z = 0; $z < count($c_atual['zeta']); $z++) {
							$atual = $PDO->query( "SELECT COUNT(*) AS total FROM `zetas` 
									LEFT JOIN `abilities` ON `abilities`.`base_id` = `zetas`.`zeta` 
									WHERE `zetas`.`player` = '".str_replace("'", "\'", $rank[$i]['player'])."' 
									AND `abilities`.`name` = '".str_replace("'", "\'", $times[$o]['chars'][$u][$a]['zeta'][$z])."'" );
							
							$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
							if ($num_atual[total] > 0) {
								$czeta .= "<B>(z)</B>"; 
							} else {
								if ($rank[$i][$c_atual['nome']]['jafoi'] == 0) {
									$reducao++;
									if ($z == (count($c_atual['zeta'])-1)) $rank[$i][$c_atual['nome']]['jafoi'] = 1;
								}
							}
						}
						if ($reducao > 0)$rank[$i][$c_atual['nome']]['base_id'] = $rank[$i][$c_atual['nome']]['base_id']*((100-$reducao*25)/100);
					}
					if (isset($c_atual['unico'])) {
						if ($c_atual['unico']['extra'] == "principal") {
							if ($rank[$i][$c_atual['nome']]['rarity'] < 7) $time_final = 1;
						}
						if (isset($c_atual['unico']['campo'])) {
							$rank[$i][$c_atual['nome']]['base_id'] = $rank[$i][$c_atual['nome']][$c_atual['unico']['campo']] == $c_atual['unico']['max'] ? 100 : 0;
						}
					}
				}
				
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
					if ($rank[$i][$times[$o]['chars'][$u][$a]['nome']]['base_id'] > 0) {
						$c_atual = $times[$o]['chars'][$u][$a]['nome'];
						if ($rank[$i][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank[$i][$times[$o]['chars'][$u][1]['nome']]['base_id']) {
							if ($rank[$i][$times[$o]['chars'][$u][0]['nome']]['base_id'] >= $rank[$i][$times[$o]['chars'][$u][2]['nome']]['base_id']) 
								$ver = 0; else $ver = 2;
						} else {
							if ($rank[$i][$times[$o]['chars'][$u][1]['nome']]['base_id'] >= $rank[$i][$times[$o]['chars'][$u][2]['nome']]['base_id']) 
								$ver = 1; else $ver = 2;
						}
						
						if ($a == $ver) {
							$item .= "<TD ".($rank[$i][$c_atual]['base_id'] > 90 ? "  bgcolor='#c2f0c2'" : "").($rank[$i][$c_atual]['base_id'] < 40 ? "  bgcolor='#ff9999'" : "").">";
							$item .= "<CENTER>";
							//$item .= "".$rank[$i][$c_atual]['gear_level']."G <BR> ".$rank[$i][$c_atual]['level']." lvl <BR> ".$rank[$i][$c_atual]['rarity']."* ".$czeta."<BR> ";
							$item .= "<B>".formatMoney($rank[$i][$c_atual]['base_id'], 0)."</B>";
							$item .= "</CENTER>";
							$item .= "</TD>";
							$time[$o]  += $rank[$i][$c_atual]['base_id'];
						} else $item .= "<TD></TD>";
					} else {
						$item .= "<TD></TD>";
					}
				}
			}
			$time[$o] = $time_final > 0 ? 0 : $time[$o];
			$linha[$i] .= "<TD style='padding: 0px;' ".(($time[$o]/5) > 90 ? "  bgcolor='#96db96'" : "").(($time[$o]/5) < 40 ? "  bgcolor='#ff9999'" : "")."><CENTER>".formatMoney(($time[$o]/5), 1)."</CENTER></TD>";
			$final[$i] += ($time[$o] / 5) * $times[$o]['peso'];
			
			if ($_GET['player'] == $rank[$i]['player']) { 
				$dado_player .= "
				<div class=\"col-md-4 animate-box\">
					<div class=\"event-entry\">
						<h2>".$times[$o]['nome']."</h2>
						<p>
						<TABLE style='border-collapse: collapse;' align='center'>
							<TR>".$tit_chars."</TR>
							<TR>".$item."</TR>
							<TR align='center'".(($time[$o]/5) > 90 ? " bgcolor='#96db96'" : "").(($time[$o]/5) < 40 ? " bgcolor='#ff9999'" : "")."><TD COLSPAN='".$k."'><B>Total:</B> ".formatMoney(($time[$o]/5), 2)."<TD></TR>
						</TABLE>
						</p>
					</div>
				</div>";
			}

		}
		$final[$i] = $final[$i]/$peso;
		$linha[$i] .= "<TD style='padding: 0px;".($final[$i] > 90 ? " color:green;" : "").($final[$i] < 40 ? " color:red;" : "")."'><CENTER><B>".formatMoney($final[$i], 1)."</B></CENTER></TD></TR>";
		$tabela .= $linha[$i];
	}
}
$tabela .= "
			</TABLE>
			</div>
		</div>
	</div>
</div>";


if (!is_null($_GET['player'])) { ?>
	<div class="colorlib-event">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
					<h2><?=$_GET['player'];?></h2>
					<p>Dados individuais para o jogador selecionado.</p>
				</div>
			</div>
			<div class="row row-pb-sm">
				<?=$dado_player;?>
			</div>
		</div>
	</div>
<?php } 

print $tabela;

function formatMoney($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0,00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents > 0 ? $cents : 0), ',', ''); // format
      } else { // cents
        $money = number_format(round($number, $cents), ($cents == 0 ? 0 : $cents), ',', ''); // format
      } // integer or decimal
    } // value
    return $money;
  } // numeric
} // formatMoney
?>

