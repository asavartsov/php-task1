<?php

declare(strict_types = 1);

namespace Tests\unit;

use DateTime;
use PHPUnit\Framework\TestCase;

use Statistics\Calculator\AveragePostNumberPerUser;
use Statistics\Dto\ParamsTo;
use Statistics\Enum\StatsEnum;
use SocialPost\Dto\SocialPostTo;

/**
 * Class AveragePostNumberPerUserTest
 *
 * @package Tests\unit
 */
class AveragePostNumberPerUserTest extends TestCase
{
    private ParamsTo $paramsTo; 

    protected function setUp(): void
    {
        $this->paramsTo = (new ParamsTo())
            ->setStatName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER)
            ->setStartDate(new DateTime('01-01-2022'))
            ->setEndDate(new DateTime('31-01-2022'));;
    }

    /**
     * @test
     * @dataProvider providePostsData
     */
    public function testCalculatesAverage($expected, $posts): void
    {
        $calculator = new AveragePostNumberPerUser();
        $calculator->setParameters($this->paramsTo);

        foreach ($posts as $post) {
            $calculator->accumulateData($post);
        }

        $value = $calculator->calculate();
        $this->assertEquals($expected, $value->getValue());
    }

    public function providePostsData()
    {
        return [
            'empty posts set' => [
                0,
                []
            ],
            'sample posts set' => [
                1.5,
                [
                    (new SocialPostTo())->setAuthorId('user_1')->setDate(new DateTime('01-01-2022')),
                    (new SocialPostTo())->setAuthorId('user_1')->setDate(new DateTime('02-01-2022')),
                    (new SocialPostTo())->setAuthorId('user_2')->setDate(new DateTime('02-01-2022'))
                ]
            ]
        ];
    }
}
