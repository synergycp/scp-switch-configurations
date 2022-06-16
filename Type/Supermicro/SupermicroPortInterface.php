<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Supermicro;

use App\Bandwidth\Format;
use App\Hub\Type\TPortInterfaceWithRegexMatching;

class SupermicroPortInterface extends Format\PortInterface {
  use TPortInterfaceWithRegexMatching;

  protected function getRegexMappingForShortNameParsing(): array {
    return [
      '@^[gG]i([0-9]+)/([0-9]+)$@' => function ($matches) {
        return ['gi', $matches[2], 0, $matches[1]];
      },
      '@ethernet interface port ([0-9]+)$@' => function ($matches) {
        return ['gigabitethernet', 0, 0, $matches[1]];
      },
      '@^[eE]x([0-9]+)/([0-9]+)$@' => function ($matches) {
        return ['ex', $matches[2], 0, $matches[1]];
      },
    ];
  }

  public function forSwitchCommand() {
    if ($this->prefix() === 'gigabitethernet') {
      return sprintf('%s %d/%d', $this->prefix(), $this->unit, $this->port);
    }

    return parent::forSwitchCommand();
  }

  // /**
  //  * Map speed prefixes to their respective full names.
  //  *
  //  * @var array
  //  */
  // protected $speedMap = [
  //   'Gi' => 'gigabitethernet ',
  //   'Ex' => 'extreme-ethernet ', 
  //   'po' => 'port-channel ',
  // ];

  protected $primaryPrefixes = ['Ex'];

  protected $aggregateRegex = '/(po)([0-9]+)/';
}
