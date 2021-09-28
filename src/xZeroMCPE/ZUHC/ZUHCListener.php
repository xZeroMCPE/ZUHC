<?php


namespace xZeroMCPE\ZUHC;


use pocketmine\event\Listener;
use xZeroMCPE\Zeroify\Events\PlayerAttackUnknownEvent;

class ZUHCListener implements Listener
{

    public function onDamage(PlayerAttackUnknownEvent $event) {
        $player = $event->getVictim();

        $player->sendMessage("Hey man, stop causing damage to yourself!");
        $event->setAllowed(false);
    }
}