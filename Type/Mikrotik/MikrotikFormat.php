<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Mikrotik;

use App\Hub\Format\FormatSwitch;

class MikrotikFormat extends FormatSwitch {
  /**
   * @var string
   */
  protected $lang = 'ssh.mikrotik';

  public function portSpeed($speed): string {
    if ($speed >= 1000) {
      return $speed / 1000 . 'Gbps';
    }

    return $speed . 'Mbps';
  }

  /**
   * @return string[]
   */
  protected function getIgnoredLinesRegex(): array {
    return [
      // ignore broadcast messages
      '@([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}) echo: system,@',
    ];
  }
}
