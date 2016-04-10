<?php
namespace NinjaWars\core\data;

use NinjaWars\core\data\Clan;
use NinjaWars\core\data\Player;

/**
 * Who/what/why/where
 *  Create a clan for leaders and members to manage membership and eventually clan structures.
 *
 */
class ClanFactory {
	/**
	 * Create the flesh of an npc from it's data
	**/
	public static function fleshOutFromData($data, Clan $clan){
		$clan->setId($data['clan_id']);
		$clan->setName($data['clan_name']);
		$clan->setAvatarUrl($data['clan_avatar_url']);
	}

	public static function allData(){
		return query_array('select clan_id, clan_name, clan_created_date, clan_founder, clan_avatar_url, description from clan');
	}

	/**
	 * Save the data of an already created clan.
	 */
	public static function save(Clan $clan) {
		if (!$clan->id()) {
			throw new \Exception('Clan cannot be saved as it does not yet have an id.');
		}

        $updated = update_query(
            'update clan set clan_name = :name, clan_founder = :founder, clan_avatar_url = :avatar_url, description = :desc where clan_id = :id',
            [
                ':name'       => $clan->getName(),
                ':founder'    => $clan->getFounder(),
                ':avatar_url' => $clan->getAvatarUrl(),
                ':desc'       => $clan->getDescription(),
                ':id'         => $clan->id(),
            ]
        );

        return (bool)$updated;
    }

    /**
     * Determines the criteria for how clans get ranked and tagged
     *
     * @return array
     * @note
     * returns only non-empty clans.
     */
    public static function clansRanked() {
        $res = [];

        // sum the levels of the players (minus days of inactivity) for each clan
        $counts = query('SELECT sum(round(((level+4)/5+8)-least((days/3), 50))) AS sum, sum(active) as member_count, clan_name, clan_id
            FROM clan JOIN clan_player ON clan_id = _clan_id JOIN players ON _player_id = player_id
            WHERE active = 1 GROUP BY clan_id, clan_name ORDER BY sum DESC');

        foreach ($counts as $clan_info) {
            $max = (isset($max) ? $max : $clan_info['sum']);
            // *** make percentage of highest, multiply by 10 and round to give a 1-10 size ***
            $res[$clan_info['clan_id']]['name']  = $clan_info['clan_name'];
            $res[$clan_info['clan_id']]['score'] = floor(( (($clan_info['sum'] - 1 < 1 ? 0 : $clan_info['sum'] - 1)) / $max) * 10) + 1;
        }

        return $res;
    }
}
