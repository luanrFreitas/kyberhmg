<?php

$times[0]['nome'] = "Time RJT";
$times[0]['chars'][0][0] = "REYJEDITRAINING";
$times[0]['chars'][0]['zeta'][0] = "Inspirational Presence";
$times[0]['chars'][1][0] = "BB8";
$times[0]['chars'][1]['zeta'][0] = "Roll with the Punches";
$times[0]['chars'][2][0] = "R2D2_LEGENDARY";
$times[0]['chars'][2][1] = "VISASMARR";
$times[0]['chars'][3][0] = "RESISTANCETROOPER";
$times[0]['chars'][4][0] = "REY";
$times[0]['chars'][4][1] = "BARRISSOFFEE";
$times[0]['chars'][4][2] = "HERMITYODA";

$times[1]['nome'] = "Troopers";
$times[1]['chars'][0][0] = "VEERS";
$times[1]['chars'][0]['zeta'][0] = "Aggressive Tactician";
$times[1]['chars'][1][0] = "GRANDADMIRALTHRAWN";
$times[1]['chars'][2][0] = "SHORETROOPER";
$times[1]['chars'][3][0] = "COLONELSTARCK";
$times[1]['chars'][4][0] = "SNOWTROOPER";

$times[2]['nome'] = "Palp";
$times[2]['chars'][0][0] = "EMPERORPALPATINE";
$times[2]['chars'][0]['zeta'][0] = "Emperor of the Galactic Empire";
$times[2]['chars'][1][0] = "GRANDADMIRALTHRAWN";
$times[2]['chars'][2][0] = "DARTHNIHILUS";
$times[2]['chars'][3][0] = "DARTHSION";
$times[2]['chars'][3][1] = "GRANDMOFFTARKIN";
$times[2]['chars'][4][0] = "VADER";
$times[2]['chars'][4][1] = "SITHTROOPER";

$times[3]['nome'] = "Leia Spam";
$times[3]['chars'][0][0] = "ADMIRALACKBAR";
//$times[3]['chars'][0][1] = "NUTEGUNRAY";
$times[3]['chars'][1][0] = "PRINCESSLEIA";
$times[3]['chars'][2][0] = "GENERALKENOBI";
$times[3]['chars'][3][0] = "GRANDADMIRALTHRAWN";
$times[3]['chars'][4][0] = "EWOKELDER";

$times[4]['nome'] = "Phoenix";
$times[4]['chars'][0][0] = "HERASYNDULLAS3";
$times[4]['chars'][1][0] = "EZRABRIDGERS3";
$times[4]['chars'][2][0] = "SABINEWRENS3";
$times[4]['chars'][3][0] = "ZEBS3";
$times[4]['chars'][4][0] = "KANANJARRUSS3";

$times[5]['nome'] = "Chexmix";
$times[5]['chars'][0][0] = "COMMANDERLUKESKYWALKER";
$times[5]['chars'][0]['zeta'][0] = "It Binds All Things";
$times[5]['chars'][1][0] = "HANSOLO";
$times[5]['chars'][1]['zeta'][0] = "Shoots First";
$times[5]['chars'][2][0] = "DEATHTROOPER";
$times[5]['chars'][3][0] = "CHIRRUTIMWE";
$times[5]['chars'][3][1] = "CT7567";
$times[5]['chars'][4][0] = "PAO";
$times[5]['chars'][4][1] = "ANAKINKNIGHT";
//$times[5]['chars'][4][2] = "POGGLETHELESSER";

$times[6]['nome'] = "NS";
$times[6]['chars'][0][0] = "ASAJVENTRESS";
$times[6]['chars'][0]['zeta'][0] = "Nightsister Swiftness";
$times[6]['chars'][0]['zeta'][1] = "Rampage";
$times[6]['chars'][1][0] = "DAKA";
$times[6]['chars'][2][0] = "NIGHTSISTERACOLYTE";
$times[6]['chars'][3][0] = "TALIA";
$times[6]['chars'][4][0] = "MOTHERTALZIN";
$times[6]['chars'][4][1] = "NIGHTSISTERINITIATE";

$i = 0;
$readPlayer = $PDO->query("SELECT DISTINCT `player`, `url` FROM `units` ORDER BY `player`");
foreach ($readPlayer as $player){
	$i++;
	$rank[$i]['player'] = $player['player'];
	
	$o = 0;
	$readUnit = $PDO->query("SELECT * FROM `units` WHERE `player` = '".$player['player']."' AND `url` = '".$player['url']."'");
	foreach ($readUnit as $unit){
		$o++;
		//$rank[$i][$unit['base_id']] = 5;
		$rank[$i][$unit['base_id']]['gear_level'] = $unit['gear_level'];
		$rank[$i][$unit['base_id']]['level'] = $unit['level'];
		$rank[$i][$unit['base_id']]['rarity'] = $unit['rarity'];
		$rank[$i][$unit['base_id']]['base_id'] = ($unit['gear_level']/12)+($unit['level']/85)+($unit['rarity']/7);
		$rank[$i][$unit['base_id']]['base_id'] = $rank[$i][$unit['base_id']]['base_id']*100/3;
		//(($unit['gear_level']/12)+($unit['level']/85)+($unit['rarity']/12))100/3;
	}
}

$i = 0;
$readBase = $PDO->query("SELECT DISTINCT `characters`.`name`, `characters`.`base_id`, `characters`.`image` FROM `units` INNER JOIN `characters` ON `units`.`base_id` = `characters`.`base_id` ORDER BY `characters`.`name`");
foreach ($readBase as $base_id){
	$char[$base_id['base_id']]['name'] 	= $base_id['name'];
	$char[$base_id['base_id']]['image'] = $base_id['image'];

	$chars[$i]['name'] 			= $base_id['name'];
	$chars[$i]['base_id'] 		= $base_id['base_id'];
	$chars[$i]['image'] 		= $base_id['image'];
	++$i;
}

print "<TABLE>";
for ($i = 0; $i <= count($rank); $i++) {
	if ( $i == 0 ) {
		print "<TR><TD ROWSPAN='2'>Players</TD>";
		for ($o = 0; $o <= count($times)-1; $o++) {
			$k = 0;
			for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) {
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
					$key = (array_keys($times[$o]['chars'][$u]));
					if ($key[$a]."" == "zeta") {
					} else {
						$k++;
						$czeta = "";
						for ($z = 0; $z < count($times[$o]['chars'][$u]['zeta']); $z++) {
							//$czeta .= $times[$o]['chars'][$u]['zeta'][$z];
							$czeta .= "<img src='//swgoh.gg/static/img/assets/tex.skill_zeta.png' alt='".$times[$o]['chars'][$u]['zeta'][$z]."' title='".$times[$o]['chars'][$u]['zeta'][$z]."' height='20' width='20'>";
							
						}
					
					
						$tit_chars .= "
						<TD>
							<CENTER>
								<img src='".$char[$times[$o]['chars'][$u][$a]]['image']."' alt='".$char[$times[$o]['chars'][$u][$a]]['name']."' title='".$char[$times[$o]['chars'][$u][$a]]['name']."' height='30' width='30'>
								".$czeta."
							</CENTER>
						</TD>";
					}
				}
			}
			$tit_time .= "<TD COLSPAN='".$k."'><CENTER>".$times[$o]['nome']."</CENTER></TD><TD ROWSPAN='2'>Total</TD>";
		}
		print $tit_time."<TD ROWSPAN='2'>FINAL</TD></TR><TR>".$tit_chars."</TR>";
	} else {
		$linha[$i] = "<TR><TD>".$rank[$i]['player']."</TD>";
		for ($o = 0; $o <= count($times)-1; $o++) {
			$time[$o] = 0;
			for ($u = 0; $u <= count($times[$o]['chars'])-1; $u++) {
				for ($a = 0; $a <= count($times[$o]['chars'][$u])-1; $a++) {
					$key = (array_keys($times[$o]['chars'][$u]));
					if ($key[$a]."" == "zeta") {
					} else {
						if ($rank[$i][$times[$o]['chars'][$u][$a]] > 0) {
							$czeta = "";
							for ($z = 0; $z < count($times[$o]['chars'][$u]['zeta']); $z++) {
								//$czeta .= "<img src='//swgoh.gg/static/img/assets/tex.skill_zeta.png' alt='".$times[$o]['chars'][$u]['zeta'][$z]."' title='".$times[$o]['chars'][$u]['zeta'][$z]."' height='20' width='20'>";
								//$czeta .= "(z)";
								
								$atual = $PDO->query( "SELECT COUNT(*) AS total FROM zetas WHERE player = '".$rank[$i]['player']."' AND zeta = '".$times[$o]['chars'][$u]['zeta'][$z]."'" );
								$num_atual = $atual->fetch(PDO::FETCH_ASSOC);
								if ($num_atual[total] > 0) $czeta .= "<B>(z)</B>"; else $rank[$i][$times[$o]['chars'][$u][$a]]['base_id'] = $rank[$i][$times[$o]['chars'][$u][$a]]['base_id']-5;
							}
							if ($rank[$i][$times[$o]['chars'][$u][0]]['base_id'] > $rank[$i][$times[$o]['chars'][$u][1]]['base_id']) {
								if ($rank[$i][$times[$o]['chars'][$u][0]]['base_id'] > $rank[$i][$times[$o]['chars'][$u][2]]['base_id']) {
									$ver = 0;
								} else {
									$ver = 2;
								}
							} else {
								if ($rank[$i][$times[$o]['chars'][$u][1]]['base_id'] > $rank[$i][$times[$o]['chars'][$u][2]]['base_id']) {
									$ver = 1;
								} else {
									$ver = 2;
								}
							}
							
							if ($a == $ver) {
								$linha[$i] .= "<TD ".($rank[$i][$times[$o]['chars'][$u][$a]]['base_id'] > 90 ? "  bgcolor='#c2f0c2'" : "").($rank[$i][$times[$o]['chars'][$u][$a]]['base_id'] < 40 ? "  bgcolor='#ff9999'" : "").">";
								$linha[$i] .= "<CENTER>";
								$linha[$i] .= "".$rank[$i][$times[$o]['chars'][$u][$a]]['gear_level']."G <BR> ".$rank[$i][$times[$o]['chars'][$u][$a]]['level']." lvl <BR> ".$rank[$i][$times[$o]['chars'][$u][$a]]['rarity']."* ".$czeta."<BR> ";
								$linha[$i] .= "<B>".formatMoney($rank[$i][$times[$o]['chars'][$u][$a]]['base_id'], 2)."</B>";
								$linha[$i] .= "</CENTER>";
								$linha[$i] .= "</TD>";
								$time[$o]  += $rank[$i][$times[$o]['chars'][$u][$a]]['base_id'];
							} else $linha[$i] .= "<TD></TD>";
						} else {
							$linha[$i] .= "<TD></TD>";
						}
					}
				}
			}
			$linha[$i] .= "<TD ".(($time[$o]/5) > 90 ? "  bgcolor='#2eb82e'" : "").(($time[$o]/5) < 40 ? "  bgcolor='#ff3333'" : "")."><CENTER>".formatMoney($time[$o]/5, 2)."</CENTER></TD>";
			$final[$i] += ($time[$o]/5)/7;
		}
		$linha[$i] .= "<TD".($final[$i] > 90 ? " style='color:green;'" : "").($final[$i] < 40 ? " style='color:red;'" : "")."><CENTER><B>".formatMoney($final[$i], 2)."</B></CENTER></TD></TR>";
		print $linha[$i];
	}
}


/*
for ($i = 0; $i <= count($rank); $i++) {
	if ( $i == 0 ) {
		print "<TR><TD></TD>";
		for ($o = 0; $o <= count($chars)-1; $o++) {
			print "<TD><img src='".$chars[$o]['image']."' alt='".$chars[$o]['name']."' title='".$chars[$o]['name']."' height='30' width='30'></TD>";
		}
		print "</TR>";
	} else {
		print "<TR><TD>".$rank[$i]['player']."</TD>";
		for ($o = 0; $o <= count($chars)-1; $o++) {
			if ($rank[$i][$chars[$o]['base_id']] > 0)
			print "<TD".($rank[$i][$chars[$o]['base_id']]['base_id'] > 90 ? "  bgcolor='#00FF00'" : "").">".$rank[$i][$chars[$o]['base_id']]['gear_level']."G / ".$rank[$i][$chars[$o]['base_id']]['level']." lvl / ".$rank[$i][$chars[$o]['base_id']]['rarity']."* / ".$rank[$i][$chars[$o]['base_id']]['base_id']."</TD>";
			else print "<TD></TD>";
		}
		print "</TR>";
	}
}
*/

print "</TABLE>";

/*
print "<pre>";
print_r ($char);
print "</pre>";
*/


function formatMoney($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0.00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
      } // integer or decimal
    } // value
    return $money;
  } // numeric
} // formatMoney
?>