<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FootballDataService;
use Illuminate\Http\Response;

class CompetitionController extends Controller
{
    protected $footballDataService;

    public function __construct(FootballDataService $footballDataService)
    {
        $this->footballDataService = $footballDataService;
    }

    public function getCompetitions()
    {
        try {
            $competitions = $this->footballDataService->getCompetitions();
            return response()->json(['data' => $competitions, 'messages' => 'Success message'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch competitions', 'messages' => 'Error message'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
