<?php

namespace App\Hub\Type\Supermicro;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;
use App\Test\TestCase;

class SupermicroPortInterfaceTest extends TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      [
        'Ethernet Interface Port 01',
        [
          'forSwitchCommand' => 'gigabitethernet 0/1',
          'short' => 'ethernet interface port 01',
          'long' => 'ethernet interface port 01',
        ],
      ],
      [
        'Ethernet Interface Port 48',
        [
          'forSwitchCommand' => 'gigabitethernet 0/48',
          'short' => 'ethernet interface port 48',
          'long' => 'ethernet interface port 48',
        ],
      ],
      [
        'Gi0/1',
        ['forSwitchCommand' => 'gi0/1', 'short' => 'gi0/1', 'long' => 'gi0/1'],
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
    return new SupermicroPortInterface($portName);
  }
}
