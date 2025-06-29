<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FootballDataService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CompetitionController extends Controller
{
    protected $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
        $this->middleware('auth:api');
        $this->middleware('permission:view_competitions|view_details_competitions')->only(['getCompetitions']);
        $this->middleware('permission:view_details_competitions')->only(['getCompetition']);
    }

    public function getCompetitions()
    {
        try {

            $competitions = $this->footballDataService->getCompetitions();
            return response()->json(['data' => $competitions, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Error fetching competitions: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to fetch competitions', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompetition(int $id)
    {
        try {
            $competition = $this->footballDataService->getCompetitionDetails($id);
            return response()->json(['data' => $competition, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch competition', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
