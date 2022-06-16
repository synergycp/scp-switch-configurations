<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Mikrotik;

use App\Bandwidth\Format;
use App\Hub\Type\TPortInterfaceWithRegexMatching;

class MikrotikPortInterface extends Format\PortInterface {
  use TPortInterfaceWithRegexMatching;

  protected function getRegexMappingForShortNameParsing(): array {
    return [
      '@^[eE]ther([0-9]+)$@' => function ($matches) {
        return [0, 0, $matches[1]];
      },
    ];
  }
}
