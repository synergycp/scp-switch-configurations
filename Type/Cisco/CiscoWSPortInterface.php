<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Bandwidth\Format;

class CiscoWSPortInterface
  extends Format\PortInterface {
  const LONG_MATCH = '/([a-zA-Z]+)([0-9]+)\/?([0-9]+)?\/?([0-9]+)?/';
  const LONG_FORMAT = '%s%d/%d/%d';

  /**
   * @return string
   */
  public function forSwitchCommand() {
    return ucwords($this->short());
  }

  /**
   * @return string[]
   * @throws Format\PortFormatInvalid
   */
  protected function parseShortNonAggregateForPrefixAndUSP(): array {
    if (!preg_match(static::LONG_MATCH, $this->short(), $matches)) {
      throw new Format\PortFormatInvalid($this->short());
    }

    // The first value in matches is the full string.
    array_shift($matches);

    // If it's just a prefix and a port, put the port as last.
    if (count($matches) === 2) {
      return [$matches[0], null, null, $matches[1]];
    }

    // The rest of the values are the individual parts (prefix, unit, slot, port).
    return $matches;
  }
}
