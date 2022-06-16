<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

use App\Bandwidth\Format;

class JuniperPortInterface extends Format\PortInterface {
  /**
   * Map speed prefixes to their respective full names.
   *
   * @var array
   */
  protected $speedMap = [
    'mge' => 'Gigabit',
    'ge' => 'Gigabit',
    'xe' => '10G',
    'ae' => 'Aggregate Ethernet',
    'et' => '100G',
  ];

  protected $primaryPrefixes = ['xe'];

  protected $aggregateRegex = '/(ae)([0-9]+)/';
}
