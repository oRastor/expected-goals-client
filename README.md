# Expected Goals Client

PHP client for [football (soccer) expected goals (xG) statistics API](https://rapidapi.com/Wolf1984/api/football-xg-statistics/).
It provides a list of events with xG metric for every game of more than 80 leagues.

## Usage

To install the latest version of `rastor/expected-goals-client` use [composer](https://getcomposer.org).

```bash
composer require rastor/expected-goals-client
```

## Example usage

Basic Usage
```php
use ExpectedGoalsClient\ExpectedGoalsClient;

$client = new ExpectedGoalsClient('Your API Key');

$countries = $client->getCountries(); // list of countries
$leagues = $client->getLeagues($countryId); // list of leagues for specified country
$seasons = $client->getSeasons($leagueId); // list of seasons for specified league
$fixtures = $client->getFixtures($seasonId); // list of fixtures for specified season
$fixture = $client->getFixture($fixtureId); // get one fixture
```

Calculating xg90 (expected goals for 90 minutes) metric for every team of available seasons 
```php
foreach ($client->getCountries() as $country) {
    foreach ($client->getLeagues($country->id) as $league) {
        foreach ($client->getSeasons($league->id) as $season) {
            echo "{$country->name}. {$league->name} ({$season->name})\n";
            echo "=====\n";

            $seasonFixtures = $client->getFixtures($season->id);

            $expectedGoals = [];
            $minutes = [];
            $teamNames = [];
            foreach ($seasonFixtures as $fixture) {
                if (!isset($teamNames[$fixture->homeTeam->id])) {
                    $teamNames[$fixture->homeTeam->id] = $fixture->homeTeam->name;
                    $minutes[$fixture->homeTeam->id] = 0;
                }

                if (!isset($teamNames[$fixture->awayTeam->id])) {
                    $teamNames[$fixture->awayTeam->id] = $fixture->awayTeam->name;
                    $minutes[$fixture->awayTeam->id] = 0;
                }

                $minutes[$fixture->homeTeam->id] += $fixture->duration->firstHalf + $fixture->duration->secondHalf;
                $minutes[$fixture->awayTeam->id] += $fixture->duration->firstHalf + $fixture->duration->secondHalf;

                foreach ($fixture->events as $event) {
                    if (!$event->xg) {
                        continue;
                    }

                    if (!isset($expectedGoals[$event->teamId])) {
                        $expectedGoals[$event->teamId] = 0;
                    }

                    $expectedGoals[$event->teamId] += $event->xg;
                }
            }

            $result = [];
            foreach ($expectedGoals as $teamId => $value) {
                $result[$teamId] = ($value / $minutes[$teamId]) * 90;
            }

            arsort($result);

            foreach ($result as $teamId => $value) {
                echo "$teamNames[$teamId]: {$value}\n";
            }

            echo PHP_EOL;
        }
    }
}
```

Example Output:
```
England. Premier League (2016/2017)
=====
Manchester City: 2.2112692731278
Tottenham: 2.0528394039735
Chelsea: 1.8262697313764
Arsenal: 1.7997027250206
Liverpool: 1.6997235277855
Manchester Utd: 1.6932413793103
Southampton: 1.4393784530387
Everton: 1.3932328539823
Bournemouth: 1.2910729023384
Stoke: 1.2596034150372
Leicester: 1.2125481563016
West Ham: 1.2049150684932
Crystal Palace: 1.1981870860927
Swansea: 1.0498671831765
Burnley: 0.95350882028666
Watford: 0.9309592061742
West Brom: 0.91582526956041
Sunderland: 0.9
Hull: 0.83620127177219
Middlesbrough: 0.69719434433047

England. Premier League (2017/2018)
=====
Manchester City: 2.3988232044199
Liverpool: 1.8711009933775
Tottenham: 1.8331631244825
Arsenal: 1.6883651452282
Manchester Utd: 1.5726460005536
Chelsea: 1.4510011061947
Crystal Palace: 1.4030157415079
Leicester: 1.2518565517241
Watford: 1.1562657534247
Everton: 1.1204689655172
Newcastle: 1.0640897755611
West Ham: 1.0446826051113
Bournemouth: 0.99573626373627
Brighton: 0.98392668703138
Southampton: 0.92284729878721
Stoke: 0.8937382661513
Burnley: 0.88359102244389
West Brom: 0.83442573163998
Swansea: 0.77539422543032
Huddersfield: 0.75367533185841

...
```

