<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Arista\EOS;

use App\Hub\Format\TTestsFormat;
use App\Hub\HubMultiVLANLogic;
use App\Test\TestCase;

class EOSv4FormatTest extends TestCase {
  use TTestsFormat;

  protected function switchTypeSlug(): string {
    return EOSv4Type::SLUG;
  }

  public function testSetPortSpeed() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'ethernet1',
    ]);
    $this->assertCommandEquals(
      [
        'en',
        'enable_pass',
        'conf',
        'interface ethernet1',
        'speed forced 1000full',
        'exit',
        'no command',
        'exit',
      ],
      $this->generator()->setPortSpeed()
    );
  }

  public function testSetVlanWithNormalVlans() {
    $this->portNetwork()->switchPort()->update([
      'name' => 'ethernet1',
    ]);
    $this->portNetwork()->entity()->update([
      'vlan' => '123',
      'gateway' => '1.0.0.2',
      'subnet_mask' => '255.255.255.255',
    ]);
    $this->assertCommandEquals([
      'en',
      'enable_pass',
      'conf',
      'interface ethernet1',
      'switchport access vlan 123',
      'exit',
      'no command',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithTaggedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::TAGGING,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'ethernet1',
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
      'enable_pass',
      'conf',
      'interface ethernet1',
      'switchport access vlan 123',
      'exit',
      'interface ethernet1',
      'switchport access vlan 2',
      'exit',
      'interface ethernet1',
      'switchport access vlan 3',
      'exit',
      'no command',
      'exit',
    ], $this->generator()->setVlan());
  }

  public function testSetVlanWithCombinedVlans() {
    $this->portNetwork()->hub()->update([
      'multi_vlan_logic' => HubMultiVLANLogic::COMBINE,
    ]);
    $this->portNetwork()->switchPort()->update([
      'name' => 'ethernet1',
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
      'enable_pass',
      'conf',
      'interface ethernet1',
      'switchport access vlan 123',
      'exit',
      'interface ethernet1',
      'switchport access vlan 123',
      'exit',
      'interface ethernet1',
      'switchport access vlan 123',
      'exit',
      'no command',
      'exit',
    ], $this->generator()->setVlan());
  }
}
