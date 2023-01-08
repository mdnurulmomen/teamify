<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Models\Player;
use App\Http\Requests\TeamRequest;
use App\Http\Resources\TeamResource;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Requests\PlayerStoreRequest;


class PlayerController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified-token')->only('destroy');
    }

    public function index()
    {
        return PlayerResource::collection(Player::all());
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);

        return new PlayerResource($player);
    }

    public function store(PlayerStoreRequest $request)
    {
        try {

            DB::beginTransaction();

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

            DB::commit();

        } catch (Throwable $e) {

            DB::rollBack();

        }

        return (new PlayerResource($player->load('skills')))
               ->response()
               ->setStatusCode(201);
    }

    public function update(PlayerStoreRequest $request, $playerid)
    {
        $player = Player::findOrFail($playerid);

        try {

            DB::beginTransaction();

            $player->update([
                'name' => $request->name,
                'position' => $request->position
            ]);

            $player->skills()->delete();

            foreach ($request->playerSkills as $playerSkill) {

                $playerAddedSkill = $player->skills()->create([
                    'skill' => $playerSkill['skill'],
                    'value' => $playerSkill['value']
                ]);

            }

            DB::commit();

        } catch (Throwable $e) {

            DB::rollBack();

        }

        return new PlayerResource($player->load('skills'));
    }

    public function destroy($playerid)
    {
        $player = Player::findOrFail($playerid);

        $player->skills()->delete();

        $player->delete();

        return response()->json(['message' => 'Player has been deleted.'], 200);
    }

    public function getTeamPlayers(TeamRequest $request)
    {
        $players = [];

        foreach ($request->all() as $requirementKey => $requirement) {

            $group = Player::where('position', $requirement['position'])
            ->whereHas('skills', function ($query) use ($requirement) {
                $query->where('skill', $requirement['mainSkill']);
            })
            ->take($requirement['numberOfPlayers'])
            ->get();

            foreach ($group as $memberKey => $member) {

                array_push($players, $member);

            }

        }

        return TeamResource::collection($players);
    }
}
