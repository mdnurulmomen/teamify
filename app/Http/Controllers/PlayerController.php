<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Requests\PlayerStoreRequest;

class PlayerController extends Controller
{
    public function index()
    {
        return response("Index", 500);
    }

    public function show()
    {
        return response("Showed", 500);
    }

    public function store(PlayerStoreRequest $request)
    {
        $player = Player::firstOrCreate([
            'name' => $request->name,
            'position' => $request->position
        ]);

        foreach ($request->playerSkills as $playerSkill) {

            $playerAddedSkill = $player->skills()->firstOrCreate([
                'skill' => $playerSkill['skill'],
                'value' => $playerSkill['value']
            ]);

        }

        return (new PlayerResource($player->load('skills')))
               ->response()
               ->setStatusCode(201);
    }

    public function update()
    {
        return response("Updated", 500);
    }

    public function destroy()
    {
        return response("Deleted", 500);
    }
}
