<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\DellNetworking;

use App\Bandwidth\Format;
use App\Hub\Type\TPortInterfaceWithRegexMatching;
use App\Util\PregMatchMap;

class DellNetworkingPortInterface extends Format\PortInterface {
  const SHORT_IS_LOWERCASE = false;

  use TPortInterfaceWithRegexMatching;
  protected $aggregateRegex = '/(([Vv]lan)|([Pp]ort-[Cc]hannel)) ?([0-9]+)/';

  protected function getRegexMappingForShortNameParsing(): array {
    return [
      // Old format
      '@^([a-zA-Z0-9\-_]+)-([0-9]+)/([0-9]+)/([0-9]+)$@' => function ($matches) {
        return array_slice($matches, 1, 4);
      },

      // Typically single ports
      '@^([a-zA-Z0-9\-_]+) ([0-9]+)/([0-9]+)$@' => function ($matches) {
        return [$matches[1], 0, $matches[2], $matches[3]];
      },

      // Typically aggregate ports
      '@^([a-zA-Z0-9\-_]+) ([0-9]+)$@' => function ($matches) {
        return [$matches[1], 0, 0, $matches[2]];
      },
    ];
  }

  // Not exactly sure what sytems use which prefixes but the Dell Networking device we tested with most recently did not use these.
  const PREFIXES_USING_LONGER_SYNTAX_FOR_SNMP = [
    'gi-',
    'te-',
    'tw-',
  ];

  const SPEED_MAP = [
    'gi' => 'Gigabit',
    'te' => '10G',
    'tw' => '20G',
  ];
  const LONG_FORMAT = 'Unit: %d Slot: %d Port: %d %s - Level';

  /**
   * Map speed prefixes to their respective full names.
   *
   * @var array
   */
  protected $speedMap = self::SPEED_MAP;

  /**
   * @return string
   */
  public function longName() {
    if ($this->usesLongerSyntaxForSNMP()) {
      list($unit, $slot, $port) = $this->usp();
      return sprintf(static::LONG_FORMAT, $unit, $slot, $port, $this->speed());
    }

    return parent::longName();
  }

  private function usesLongerSyntaxForSNMP() {
    return starts_with(
      $this->short(),
      static::PREFIXES_USING_LONGER_SYNTAX_FOR_SNMP
    );
  }

  /**
   * @param string $long
   *
   * @return string
   * @throws Format\PortFormatInvalid
   */
  public static function longToShort($long) {
    $passthrough = function ($matches) {
      return $matches[0];
    };
    $mapping = new PregMatchMap([
      // Old format
      '/^Unit: ([0-9]+) Slot: ([0-9]+) Port: ([0-9]+) ([a-zA-Z0-9]+) - Level$/' => function ($matches) {
        return sprintf(
          '%s-%d/%d/%d',
          array_search($matches[4], static::SPEED_MAP),
          $matches[1],
          $matches[2],
          $matches[3]
        );
      },

      // Typically single ports
      '@^([a-zA-Z0-9\-_]+) ([0-9]+)/([0-9]+)$@' => $passthrough,

      // Typically aggregate ports
      '@^([a-zA-Z0-9\-_]+) ([0-9]+)$@' => $passthrough,
    ]);
    $result = $mapping->firstMatch($long);

    if ($result === null) {
      throw new Format\PortFormatInvalid($long);
    }

    return $result;
  }

  /**
   * @return string
   */
  public function forSwitchCommand() {
    $result = $this->short();
    if ($this->usesLongerSyntaxForSNMP()) {
      $result = str_replace('-', '', $result);
    } else {
      $result = str_replace(' ', '', $result);
    }

    $result[0] = ucfirst($result[0]);

    return $result;
  }
}
