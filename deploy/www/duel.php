<?php
require_once(LIB_ROOT."specific/lib_player.php");

$private    = false;
$alive      = false;
$quickstat  = false;
$page_title = "Duel Log";

include SERVER_ROOT."interface/header.php";

$stats          = membership_and_combat_stats($sql);
$vicious_killer = $stats['vicious_killer'];
?>
<h1>Today's Duels</h1>

<div id='vicious-killer'>
    Current Fastest Killer: 
    <a id='vicious-killer-menu href='player.php?player=<?php echo out($vicious_killer); ?>'>
        <?php echo $vicious_killer; ?>
    </a>
</div>

<?php

$sql->QueryRow("SELECT * FROM dueling_log ORDER BY id DESC LIMIT 500");

echo "  <h3>Duel Log</h3>";

$duels = $sql->FetchAll();
if(empty($duels)){
    echo "<p>No duels for today yet.</p>";
}
echo "<ul id='duel-log'>";
//var_dump($duels);
foreach($duels as $duel){
  echo "<li>";
  echo render_player_link($duel['attacker'])." has dueled ".render_player_link($duel['defender'])." 
    and ".($duel['won']?'won':'lost')." for {$duel['killpoints']} killpoints on {$duel['date']}";
  echo "</li>";

}

echo "</ul>";


include SERVER_ROOT."interface/footer.php";
?>
