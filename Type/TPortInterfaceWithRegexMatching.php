<?php

namespace App\Hub\Type;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Util\PregMatchMap;

trait TPortInterfaceWithRegexMatching {
  /**
   * @throws PortFormatInvalid
   */
  protected function parseShortNonAggregateForPrefixAndUSP(): array {
    $mapping = new PregMatchMap($this->getRegexMappingForShortNameParsing());
    $result = $mapping->firstMatch($this->short());

    if ($result === null) {
      throw new PortFormatInvalid($this->short());
    }

    return $result;
  }

  abstract protected function getRegexMappingForShortNameParsing(): array;
}
