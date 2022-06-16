<?php

namespace App\Hub\Type\Mikrotik;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;
use App\Test\TestCase;

class MikrotikPortInterfaceTest extends TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      [
        'Ether15',
        [
          'forSwitchCommand' => 'ether15',
          'short' => 'ether15',
          'long' => 'ether15',
        ],
      ],
      [
        'ether15',
        [
          'forSwitchCommand' => 'ether15',
          'short' => 'ether15',
          'long' => 'ether15',
        ],
      ],
      ['Ether', ['exception' => PortFormatInvalid::class]],
      ['Ethernet', ['exception' => PortFormatInvalid::class]],
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
    return new MikrotikPortInterface($portName);
  }
}
