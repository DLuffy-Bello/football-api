<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FootballDataService;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    protected $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
        $this->middleware('auth:api');
        $this->middleware('permission:view_competitions|view_details_competitions')->only(['getTeams']);
        $this->middleware('permission:view_details_competitions')->only(['getTeam']);
    }

    public function getTeams()
    {
        try {
            $teams = $this->footballDataService->getTeams();
            return response()->json(['data' => $teams, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch teams', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTeam(int $id)
    {
        try {
            $team = $this->footballDataService->getTeamDetails($id);
            return response()->json(['data' => $team, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch team', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
