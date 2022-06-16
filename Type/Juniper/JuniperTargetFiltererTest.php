<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

use App\Bandwidth\Rtg\Target\TargetFilterer;
use App\Bandwidth\Rtg\Target\TTestsTargetFilterer;
use App\Test\TestCase;

class JuniperTargetFiltererTest extends TestCase {
  use TTestsTargetFilterer;

  public function dataShouldPoll(): array {
    return [
      [
        'name' => 'ge-1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'ge1/2/3',
        'shouldPoll' => false,
      ],
      [
        'name' => 'ae-1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'ae1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'mge-1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'xe-1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'test-1/2/3',
        'shouldPoll' => false,
      ],
      [
        'name' => 'Management',
        'shouldPoll' => false,
      ],
      [
        'name' => 'et-40/50/100',
        'shouldPoll' => true,
      ],
    ];
  }

  protected function getFilterer(): TargetFilterer {
    return new JuniperTargetFilterer();
  }
}
