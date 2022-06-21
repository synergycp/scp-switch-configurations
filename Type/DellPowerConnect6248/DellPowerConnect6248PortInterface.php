<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\DellPowerConnect6248;

use App\Hub\Type\DellNetworking\DellNetworkingPortInterface;

class DellPowerConnect6248PortInterface extends DellNetworkingPortInterface {
  /**
   * @return string
   */
  public function forSwitchCommand() {
    return sprintf('%d/g%d', $this->unit, $this->port);
  }
}
