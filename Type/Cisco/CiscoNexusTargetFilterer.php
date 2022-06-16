<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Bandwidth\Rtg\Target\TargetFilterer;

class CiscoNexusTargetFilterer extends TargetFilterer {
  /**
   * Prefixes to ignore when looking at interface names.
   *
   * @var array
   */
  protected $ignoredPrefixes = [
    'Link Aggregate', # Link Aggregate (Dell)
    'Vl', # Vlan (Dell)
    'CPU', # CPU (Dell)
    'mgmt',
  ];
}
