<?php


namespace xZeroMCPE\ZUHC;


use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use xZeroMCPE\Zeroify\Configuration\Configuration;
use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Configuration\TimeConfiguration;
use xZeroMCPE\Zeroify\Game\Game;
use xZeroMCPE\Zeroify\Game\GameType;
use xZeroMCPE\Zeroify\Observer\Observer;
use xZeroMCPE\Zeroify\State\StateType;
use xZeroMCPE\Zeroify\Team\Team;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyEnvironment;
use xZeroMCPE\Zeroify\ZeroifyPlayer;
use xZeroMCPE\ZUHC\Stages\WaitingStage;
use xZeroMCPE\ZUHC\Team\TeamPlayer;

class ZUHC extends PluginBase
{
    public function onEnable()
    {
        $this->setup();
    }

    public function setup() {
        $game = new Zeroify(
            "UHC", 2, 10,
            new ZeroifyEnvironment($this, ZeroifyEnvironment::DEVELOPMENT),
            new Configuration(new PositionConfiguration(new Position(256, 70, 256, Server::getInstance()->getDefaultLevel()), 0, 0)));

        $game->getStateManager()->setState(new WaitingStage(StateType::WAITING, new TimeConfiguration(45)));

        $game->getTeamManager()->addTeam(new Team("Red", new PositionConfiguration(new Position(256, 70, 256, Server::getInstance()->getDefaultLevel()), 0, 0), []));
        $game->getTeamManager()->addTeam(new Team("Blue", new PositionConfiguration(new Position(256, 70, 256, Server::getInstance()->getDefaultLevel()), 0, 0), []));
        $game->getTeamManager()->addTeam(new Team("Black", new PositionConfiguration(new Position(256, 70, 256, Server::getInstance()->getDefaultLevel()), 0, 0), []));

        $game->getTeamManager()->setGame(new Game(GameType::SOLO, ['Blue' => 4, "Red" => 4, "Black" => 2]));

        Observer::getInstance()->setCannotJoinGameCallback(function (ZeroifyPlayer $player) {
            $player->kick("You been a bad boy!");
        });
        try {
            $game->deploy();
            $this->getServer()->getPluginManager()->registerEvents(new ZUHCListener(), $this);
        } catch (\Exception $e) {
            var_dump("OMG MY GAME HAD AN ERROR ");
        }
    }
}