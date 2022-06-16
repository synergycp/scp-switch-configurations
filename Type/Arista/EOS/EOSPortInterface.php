<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Arista\EOS;

use App\Bandwidth\Format;
use App\Hub\Type\TPortInterfaceWithRegexMatching;

class EOSPortInterface extends Format\PortInterface {
  use TPortInterfaceWithRegexMatching;
  protected $aggregateRegex = '/(([Vv]lan)|([Pp]ort-[Cc]hannel))([0-9]+)/';

  protected function getRegexMappingForShortNameParsing(): array {
    return [
      '@^[eE]thernet([0-9]+)/([0-9]+)$@' => function ($matches) {
        return ['Ethernet', $matches[2], 0, $matches[1]];
      },
      '@^[eE]thernet([0-9]+)$@' => function ($matches) {
        return ['Ethernet', 0, 0, $matches[1]];
      },
    ];
  }
}
