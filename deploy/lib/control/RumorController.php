<?php
namespace NinjaWars\core\control;

use Pimple\Container;
use NinjaWars\core\control\AbstractController;
use NinjaWars\core\data\DatabaseConnection;
use NinjaWars\core\extensions\StreamedViewResponse;

/**
 * Handles the rumors and info displayed by the bathhouse.
 */
class RumorController extends AbstractController {
    const ALIVE = false;
    const PRIV  = false;

    private function duels() {
        return query_array("SELECT dueling_log.*, attackers.player_id AS attacker_id, defenders.player_id AS defender_id FROM dueling_log JOIN players AS attackers ON attackers.uname = attacker JOIN players AS defenders ON defender = defenders.uname ORDER BY id DESC LIMIT 500");
    }

    public function index(Container $p_dependencies) {
        $stats           = $this->stats();
        $parts           = [
            'stats'          => $stats,
            'vicious_killer' => $stats['vicious_killer'],
            'duels'          => $this->duels(),
        ];

        return new StreamedViewResponse('Bath House', 'duel.tpl', $parts, ['quickstat'=>false]);
    }

    /**
     * Stats on recent activity and other aggregate counts/information.
     *
     * @return array
     */
    private function stats() {
        DatabaseConnection::getInstance();
        $viciousResult = DatabaseConnection::$pdo->query('SELECT stat_result from past_stats where id = 4');
        $todaysViciousKiller = $viciousResult->fetchColumn();
        $stats = [];

        $stats['vicious_killer'] = $todaysViciousKiller;
        $playerCount = DatabaseConnection::$pdo->query("SELECT count(player_id) FROM players WHERE active = 1");
        $stats['player_count'] = $playerCount->fetchColumn();

        return $stats;
    }
}
