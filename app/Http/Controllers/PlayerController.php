<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FootballDataService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    protected $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
        $this->middleware('auth:api');
        $this->middleware('permission:view_competitions|view_details_competitions')->only(['getPlayers']);
        $this->middleware('permission:view_details_competitions')->only(['getPlayer']);
    }

    public function getPlayers()
    {
        try {
            $players = $this->footballDataService->getPlayers();
            return response()->json(['data' => $players, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch players', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPlayer(int $id)
    {
        try {
            $players = $this->footballDataService->getPlayerDetails($id);
            return response()->json(['data' => $players, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch players', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
