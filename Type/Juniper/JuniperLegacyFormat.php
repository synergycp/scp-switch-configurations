<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

use App\Hub\Format\FormatSwitch;

class JuniperLegacyFormat extends FormatSwitch {
  /**
   * @var bool
   */
  protected $noEnablePass = true;

  /**
   * @var string
   */
  protected $lang = 'ssh.juniper.legacy';

  /**
   * @var array
   */
  protected $ignoreLines = ['{master:0}', '{master:0}[edit]'];

  public function portSpeed($speed): string {
    if ($speed >= 1000) {
      return $speed / 1000 . 'g';
    }

    return $speed . 'm';
  }

  public function enable(array $command): array {
    // On Juniper switches, the enable command (`cli`) is only needed for the root user.
    return $this->switch->sshUser() === 'root'
      ? parent::enable($command)
      : $command;
  }
}
