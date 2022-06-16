<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Supermicro;

use App\Bandwidth\Rtg\Target\TargetFilterer;

class SupermicroTargetFilterer extends TargetFilterer {
  protected $ignoredPrefixes = ['mgmt', 'vlan'];
}
