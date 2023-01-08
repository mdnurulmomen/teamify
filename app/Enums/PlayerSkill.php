<?php

namespace App\Enums;

enum PlayerSkill: string
{
    case Defense = 'defense';
    case Attack = 'attack';
    case Speed = 'speed';
    case Strength = 'strength';
    case Stamina = 'stamina';
}
