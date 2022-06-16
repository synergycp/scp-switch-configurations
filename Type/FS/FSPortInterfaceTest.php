<?php

namespace App\Hub\Type\FS;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;

class FSPortInterfaceTest extends \App\Test\TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      [
        'Gi1/2/3',
        [
          'exception' => PortFormatInvalid::class,
        ],
      ],
      ['gi1', ['exception' => PortFormatInvalid::class]],
      [
        'eth-0-1',
        [
          'forSwitchCommand' => 'eth-0-1',
          'short' => 'eth-0-1',
          'long' => 'eth-0-1',
        ],
      ],
      [
        'eth-1-2',
        [
          'forSwitchCommand' => 'eth-1-2',
          'short' => 'eth-1-2',
          'long' => 'eth-1-2',
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
    return new FSPortInterface($portName);
  }
}
