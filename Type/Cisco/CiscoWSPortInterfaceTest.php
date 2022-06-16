<?php

namespace App\Hub\Type\Cisco;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;

class CiscoWSPortInterfaceTest extends \App\Test\TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      ['Po1', ['forSwitchCommand' => 'Po1', 'short' => 'po1', 'long' => 'po1']],
      [
        'Po10',
        ['forSwitchCommand' => 'Po10', 'short' => 'po10', 'long' => 'po10'],
      ],
      [
        'Gi1/2/3',
        [
          'forSwitchCommand' => 'Gi1/2/3',
          'short' => 'gi1/2/3',
          'long' => 'gi1/2/3',
        ],
      ],
      [
        'Gi2/3/33',
        [
          'forSwitchCommand' => 'Gi2/3/33',
          'short' => 'gi2/3/33',
          'long' => 'gi2/3/33',
        ],
      ],
      ['gi1', ['forSwitchCommand' => 'Gi1', 'short' => 'gi1', 'long' => 'gi1']],
      [
        'gigabitethernet1',
        [
          'forSwitchCommand' => 'Gigabitethernet1',
          'short' => 'gigabitethernet1',
          'long' => 'gigabitethernet1',
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
    return new CiscoWSPortInterface($portName);
  }
}
