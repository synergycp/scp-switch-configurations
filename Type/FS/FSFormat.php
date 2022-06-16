<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\FS;

use App\Hub\Format\FormatSwitch;

class FSFormat extends FormatSwitch {
  /**
   * @var bool
   */
  protected $noEnablePass = true;

  /**
   * @var string
   */
  protected $lang = 'ssh.fs.v1';

  public function portSpeed($speed): string {
    if ($speed > 1000) {
      return (string) ($speed / 1000) . 'G';
    }

    return (string) $speed;
  }
}
