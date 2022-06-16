<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Juniper;

use App\Hub\Format\TTestsFormat;
use App\Hub\HubMultiVLANLogic;
use App\Test\TestCase;

class JuniperELSFormatTest extends TestCase {
  use TTestsFormat;

  protected function switchTypeSlug(): string {
    return JuniperELSType::SLUG;
  }

  public function testSetPortSpeed() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge0/0/1',
    ]);
    $this->assertCommandEquals(
      [
        'configure private',
        'set interfaces ge0/0/1 speed 1g',
        'commit',
        'exit',
      ],
      $this->generator()->setPortSpeed()
    );
  }

  public function testSetVlanWithNormalVlans() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge0/0/1',
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->assertCommandEquals([
      'configure private',
      'delete interfaces ge0/0/1 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.123 vlan-id 123',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching vlan members 123',
      'commit',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithTaggedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::TAGGING,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge0/0/1',
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
      'configure private',
      'delete interfaces ge0/0/1 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.123 vlan-id 123',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching vlan members 123',
      'delete interfaces ge0/0/1 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.2 vlan-id 2',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching vlan members 2',
      'delete interfaces ge0/0/1 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.3 vlan-id 3',
      'set interfaces ge0/0/1 unit 0 family ethernet-switching vlan members 3',
      'commit',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithCombinedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::COMBINE,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'ge0/0/2',
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
      'configure private',
      'delete interfaces ge0/0/2 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/2 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.123 vlan-id 123',
      'set interfaces ge0/0/2 unit 0 family ethernet-switching vlan members 123',
      'delete interfaces ge0/0/2 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/2 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.123 vlan-id 123',
      'set interfaces ge0/0/2 unit 0 family ethernet-switching vlan members 123',
      'delete interfaces ge0/0/2 unit 0 family ethernet-switching vlan members',
      'set interfaces ge0/0/2 unit 0 family ethernet-switching interface-mode access',
      'set vlans vlan.123 vlan-id 123',
      'set interfaces ge0/0/2 unit 0 family ethernet-switching vlan members 123',
      'commit',
      'exit',
    ], $this->generator()->setVlan());
  }
}
