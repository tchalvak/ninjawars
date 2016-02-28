<?php
use NinjaWars\core\data\DatabaseConnection;

// ********************* STATUS DEFINES MOVED TO STATUS_DEFINES.PHP FILE ******** //


// ********************* CLASS FUNCTIONS MOVED TO PLAYER OBJECT ******* //


// ************************************
// ********* HEALTH FUNCTIONS *********
// ************************************

function setHealth($who, $new_health) {
	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("UPDATE players SET health = :health WHERE player_id = :user");
	$statement->bindValue(':health', $new_health);
	$statement->bindValue(':user', $who);
	$statement->execute();

	return $new_health;
}

function getHealth($who) {
	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("SELECT health FROM players WHERE player_id = :user");
	$statement->bindValue(':user', $who);
	$statement->execute();

	return $statement->fetchColumn();
}

function changeHealth($who, $amount) {
	$amount = (int)$amount;

	if (abs($amount) > 0) {
		DatabaseConnection::getInstance();
		$statement = DatabaseConnection::$pdo->prepare("UPDATE players SET health = health + ".
		   "CASE WHEN health + :amount < 0 THEN health*(-1) ELSE :amount2 END ".
		   "WHERE player_id = :user");
		$statement->bindValue(':user', $who);
		$statement->bindValue(':amount', $amount);
		$statement->bindValue(':amount2', $amount);
		$statement->execute();
	}

	return getHealth($who);
}

function subtractHealth($who, $amount) {
	return changeHealth($who, ((-1)*$amount));
}

// ************************************
// ************************************

// ************************************
// ********** TURNS FUNCTIONS *********
// ************************************

// Deprecated.
function subtractTurns($who, $amount) {
	return change_turns($who, ((-1)*abs($amount)));
}

// Add or subtract from a players turns (zeroed-out).
function change_turns($char_id, $amount){
	$amount = (int) $amount;
	if($amount){ // Ignore zero
		// These PDO parameters must be split into amount1 and amount2 because otherwise PDO gets confused.  See github issue 147.
		query("UPDATE players set turns = (CASE WHEN turns + :amount < 0 THEN 0 ELSE turns + :amount2 END) where player_id = :char_id",
			array(':amount'=>array($amount, PDO::PARAM_INT), ':amount2'=>array($amount, PDO::PARAM_INT), ':char_id'=>$char_id));
	}
	return get_turns($char_id);
}

// Pull a character's turns.
function get_turns($char_id){
	return query_item("select turns from players where player_id = :char_id", array(':char_id'=>$char_id));
}

// ************************************
// ************************************

// ************************************
// ********** LEVEL FUNCTIONS *********
// ************************************

function getLevel($who) {
	DatabaseConnection::getInstance();

	$statement = DatabaseConnection::$pdo->prepare("SELECT level FROM players WHERE player_id = :player");
	$statement->bindValue(':player', $who);
	$statement->execute();
	return $statement->fetchColumn();
}

// ************************************
// ************************************


// ************************************
// ********* BOUNTY FUNCTIONS *********
// ************************************

function setBounty($who, $new_bounty) {
	$new_bounty = (int)$new_bounty;
	DatabaseConnection::getInstance();

	$statement = DatabaseConnection::$pdo->prepare("UPDATE players SET bounty = :bounty WHERE player_id = :player");
	$statement->bindValue(':bounty', $new_bounty);
	$statement->bindValue(':player', $who);
	$statement->execute();

	return $new_bounty;
}

function getBounty($who) {
	DatabaseConnection::getInstance();

	$statement = DatabaseConnection::$pdo->prepare("SELECT bounty FROM players WHERE player_id = :player");
	$statement->bindValue(':player', $who);
	$statement->execute();
	return $statement->fetchColumn();
}

/**
 * Change a bounty 
 * @param int $who A character id to change the bounty of
 */
function changeBounty($who, $amount) {
	$amount = (int)$amount;

	if (abs($amount) > 0) {
		DatabaseConnection::getInstance();
		$statement = DatabaseConnection::$pdo->prepare("UPDATE players SET bounty = bounty+".
			"CASE WHEN bounty+:amount1 < 0 THEN bounty*(-1) ".
			"WHEN bounty+:amount2 > 5000 THEN (5000 - bounty) ".
			"ELSE :amount3 END ".
			"WHERE player_id = :player");
		$statement->bindValue(':player', $who);
		$statement->bindValue(':amount1', $amount);
		$statement->bindValue(':amount2', $amount);
		$statement->bindValue(':amount3', $amount);
		$statement->execute();
	}

	return getBounty($who);
}

/**
 * @param int $who A character id to change the bounty of
 */
function addBounty($who, $amount) {
	return changeBounty($who, $amount);
}

function rewardBounty($bounty_to, $bounty_on) {
	$bounty = getBounty($bounty_on);

	setBounty($bounty_on, 0);  //Sets bounty to zero.
	add_gold($bounty_to, $bounty);

	return $bounty;
}

function runBountyExchange($username, $defender) {  //  *** BOUNTY EQUATION ***
	$user_id = get_user_id($username);
	$defender_id = get_user_id($defender);
	// *** Bounty Increase equation: (attacker's level - defender's level) / an increment, rounded down ***
	$levelRatio     = floor((getLevel($user_id) - getLevel($defender_id)) / 10);

	$bountyIncrease = min(25, max($levelRatio * 25, 0));	//Avoids negative increases, max of 30 gold, min of 0

	$bountyForAttacker = rewardBounty($user_id, $defender_id); //returns a value if bounty rewarded.
	if ($bountyForAttacker) {
		// *** Reward bounty whenever available. ***
		return "You have received the $bountyForAttacker gold bounty on $defender's head for your deeds!";
		$bounty_msg = "You have valiantly slain the wanted criminal, $defender! For your efforts, you have been awarded $bountyForAttacker gold!";
		sendMessage("Village Doshin", $username, $bounty_msg);
	} else if ($bountyIncrease > 0) {
		// *** If Defender has no bounty and there was a level difference. ***
		addBounty($user_id, $bountyIncrease);
		return "Your victim was much weaker than you. The townsfolk are angered. A bounty of $bountyIncrease gold has been placed on your head!";
	} else {
		return null;
	}
}

// ************************************
// ************************************


// ************************************
// ******** LOGGING FUNCTIONS *******
// ************************************


function sendLogOfDuel($attacker, $defender, $won, $killpoints) {
	$killpoints = (int)$killpoints;

	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("INSERT INTO dueling_log values 
        (default, :attacker, :defender, :won, :killpoints, now())");
        //Log of Dueling information.
	$statement->bindValue(':attacker', $attacker);
	$statement->bindValue(':defender', $defender);
	$statement->bindValue(':won', $won);
	$statement->bindValue(':killpoints', $killpoints);
	$statement->execute();
}

/*
 * Returns a comma-seperated string of states based on the statuses of the target.
 * @param array $statuses status array
 * @param string $target the target, username if self targetting.
 * @return string
 *
 */
function get_status_list($target=null) {
	$states = array();
	$target = (isset($target) && (int)$target == $target ? $target : self_char_id());

	// Default to showing own status.
	$target = new Player($target);

	if ($target->vo->health < 1) {
		$states[] = 'Dead';
	} else { // *** Other statuses only display if not dead.
		if ($target->vo->health < 80) {
			$states[] = 'Injured';
		} else {
			$states[] = 'Healthy';
		}
        // The visibly viewable statuses.
		if ($target->hasStatus(STEALTH)) { $states[] = 'Stealthed'; }
		if ($target->hasStatus(POISON)) { $states[] = 'Poisoned'; }
		if ($target->hasStatus(WEAKENED)) { $states[] = 'Weakened'; }
		if ($target->hasStatus(FROZEN)) { $states[] = 'Frozen'; }
		if ($target->hasStatus(STR_UP1)) { $states[] = 'Buff'; }
		if ($target->hasStatus(STR_UP2)) { $states[] = 'Strength+'; }

		// If any of the shield skills are up, show a single status state for any.
		if($target->hasStatus(FIRE_RESISTING) || $target->hasStatus(INSULATED) || $target->hasStatus(GROUNDED)
		    || $target->hasStatus(BLESSED) || $target->hasStatus(IMMUNIZED)
		    || $target->hasStatus(ACID_RESISTING)){
		    $states[] = 'Shielded';
		}
	}

	return $states;
}
