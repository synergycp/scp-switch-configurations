<?php

namespace App\Hub\Type\DellNetworking;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;
use App\Test\TestCase;

class DellNetworkingPortInterfaceTest extends TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      [
        'te-0/1/2',
        [
          'forSwitchCommand' => 'Te0/1/2',
          'short' => 'te-0/1/2',
          'long' => 'Unit: 0 Slot: 1 Port: 2 10G - Level',
        ],
      ],
      [
        'gi-1/2/3',
        [
          'forSwitchCommand' => 'Gi1/2/3',
          'short' => 'gi-1/2/3',
          'long' => 'Unit: 1 Slot: 2 Port: 3 Gigabit - Level',
        ],
      ],
      [
        'TenGigabitEthernet 1/2',
        [
          'forSwitchCommand' => 'TenGigabitEthernet1/2',
          'short' => 'TenGigabitEthernet 1/2',
          'long' => 'TenGigabitEthernet 1/2',
        ],
      ],
      [
        'fortyGigE 1/2',
        [
          'forSwitchCommand' => 'FortyGigE1/2',
          'short' => 'fortyGigE 1/2',
          'long' => 'fortyGigE 1/2',
        ],
      ],
      [
        'Port-channel 10',
        [
          'forSwitchCommand' => 'Port-channel10',
          'short' => 'Port-channel 10',
          'long' => 'Port-channel 10',
        ],
      ],
      [
        'Vlan 10',
        [
          'forSwitchCommand' => 'Vlan10',
          'short' => 'Vlan 10',
          'long' => 'Vlan 10',
        ],
      ],
      ['Unit', ['exception' => PortFormatInvalid::class]],
    ];
  }

  /**
   * @param string $portName
   *
   * @return \App\Control\Rtg\PortInterface
   * @throws \App\Bandwidth\Format\PortFormatInvalid
   */
  protected function getPortInterface(
    string $portName
  ): \App\Control\Rtg\PortInterface {
    return new DellNetworkingPortInterface($portName);
  }
}
