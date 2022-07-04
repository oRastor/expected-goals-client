<?php


namespace ExpectedGoalsClient\Entity;


class Event
{
    public const TYPE_GOAL = 'goal';
    public const TYPE_MISS = 'miss';
    public const TYPE_SAVE = 'save';
    public const TYPE_BLOCK = 'block';
    public const TYPE_OWN_GOAL = 'own-goal';
    public const TYPE_RED_CARD = 'red-card';
    public const TYPE_PENALTY_GOAL = 'penalty-goal';
    public const TYPE_PENALTY_MISS = 'penalty-miss';

    /**
     * @var int
     */
    public $homeScore;

    /**
     * @var int
     */
    public $awayScore;

    /**
     * @var int
     */
    public $minute;

    /**
     * @var int|null
     */
    public $additionalMinute;

    /**
     * @var Player
     */
    public $author;

    /**
     * @var int
     */
    public $teamId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var float|null
     */
    public $xg;

    /**
     * @return int
     */
    public function getHalfNumber(): int
    {
        if ($this->minute > 45) {
            return 2;
        }

        return 1;
    }
}