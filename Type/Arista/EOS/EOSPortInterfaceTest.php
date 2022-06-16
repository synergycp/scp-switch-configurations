<?php

namespace App\Hub\Type\Arista\EOS;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;
use App\Test\TestCase;

class EOSPortInterfaceTest extends TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      [
        'ethernet1',
        [
          'forSwitchCommand' => 'ethernet1',
          'short' => 'ethernet1',
          'long' => 'ethernet1',
        ],
      ],
      [
        'ethernet48',
        [
          'forSwitchCommand' => 'ethernet48',
          'short' => 'ethernet48',
          'long' => 'ethernet48',
        ],
      ],
      [
        'ethernet49/1',
        [
          'forSwitchCommand' => 'ethernet49/1',
          'short' => 'ethernet49/1',
          'long' => 'ethernet49/1',
        ],
      ],
      [
        'Port-Channel1',
        [
          'forSwitchCommand' => 'port-channel1',
          'short' => 'port-channel1',
          'long' => 'port-channel1',
        ],
      ],
      [
        'Vlan1001',
        [
          'forSwitchCommand' => 'vlan1001',
          'short' => 'vlan1001',
          'long' => 'vlan1001',
        ],
      ],
      ['ethernet', ['exception' => PortFormatInvalid::class]],
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
    return new EOSPortInterface($portName);
  }
}
