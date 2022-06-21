<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Mikrotik;

use App\Hub\Format\TTestsFormat;
use App\Hub\HubMultiVLANLogic;
use App\Test\TestCase;

class MikrotikFormatTest extends TestCase {
  use TTestsFormat;

  protected function switchTypeSlug(): string {
    return MikrotikType::SLUG;
  }

  public function testSetPortSpeed() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'Ether15',
    ]);
    $this->assertCommandEquals(
      [
        'enable_pass',
        '',
        '/interface Ethernet set ether15 speed=1Gbps',
      ],
      $this->generator()->setPortSpeed()
    );
  }

  public function testSetVlanWithNormalVlans() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'Ether11',
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->assertCommandEquals([
      'enable_pass',
      '',
      '/interface bridge port add bridge=bridge1 interface=ether11 pvid=123',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithTaggedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::TAGGING,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'Ether11',
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
      'enable_pass',
      '',
      '/interface bridge port add bridge=bridge1 interface=ether11 pvid=123',
      '/interface bridge vlan add bridge=bridge1 tagged=ether11 vlan-ids=2',
      '/interface bridge vlan add bridge=bridge1 tagged=ether11 vlan-ids=3',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithCombinedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::COMBINE,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'Ether12',
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
      'enable_pass',
      '',
      '/interface bridge port add bridge=bridge1 interface=ether12 pvid=123',
      '/interface bridge port add bridge=bridge1 interface=ether12 pvid=123',
      '/interface bridge port add bridge=bridge1 interface=ether12 pvid=123',
    ], $this->generator()->setVlan());
  }
}
