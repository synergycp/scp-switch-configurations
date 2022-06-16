<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

class JuniperARPLockingType extends JuniperLegacyType {
  const SLUG = 'juniper.arp-locking';

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
    return 'ssh.juniper.arp-locking';
  }

  /**
   * Must be a class name that implements SwitchFormatter
   *
   * @example App\Hub\Type\DellNetworking\DellNetworkingFormat::class
   * @return string
   */
  public function getFormatter() {
    return JuniperARPLockingFormat::class;
  }
}
