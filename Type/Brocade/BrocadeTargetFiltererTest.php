<?php

namespace App\Hub\Type\Brocade;

use App\Bandwidth\Rtg\Target\TargetFilterer;
use App\Bandwidth\Rtg\Target\TTestsTargetFilterer;
use App\Test\TestCase;

class BrocadeTargetFiltererTest extends TestCase {
  use TTestsTargetFilterer;

  public function dataShouldPoll(): array {
    return [
      [
        'name' => '10GigabitEthernet1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => '40GigabitEthernet1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'GigabitEthernet1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'ethernet1/2/3',
        'shouldPoll' => true,
      ],
      [
        'name' => 'something1/2/3',
        'shouldPoll' => false,
      ],
      [
        'name' => 'Management',
        'shouldPoll' => false,
      ],
    ];
  }

  protected function getFilterer(): TargetFilterer {
    return new BrocadeTargetFilterer();
  }
}
