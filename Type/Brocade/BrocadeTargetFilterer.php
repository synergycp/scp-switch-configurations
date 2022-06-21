<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Brocade;

use App\Bandwidth\Rtg\Target\RtgTarget;
use App\Bandwidth\Rtg\Target\TargetFilterer;

class BrocadeTargetFilterer extends TargetFilterer {
  protected $allowedPrefixes = [
    'ethernet',
    'GigabitEthernet',
    '10GigabitEthernet',
    '40GigabitEthernet',
    'ae',
  ];

  public function shouldPoll(RtgTarget $target) {
    $name = trim($target->name());
    $namePrefixMatch = function ($prefix) use ($name) {
      return starts_with($name, $prefix);
    };

    if (!collection($this->allowedPrefixes)->contains($namePrefixMatch)) {
      return false;
    }

    return true;
  }
}
