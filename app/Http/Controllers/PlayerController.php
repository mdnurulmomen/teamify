<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use Exception;
use App\Models\Player;
use App\Models\PlayerSkill;
use App\Http\Requests\TeamRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlayerRequest;
use App\Http\Resources\TeamResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;


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

    public function store(PlayerRequest $request)
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

        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                // 'code' => $e->getCode(),
            ], 400);

        }

        return (new PlayerResource($player->load('skills')))
               ->response()
               ->setStatusCode(201);
    }

    public function update(PlayerRequest $request, $playerid)
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

        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                // 'code' => $e->getCode(),
            ], 400);

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

            $group = Player::with('skills')
            ->where('position', $requirement['position'])
            ->whereNotIn('id', array_column($players, 'id'))
            ->whereHas('skills', function ($query) use ($requirement) {
                $query->where('skill', $requirement['mainSkill']);
            })
            ->orderByDesc(
                PlayerSkill::select('value')
                ->whereColumn('player_id', 'players.id')
                ->orderByDesc('value')
                ->limit(1)
            )
            ->take($requirement['numberOfPlayers'])
            ->get();

            if ($group->isEmpty() || $group->count() < $requirement['numberOfPlayers']) {

                $group2 = Player::with('skills')
                ->where('position', $requirement['position'])
                ->whereNotIn('id', array_column($players, 'id'))
                ->orderByDesc(
                    PlayerSkill::select('value')
                    ->whereColumn('player_id', 'players.id')
                    ->orderByDesc('value')
                    ->limit(1)
                )
                ->take(($requirement['numberOfPlayers'] - $group->count()))
                ->get();

                $group = $group->merge($group2);

            }

            if ($group->count() < $requirement['numberOfPlayers']) {

                return response()->json([
                    'message' => 'Insufficient number of players for position: '.$requirement['position'],
                ], 422);

            }
            else {

                foreach ($group as $memberKey => $member) {

                    array_push($players, $member);

                }

            }

        }

        return TeamResource::collection($players);
    }
}
