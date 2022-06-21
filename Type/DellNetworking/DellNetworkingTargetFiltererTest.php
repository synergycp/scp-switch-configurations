<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\DellNetworking;

use App\Bandwidth\Rtg\Target\TargetFilterer;
use App\Bandwidth\Rtg\Target\TTestsTargetFilterer;
use App\Test\TestCase;

class DellNetworkingTargetFiltererTest extends TestCase {
  use TTestsTargetFilterer;

  public function dataShouldPoll(): array {
    return [
      [
        'name' => 'FastEthernet1',
        'shouldPoll' => true,
      ],
      [
        'name' => 'Po1',
        'shouldPoll' => true,
      ],
      [
        'name' => 'port-channel10',
        'shouldPoll' => true,
      ],
      [
        'name' => 'Gi2/24',
        'shouldPoll' => true,
      ],
      [
        'name' => 'TenGigabitEthernet 1/2',
        'shouldPoll' => true,
      ],
      [
        'name' => 'gi-1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'Vl',
        'shouldPoll' => false,
      ],
      [
        'name' => 'CPU',
        'shouldPoll' => false,
      ],
    ];
  }
  protected function getFilterer(): TargetFilterer {
    return new DellNetworkingTargetFilterer();
  }
}
