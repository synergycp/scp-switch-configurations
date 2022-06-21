<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

use App\Hub\Format\TTestsFormat;
use App\Hub\HubMultiVLANLogic;
use App\Test\TestCase;

class JuniperARPLockingFormatTest extends TestCase {
  use TTestsFormat;

  protected function switchTypeSlug(): string {
    return JuniperARPLockingType::SLUG;
  }

  public function testSetPortSpeed() {
    $this->portNetwork()
      ->switchPort()
      ->update([
        'name' => 'ge0/0/1',
      ]);
    $this->assertCommandEquals(
      [
        'configure private',
        'set interfaces ge0/0/1 ether-options speed 1g',
        'commit',
        'exit',
      ],
      $this->generator()->setPortSpeed()
    );
  }

  public function testWipe() {
    $this->portNetwork()
      ->switchPort()
      ->update([
        'name' => 'ge0/0/1',
      ]);
    $this->assertCommandEquals(
      [
        'configure private',
        'set interfaces ge0/0/1 disable',
        'delete vlans default forwarding-options dhcp-security group filter interface ge0/0/1',
        'delete vlans default forwarding-options dhcp-security group trusted interface ge0/0/1',
        'commit',
        'exit',
      ],
      $this->generator()->wipe()
    );
  }

  public function testSetVlanWithNormalVlans() {
    $this->portNetwork()
      ->switchPort()
      ->update([
        'name' => 'ge0/0/1',
      ]);
    $this->portNetwork()
      ->serverPort()
      ->update([
        'mac' => '00:11:22:33:44:55',
      ]);
    $this->portNetwork()
      ->entity()
      ->update([
        'vlan' => '123',
        'gateway' => '1.0.0.2',
        'subnet_mask' => '255.255.255.255',
        'ip' => '1.0.0.1',
      ]);
    $this->portNetwork()
      ->allEntities()[1]
      ->update([
        'vlan' => '123',
        'gateway' => '1.0.0.3',
        'subnet_mask' => '255.255.255.255',
        'ip' => '2.0.0.1',
      ]);
    $this->portNetwork()
      ->allEntities()[2]
      ->update([
        'vlan' => '123',
        'gateway' => '1.0.0.4',
        'subnet_mask' => '255.255.255.255',
        'ip' => '3.0.0.1',
      ]);
    $this->assertCommandEquals(
      [
        'configure private',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 1.0.0.1 mac 00:11:22:33:44:55',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 2.0.0.1 mac 00:11:22:33:44:55',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 3.0.0.1 mac 00:11:22:33:44:55',
        'commit',
        'exit',
      ],
      $this->generator()->setVlan()
    );
  }

  public function testSetVlanWithTaggedVlans() {
    $this->portNetwork()
      ->hub()
      ->update([
        'multi_vlan_logic' => HubMultiVLANLogic::TAGGING,
      ]);
    $this->portNetwork()
      ->switchPort()
      ->update([
        'name' => 'ge0/0/1',
      ]);
    $this->portNetwork()
      ->serverPort()
      ->update([
        'mac' => '00:11:22:33:44:55',
      ]);
    $this->portNetwork()
      ->entity()
      ->update([
        'vlan' => '1',
        'gateway' => '1.0.0.2',
        'subnet_mask' => '255.255.255.255',
        'ip' => '1.0.0.1',
      ]);
    $this->portNetwork()
      ->allEntities()[1]
      ->update([
        'vlan' => '2',
        'gateway' => '1.0.0.3',
        'subnet_mask' => '255.255.255.255',
        'ip' => '2.0.0.1',
      ]);
    $this->portNetwork()
      ->allEntities()[2]
      ->update([
        'vlan' => '3',
        'gateway' => '1.0.0.4',
        'subnet_mask' => '255.255.255.255',
        'ip' => '3.0.0.1',
      ]);
    $this->assertCommandEquals(
      [
        'configure private',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 1.0.0.1 mac 00:11:22:33:44:55',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 2.0.0.1 mac 00:11:22:33:44:55',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 3.0.0.1 mac 00:11:22:33:44:55',
        'commit',
        'exit',
      ],
      $this->generator()->setVlan()
    );
  }

  public function testSetVlanWithCombinedVlans() {
    $this->portNetwork()
      ->hub()
      ->update([
        'multi_vlan_logic' => HubMultiVLANLogic::COMBINE,
      ]);
    $this->portNetwork()
      ->switchPort()
      ->update([
        'name' => 'ge0/0/1',
      ]);
    $this->portNetwork()
      ->serverPort()
      ->update([
        'mac' => '00:11:22:33:44:55',
      ]);
    $this->portNetwork()
      ->entity()
      ->update([
        'vlan' => '1',
        'gateway' => '1.0.0.2',
        'subnet_mask' => '255.255.255.255',
        'ip' => '1.0.0.1',
      ]);
    $this->portNetwork()
      ->allEntities()[1]
      ->update([
        'vlan' => '2',
        'gateway' => '1.0.0.3',
        'subnet_mask' => '255.255.255.255',
        'ip' => '2.0.0.1',
      ]);
    $this->portNetwork()
      ->allEntities()[2]
      ->update([
        'vlan' => '3',
        'gateway' => '1.0.0.4',
        'subnet_mask' => '255.255.255.255',
        'ip' => '3.0.0.1',
      ]);
    $this->assertCommandEquals(
      [
        'configure private',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 1.0.0.1 mac 00:11:22:33:44:55',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 2.0.0.1 mac 00:11:22:33:44:55',
        'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 3.0.0.1 mac 00:11:22:33:44:55',
        'commit',
        'exit',
      ],
      $this->generator()->setVlan()
    );
  }

  public function testSetVlanWithLargeEntity() {
    $this->portNetwork()
      ->switchPort()
      ->update([
        'name' => 'ge0/0/1',
      ]);
    $this->portNetwork()
      ->serverPort()
      ->update([
        'mac' => '00:11:22:33:44:55',
      ]);
    $this->portNetwork()
      ->entity()
      ->update([
        'vlan' => '1',
        'gateway' => '1.0.0.2',
        'subnet_mask' => '255.255.255.255',
        'ip' => '1.0.0.1',
        'range_end' => '1.0.1.255',
        'v6_address' => 'aa::0/64',
      ]);
    $this->portNetwork()->withoutSecondaryEntities();
    $this->assertCommandEquals(
      array_merge(
        ['configure private'],
        array_map(function (int $i) {
          return 'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 1.0.0.' .
            $i .
            ' mac 00:11:22:33:44:55';
        }, range(1, 255)),
        [
          'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ip 1.0.1.0 mac 00:11:22:33:44:55',
          'set vlans default forwarding-options dhcp-security group filter interface ge0/0/1 static-ipv6 aa::0 mac 00:11:22:33:44:55',
          'commit',
          'exit',
        ]
      ),
      $this->generator()->setVlan()
    );
  }
}
