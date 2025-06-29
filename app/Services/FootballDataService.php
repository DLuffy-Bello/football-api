<?php

namespace App\Services;

use App\Interfaces\IFootballDataService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FootballDataService implements IFootballDataService
{
    private $apiUrl;
    private $apiKey;
    private $rateLimit  = 'football_api_rate_limit';

    public function __construct()
    {
        $this->apiUrl = config('services.football_data.api_url');
        $this->apiKey = config('services.football_data.api_key');
    }

    /**
     * Check if the API request can be made based on the rate limit.
     *
     * @return bool
     */
    public function canMakeRequest(): bool
    {
        $currentRate = Cache::get($this->rateLimit, 0);
        $maxRate = config('services.football_data.rate_limit', 10);

        if ($currentRate < $maxRate) {
            Cache::increment($this->rateLimit);
            return true;
        }

        return false;
    }

    /**
     * Make a request to the Football Data API.
     *
     * @param string $endpoint
     * @return array|null
     * @throws \Exception
     */
    public function makeRequest(string $endpoint): ?array
    {
        if (!$this->canMakeRequest()) {
            throw new \Exception('API rate limit exceeded');
        }

        try {
            $response = Http::withHeaders([
                'X-Auth-Token' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->apiUrl . $endpoint);

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            throw new \Exception('Error making API request: ' . $e->getMessage());
        }
    }

    /**
     * Get football competitions from the API.
     *
     * @return array|null
     */
    public function getCompetitions(): array
    {
        $cacheKey = 'football_competitions';
        return Cache::remember('competitions', 60, function () use ($cacheKey) {
            try {
                $data = $this->makeRequest('/competitions');
                return $data['competitions'] ?? [];
            } catch (\Exception $e) {
                $fallbackData = Cache::get("{$cacheKey}_fallback", []);
                if (!empty($fallbackData)) {
                    return $fallbackData;
                }
                return [];
            }
        });
    }

    /**
     * Get details of a specific football competition.
     *
     * @param int $competitionId
     * @return array|null
     */
    public function getCompetitionDetails(int $competitionId): array
    {
        $cacheKey = "football_competition_{$competitionId}";

        return Cache::remember($cacheKey, 60 * 60 * 12, function () use ($competitionId, $cacheKey) {
            try {
                $data = $this->makeRequest("/competitions/{$competitionId}/teams");

                if ($data) {
                    Cache::put("{$cacheKey}_fallback", $data, 60 * 60 * 24 * 7);
                }

                return $data;
            } catch (\Exception $e) {
                $fallbackData = Cache::get("{$cacheKey}_fallback");
                if ($fallbackData) {
                    return $fallbackData;
                }
                return [];
            }
        });
    }

    /**
     * Get a list of teams from the API.
     *
     * @return array
     */
    public function getTeams(): array
    {
        $cacheKey = 'football_teams';

        return Cache::remember($cacheKey, 60, function () use ($cacheKey) {
            try {
                $data = $this->makeRequest('/teams');
                return $data['teams'] ?? [];
            } catch (\Exception $e) {
                $fallbackData = Cache::get("{$cacheKey}_fallback", []);
                if (!empty($fallbackData)) {
                    return $fallbackData;
                }
                return [];
            }
        });
    }

    /**
     * Get details of a specific football team.
     *
     * @param int $teamId
     * @return array
     */
    public function getTeamDetails(int $teamId): array
    {
        $cacheKey = "football_team_{$teamId}";

        return Cache::remember($cacheKey, 60 * 60 * 12, function () use ($teamId, $cacheKey) {
            try {
                $data = $this->makeRequest("/teams/{$teamId}");

                if ($data) {
                    Cache::put("{$cacheKey}_fallback", $data, 60 * 60 * 24 * 7);
                }

                return $data ?? [];
            } catch (\Exception $e) {
                $fallbackData = Cache::get("{$cacheKey}_fallback");
                if ($fallbackData) {
                    return $fallbackData;
                }
                return [];
            }
        });
    }
}
