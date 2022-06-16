<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Hub\Type\DellNetworking\DellNetworkingSwitchControl;
use App\Hub\Type\Type;

class CiscoNexusType implements Type {
  const SLUG = 'cisco-nexus';

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
    return 'ssh.cisco.nexus';
  }

  /**
   * Must be a class name that implements SwitchFormatter
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingFormat::class
   * @return string
   */
  public function getFormatter() {
    return CiscoNexusFormat::class;
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
    return CiscoNexusTargetFilterer::class;
  }

  /**
   * Must be a class name that implements PortInterface.
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingPortInterface::class
   * @return string
   */
  public function getPortInterface() {
    return CiscoIOSPortInterface::class;
  }

  /**
   * A list of potential names for uplink ports.
   *
   * @return string[]
   */
  public function getUplinkPortNames() {
    return [
      'TenGigabitEthernet1/25',
      'TenGigabitEthernet1/26',
      'TenGigabitEthernet1/49',
      'TenGigabitEthernet1/50',
    ];
  }
}
