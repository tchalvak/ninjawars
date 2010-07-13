<?php
$private = false;
$alive   = false;

if ($error = init($private, $alive)) {
	display_error($error);
} else {

$player = new Player(get_char_id());
$freeResLevelLimit = 6;
$freeResKillLimit  = 25;
$lostTurns         = 10; // *** Default turns lost when the player has no kills.
$startingKills     = 0;
$userLevel         = 0;
$poisoned          = $player->hasStatus(POISONED);

if (isset($username)) {
	$startingKills     = $player->vo->kills;
	$userLevel         = $player->vo->level;
	$at_max_health     = ($player->vo->health >= (150 + (($userLevel - 1) * 25)));
	$player_health     = $player->vo->health;

	// *** A True or False as to whether resurrection will be free.
	$freeResurrection = ($userLevel < $freeResLevelLimit && $startingKills < $freeResKillLimit);
}	// End of username check.

display_page(
	'shrine.tpl' // *** Main Template ***
	, 'Healing Shrine' // *** Page Title ***
	, get_certain_vars(get_defined_vars(), array()) // *** Page Variables ***
	, array( // *** Page Options ***
		'quickstat' => 'player'
	)
); 
}
?>
