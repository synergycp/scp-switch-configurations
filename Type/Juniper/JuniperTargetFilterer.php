<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

use App\Bandwidth\Rtg\Target\RtgTarget;
use App\Bandwidth\Rtg\Target\TargetFilterer;

class JuniperTargetFilterer extends TargetFilterer {
  protected $allowedPrefixes = ['mge-', 'ge-', 'xe-', 'ae', 'et'];

  protected $notAllowedSuffixes = ['.0'];

  public function shouldPoll(RtgTarget $target) {
    $name = trim($target->name());
    $namePrefixMatch = function ($prefix) use ($name) {
      return starts_with($name, $prefix);
    };
    $nameSuffixMatch = function ($suffix) use ($name) {
      return ends_with($name, $suffix);
    };

    if (!collection($this->allowedPrefixes)->contains($namePrefixMatch)) {
      return false;
    }

    if (collection($this->notAllowedSuffixes)->contains($nameSuffixMatch)) {
      return false;
    }

    return true;
  }
}
