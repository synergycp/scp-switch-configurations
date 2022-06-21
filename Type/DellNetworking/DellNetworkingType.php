<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\DellNetworking;

use App\Hub\Type\Type;

class DellNetworkingType implements Type {
  const SLUG = 'dell.networking';

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
    return 'ssh.dell.networking';
  }

  /**
   * Must be a class name that implements SwitchFormatter
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingFormat::class
   * @return string
   */
  public function getFormatter() {
    return DellNetworkingFormat::class;
  }

  /**
   * Must be a class name that implements SwitchControlContract.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingSwitchControl::class
   * @return string
   */
  public function getSwitchControl() {
    return DellNetworkingSwitchControl::class;
  }

  /**
   * Must be a class name that implements TargetFilterer.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingTargetFilterer::class
   * @return string
   */
  public function getTargetFilterer() {
    return DellNetworkingTargetFilterer::class;
  }

  /**
   * Must be a class name that implements PortInterface.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingPortInterface::class
   * @return string
   */
  public function getPortInterface() {
    return DellNetworkingPortInterface::class;
  }

  /**
   * A list of potential names for uplink ports.
   *
   * @return string[]
   */
  public function getUplinkPortNames() {
    return ['te-1/0/1'];
  }
}
