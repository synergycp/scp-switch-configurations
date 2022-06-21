<?php

namespace App\Hub\Type\Cisco;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;

class CiscoIOSPortInterfaceTest extends \App\Test\TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      ['Po1', ['forSwitchCommand' => 'Po1', 'short' => 'Po1', 'long' => 'Po1']],
      [
        'Po10',
        ['forSwitchCommand' => 'Po10', 'short' => 'Po10', 'long' => 'Po10'],
      ],
      [
        'port-channel10',
        [
          'forSwitchCommand' => 'Port-channel10',
          'short' => 'port-channel10',
          'long' => 'port-channel10',
        ],
      ],
      [
        'gi1/2',
        ['forSwitchCommand' => 'Gi1/2', 'short' => 'gi1/2', 'long' => 'gi1/2'],
      ],
      [
        'gi2/24',
        [
          'forSwitchCommand' => 'Gi2/24',
          'short' => 'gi2/24',
          'long' => 'gi2/24',
        ],
      ],
      [
        'Ethernet1/10',
        [
          'forSwitchCommand' => 'Ethernet1/10',
          'short' => 'Ethernet1/10',
          'long' => 'Ethernet1/10',
        ],
      ],
      [
        'Ethernet1/10/20',
        [
          'forSwitchCommand' => 'Ethernet1/10/20',
          'short' => 'Ethernet1/10/20',
          'long' => 'Ethernet1/10/20',
        ],
      ],
      [
        'FastEthernet1',
        [
          'forSwitchCommand' => 'FastEthernet1',
          'short' => 'FastEthernet1',
          'long' => 'FastEthernet1',
        ],
      ],
      ['ethernet', ['exception' => PortFormatInvalid::class]],
      ['vlan1', ['exception' => PortFormatInvalid::class]],
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
    return new CiscoIOSPortInterface($portName);
  }
}
