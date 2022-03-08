<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class AveragePostNumberPerUser
 *
 * @package Statistics\Calculator
 */
class AveragePostNumberPerUser extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var array
     */
    private $postsByUser = [];

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $key = $postTo->getAuthorId();
        $this->postsByUser[$key] = ($this->postsByUser[$key] ?? 0) + 1;
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {
        $totalUsers = count($this->postsByUser);
        $totalPosts = array_sum($this->postsByUser);

        $value = $totalUsers > 0
            ? $totalPosts / $totalUsers
            : 0;

        return (new StatisticsTo())->setValue(round($value, 2));
    }
}
