<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\FS;

use App\Bandwidth\Format;

class FSPortInterface extends Format\PortInterface {
  const HYPHENATED_MATCH = '/([a-zA-Z]+)-([0-9]+)-([0-9]+)/';

  /**
   * @return string
   */
  public function forSwitchCommand() {
    return $this->short();
  }

  /**
   * @return string[]
   * @throws Format\PortFormatInvalid
   */
  protected function parseShortNonAggregateForPrefixAndUSP(): array {
    if (!preg_match(static::HYPHENATED_MATCH, $this->short(), $matches)) {
      throw new Format\PortFormatInvalid($this->short());
    }

    // The first value in matches is the full string.
    array_shift($matches);

    // The rest of the values are the individual parts (prefix, unit, slot, port).
    return [$matches[0], null, $matches[1], $matches[2]];
  }
}
