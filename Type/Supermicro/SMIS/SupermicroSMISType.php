<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Supermicro\SMIS;

use App\Hub\Ssh\SwitchControl;
use App\Hub\Type\Supermicro\SupermicroPortInterface;
use App\Hub\Type\Supermicro\SupermicroTargetFilterer;
use App\Hub\Type\Type;

class SupermicroSMISType implements Type {
  const SLUG = 'supermicro.smis';

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
    return 'ssh.supermicro.smis';
  }

  /**
   * Must be a class name that implements SwitchFormatter
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingFormat::class
   * @return string
   */
  public function getFormatter() {
    return SupermicroSMISFormat::class;
  }

  /**
   * Must be a class name that implements SwitchControlContract.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingSwitchControl::class
   * @return string
   */
  public function getSwitchControl() {
    return SwitchControl::class;
  }

  /**
   * Must be a class name that implements TargetFilterer.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingTargetFilterer::class
   * @return string
   */
  public function getTargetFilterer() {
    return SupermicroTargetFilterer::class;
  }

  /**
   * Must be a class name that implements PortInterface.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingPortInterface::class
   * @return string
   */
  public function getPortInterface() {
    return SupermicroPortInterface::class;
  }

  /**
   * A list of potential names for uplink ports.
   *
   * @return string[]
   */
  public function getUplinkPortNames() {
    return ['Ex0/1', 'Ex0/2', 'Ex0/3', 'Ex0/4'];
  }
}
