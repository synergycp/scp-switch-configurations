<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Bandwidth\Format;
use App\Hub\Type\TPortInterfaceWithRegexMatching;

class CiscoIOSPortInterface extends Format\PortInterface {
  use TPortInterfaceWithRegexMatching;
  
  protected function getRegexMappingForShortNameParsing(): array {
    return [
      '/^([a-zA-Z]+)([0-9]+)\/([0-9]+)$/' => function($matches) {
        return [0,0,0,0];
      },
      '/^([a-zA-Z]+)-([0-9]+)\/([0-9]+)\/([0-9]+)$/' => function($matches) {
        return [0,0,0,0];
      },
      '/^Ethernet([0-9]+)\/([0-9]+)\/([0-9]+)$/'=> function($matches) {
        return [0,0,0,0];
      },
      '/^FastEthernet([0-9]+)$/' => function($matches) {
        return [0,0,0,0];
      },
    ];
  }
  const SHORT_IS_LOWERCASE = false;
  const AGGREGATE_MATCH = '/(Po|port-channel)([0-9]+)/';

  protected $aggregateRegex = self::AGGREGATE_MATCH;

  /**
   * @return string
   */
  public function forSwitchCommand() {
    return ucwords($this->short());
  }
}
