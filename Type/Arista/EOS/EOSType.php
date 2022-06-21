<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Arista\EOS;

use App\Bandwidth\Rtg\Target\TargetFilterer;
use App\Hub\Ssh\SwitchControl;
use App\Hub\Type\Type;

class EOSType implements Type {
  const SLUG = 'arista.eos';

  /**
   * Must be unique across all of SynergyCP. This is what's stored in the database.
   *
   * @example dell-networking
   * @example brocade-fcx-layer3
   * @return string
   */
  public function getSlug() {
    return static::SLUG;
  }

  /**
   * Must be the key to a language array that includes at least 'name'.
   *
   * @example ssh.dell
   * @return string
   */
  public function getLang() {
    return 'ssh.arista.eos';
  }

  /**
   * Must be a class name that implements SwitchFormatter
   *
   * @return string
   */
  public function getFormatter() {
    return EOSFormat::class;
  }

  /**
   * Must be a class name that implements SwitchControlContract.
   *
   * @return string
   */
  public function getSwitchControl() {
    return SwitchControl::class;
  }

  /**
   * Must be a class name that implements TargetFilterer.
   *
   * @return string
   */
  public function getTargetFilterer() {
    return TargetFilterer::class;
  }

  /**
   * Must be a class name that implements PortInterface.
   *
   * @return string
   */
  public function getPortInterface() {
    return EOSPortInterface::class;
  }

  /**
   * A list of potential names for uplink ports.
   *
   * @return string[]
   */
  public function getUplinkPortNames() {
    return [];
  }
}
