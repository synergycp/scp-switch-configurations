<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Cisco;

use App\Hub\Format\TTestsFormat;
use App\Hub\HubLayerType;
use App\Hub\HubMultiVLANLogic;
use App\Test\TestCase;

class CiscoIOSFormatTest extends TestCase {
  use TTestsFormat;

  protected function switchTypeSlug(): string {
    return CiscoIOSType::SLUG;
  }

  public function testSetPortSpeed() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge-0/0/2',
    ]);
    $this->assertCommandEquals(
      [
        'en',
        '',
        'config t',
        'interface Ge-0/0/2',
        'speed auto 1000',
        'exit',
        'exit',
        'copy running-config startup-config',
        'exit',
      ],
      $this->generator()->setPortSpeed()
    );
  }

  public function testSetVlanWithNormalVlans() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge-0/0/2',
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->assertCommandEquals([
      'en',
      '',
      'config t',
      'interface Ge-0/0/2',
      'switchport access vlan 123',
      'exit',
      'interface vlan 123',
      'ip address 1.0.0.2 255.255.255.255',
      'exit',
      'exit',
      'copy running-config startup-config',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithTaggedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::TAGGING,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge-0/0/2',
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->portNetwork()->allEntities()[1]->update([
      'vlan' => '2',
      'gateway' => '1.0.0.3',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->portNetwork()->allEntities()[2]->update([
      'vlan' => '3',
      // When gateway is missing, it falls back to IPv4 address
      'ip' => '2.0.0.1',
      'gateway' => '',
      'subnet_mask' => '255.255.255.240',
    ]);
    $this->assertCommandEquals([
      'en',
      '',
      'config t',
      'interface Ge-0/0/2',
      'switchport access vlan 123',
      'exit',
      'interface vlan 123',
      'ip address 1.0.0.2 255.255.255.255',
      'exit',
      'interface Ge-0/0/2',
      'switchport access vlan 2',
      'exit',
      'interface vlan 2',
      'ip address 1.0.0.3 255.255.255.255',
      'exit',
      'interface Ge-0/0/2',
      'switchport access vlan 3',
      'exit',
      'interface vlan 3',
      'ip address 2.0.0.1 255.255.255.240',
      'exit',
      'exit',
      'copy running-config startup-config',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithCombinedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::COMBINE,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge-0/0/2',
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->portNetwork()->allEntities()[1]->update([
      'vlan' => '2',
      'gateway' => '1.0.0.3',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->portNetwork()->allEntities()[2]->update([
      'vlan' => '3',
      // When gateway is missing, it falls back to IPv4 address
      'ip' => '2.0.0.1',
      'gateway' => '',
      'subnet_mask' => '255.255.255.240',
    ]);
    $this->assertCommandEquals([
      'en',
      '',
      'config t',
      'interface Ge-0/0/2',
      'switchport access vlan 123',
      'exit',
      'interface vlan 123',
      'ip address 1.0.0.2 255.255.255.255',
      'exit',
      'interface Ge-0/0/2',
      'switchport access vlan 123',
      'exit',
      'interface vlan 123',
      'ip address 1.0.0.3 255.255.255.255',
      'exit',
      'interface Ge-0/0/2',
      'switchport access vlan 123',
      'exit',
      'interface vlan 123',
      'ip address 2.0.0.1 255.255.255.240',
      'exit',
      'exit',
      'copy running-config startup-config',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testDistSetVlan() {
    $this->portNetwork()->hub()->update([
      'ipv4_address' => '10.0.0.5',
      'layer' => HubLayerType::DISTRIBUTION,
      'multi_vlan_logic' => HubMultiVLANLogic::COMBINE,
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.248',
    ]);
    $this->portNetwork()->allEntities()[1]->update([
      'vlan' => '2',
      'gateway' => '1.0.0.3',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->portNetwork()->allEntities()[2]->update([
      'vlan' => '3',
      // When gateway is missing, it falls back to IPv4 address
      'ip' => '2.0.0.1',
      'gateway' => '',
      'subnet_mask' => '255.255.255.240',
    ]);
    $this->assertCommandEquals([
      'en',
      '',
      'config t',
      'ip route 1.0.0.2/29 10.0.0.5',
      'ip route 1.0.0.3/32 10.0.0.5',
      'ip route 2.0.0.1/28 10.0.0.5',
      'exit',
      'copy running-config startup-config',
      'exit',
    ], $this->generator()->setVlan());
  }
}
