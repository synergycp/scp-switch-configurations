<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Hub\Format\FormatSwitch;

class CiscoNexusFormat extends FormatSwitch {
  /**
   * @var bool
   */
  protected $noEnablePass = true;

  /**
   * @var string
   */
  protected $lang = 'ssh.cisco.nexus';
}
