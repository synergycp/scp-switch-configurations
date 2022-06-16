<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Bandwidth\Rtg\Target\TargetFilterer;
use App\Bandwidth\Rtg\Target\TTestsTargetFilterer;
use App\Test\TestCase;

class CiscoNexusTargetFiltererTest extends TestCase {
  use TTestsTargetFilterer;

  public function dataShouldPoll(): array {
    return[
      [
        'name' => 'mgmt0',
        'shouldPoll' => false,
      ],
      [
        'name' => 'Po10',
        'shouldPoll' => true,
      ],
      [
        'name' => 'port-channel10',
        'shouldPoll' => true,
      ],
      [
        'name' => 'gi1/2',
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
      [
        'name' => 'Link Aggregate',
        'shouldPoll' => false,
      ],
    ];
  }
  protected function getFilterer(): TargetFilterer {
    return new CiscoNexusTargetFilterer();
  }
}
