<?php


namespace ExpectedGoalsClient;


use Exception;
use ExpectedGoalsClient\Entity\Country;
use ExpectedGoalsClient\Entity\Fixture;
use ExpectedGoalsClient\Entity\League;
use ExpectedGoalsClient\Entity\Season;
use GuzzleHttp\Client as HttpClient;
use JsonMapper;

class ExpectedGoalsClient
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var JsonMapper
     */
    private $mapper;

    public function __construct(string $key)
    {
        $this->client = new HttpClient([
            'base_uri' => 'https://football-xg-statistics.p.rapidapi.com/',
            'headers' => [
                'User-Agent' => 'ExpectedScoreClient/1.0',
                'X-RapidAPI-Host' => 'football-xg-statistics.p.rapidapi.com',
                'X-RapidAPI-Key' => $key,
            ]
        ]);

        $this->mapper = new JsonMapper();
    }

    /**
     * @return Country[]
     * @throws Exception
     */
    public function getCountries(): array
    {
        $response = $this->makeRequest('/countries/');

        return $this->mapper->mapArray($response->result, [], Country::class);
    }

    /**
     * @param $countryId
     * @return League[]
     * @throws Exception
     */
    public function getTournaments($countryId): array
    {
        $response = $this->makeRequest("/countries/{$countryId}/tournaments/");

        return $this->mapper->mapArray($response->result, [], League::class);
    }

    /**
     * @param $leagueId
     * @return Season[]
     * @throws \JsonMapper_Exception
     */
    public function getSeasons($leagueId): array
    {
        $response = $this->makeRequest("/tournaments/{$leagueId}/seasons/");

        return $this->mapper->mapArray($response->result, [], Season::class);
    }

    /**
     * @param $seasonId
     * @return Fixture[]
     * @throws \JsonMapper_Exception
     */
    public function getFixtures($seasonId): array
    {
        $response = $this->makeRequest("/seasons/{$seasonId}/fixtures/");

        return $this->mapper->mapArray($response->result, [], Fixture::class);
    }

    /**
     * @param $id
     * @return Fixture
     * @throws \JsonMapper_Exception
     */
    public function getFixture($id): Fixture
    {
        $response = $this->makeRequest("/fixtures/{$id}/");

        return $this->mapper->map($response->result, new Fixture());
    }

    private function makeRequest($uri)
    {
        $result = $this->client->get($uri);

        $result = json_decode($result->getBody()->getContents());

        if (isset($result->error)) {
            throw new Exception($result->error->text);
        }

        return $result;
    }
}
