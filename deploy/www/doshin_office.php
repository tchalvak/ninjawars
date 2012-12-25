<?php
$alive      = true;
$private    = false;

if ($error = init($private, $alive)) {
	display_error($error);
} else {

require_once(LIB_ROOT.'control/lib_inventory.php');
$quickstat   = false;
$location    = 0;

$target   = in('target');
$target_id = get_char_id($target); // Will be the enemy to put the bounty on.
$command  = in('command');
$username = self_name();
$char_id  = self_char_id();
$amount   = intval(in('amount'));
$bribe    = intval(in('bribe'));
$bounty   = intval(in('bounty'));


$amount_in = $amount;

if ($bounty && $target_id) {
	$command = 'Offer Bounty';
}

$error = 0;
$success = false;

if ($command == 'Offer Bounty') {
	if (!$target_id) {
		$error = 1;
	} else { // Target existed.
		$target_bounty = getBounty($target_id);

		if ($target_bounty < 5000) {
			if ($amount > 0) {
				if (($target_bounty + $amount) > 5000) {
					$amount = (5000 - $target_bounty);
				}

				if (get_gold($char_id) >= $amount) {
					addBounty($target_id, $amount); // Add the bounty to the person being bountied upon.  How the hell did this break?

					subtract_gold($char_id, $amount);
					send_event($char_id, get_char_id($target), "$username has offered $amount gold in reward for your head!");
					
					$success = true;
					$quickstat = 'player';
				} else {
					$error = 2;
				}
			} else {
				$error = 3;
			}
		} else {
			$error = 4;
		}
	}
} else if ($command == 'Bribe') {
	if ($bribe <= get_gold($char_id) && $bribe > 0) {
		subtract_gold($char_id, $bribe);
		subtractBounty($char_id, ($bribe/2));
		$location = 1;

		$quickstat = 'player';
	} else if ($bribe < 0) { 
		// Was a bug, now the doshin beats you up!  Yay!
		if (get_gold($char_id) > 1000) { //  *** If they have more than 1000 gold, their bounty will be mostly removed by this event.
			$bountyGoesToNearlyZero = (getBounty($char_id) * .7);
			subtractBounty($char_id, $bountyGoesToNearlyZero);
		}

		subtractGold($username, floor(getGold($username) *.8));  //Takes away 80% of the players gold.

		$location = 2;

		$quickstat = 'player';
	} else {
		$error = 5;
	}
}

$myBounty = getBounty($char_id);


// Pulling the bounties.
DatabaseConnection::getInstance();
$result = DatabaseConnection::$pdo->query("SELECT player_id, uname, bounty, class_name AS class, level, clan_id, clan_name FROM players JOIN class ON class_id = _class_id LEFT JOIN clan_player ON player_id = _player_id LEFT JOIN clan ON clan_id = _clan_id WHERE bounty > 0 AND active = 1 and health > 0 ORDER BY bounty DESC");

$data = $result->fetchAll();

display_page(
	'doshin.tpl'
	, 'Doshin Office'
	, get_certain_vars(get_defined_vars(), array('data'))
	, array(
		'quickstat' => $quickstat
	)
);
}
?>
