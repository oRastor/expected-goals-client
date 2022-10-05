<?php


namespace ExpectedGoalsClient\Entity;

class Fixture
{
    public const STATUS_FINISHED = 'finished';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var int|null
     */
    public $startTime;

    /**
     * @var int
     */
    public $updateTime;

    /**
     * @var Team
     */
    public $homeTeam;

    /**
     * @var Team
     */
    public $awayTeam;

    /**
     * @var Duration
     */
    public $duration;

    /**
     * @var TeamScore
     */
    public $homeScore;

    /**
     * @var TeamScore
     */
    public $awayScore;

    /**
     * @var Country
     */
    public $country;

    /**
     * @var Tournament
     */
    public $tournament;

    /**
     * @var Season
     */
    public $season;

    /**
     * @var Event[]
     */
    public $events;

    /**
     * @var Score
     */
    public $xg;

    public function isFinished(): bool
    {
        return $this->status == self::STATUS_FINISHED;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "#{$this->id} {$this->homeTeam->name} - {$this->awayTeam->name}";
    }
}