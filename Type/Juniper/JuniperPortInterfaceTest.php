<?php

namespace App\Hub\Type\Juniper;

use App\Bandwidth\Format\PortFormatInvalid;
use App\Bandwidth\Format\TTestsPortInterface;

class JuniperPortInterfaceTest extends \App\Test\TestCase {
  use TTestsPortInterface;

  public function dataOutputs(): array {
    return [
      ['Po1', ['exception' => PortFormatInvalid::class]],
      [
        'ge1/2/3',
        [
          'forSwitchCommand' => 'ge1/2/3',
          'short' => 'ge1/2/3',
          'long' => 'ge1/2/3',
        ],
      ],
      [
        'ge-0/1/2',
        [
          'forSwitchCommand' => 'ge-0/1/2',
          'short' => 'ge-0/1/2',
          'long' => 'ge-0/1/2',
        ],
      ],
      [
        'mge-0/1/2',
        [
          'forSwitchCommand' => 'mge-0/1/2',
          'short' => 'mge-0/1/2',
          'long' => 'mge-0/1/2',
        ],
      ],
      [
        'xe-0/1/2',
        [
          'forSwitchCommand' => 'xe-0/1/2',
          'short' => 'xe-0/1/2',
          'long' => 'xe-0/1/2',
        ],
      ],
      [
        'ae-0/1/2',
        [
          'forSwitchCommand' => 'ae-0/1/2',
          'short' => 'ae-0/1/2',
          'long' => 'ae-0/1/2',
        ],
      ],
      [
        'et-40/50/100',
        [
          'forSwitchCommand' => 'et-40/50/100',
          'short' => 'et-40/50/100',
          'long' => 'et-40/50/100',
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
    return new JuniperPortInterface($portName);
  }
}
