<?php

namespace App\Interfaces;

interface IFootballDataService
{

    /**
     * Check if the API request can be made based on the rate limit.
     *
     * @return bool
     */
    public function canMakeRequest(): bool;

    /**
     * Make a request to the Football Data API.
     *
     * @param string $endpoint
     * @return array|null
     * @throws \Exception
     */
    public function makeRequest(string $endpoint): ?array;

    /**
     * Get a list of football competitions.
     *
     * @return array
     */
    public function getCompetitions(): array;

    /**
     * Get competition details by ID.
     *
     * @param int $competitionId
     * @return array
     */
    public function getCompetitionDetails(int $competitionId): array;

    /**
     * Get a list of matches for a specific competition.
     *
     * @return array
     */
    public function getTeams (): array;

    /**
     * Get details of a specific team.
     *
     * @param int $teamId
     * @return array
     */
    public function getTeamDetails(int $teamId): array;

}
