<?php

namespace App\Hub\Type\Brocade;

class BrocadePortInterfaceTest extends \App\Test\TestCase {
  /**
   * @param $long
   * @param $short
   *
   * @throws \App\Bandwidth\Format\PortFormatInvalid
   * @dataProvider dataLongToShort
   */
  public function testLongToShort(string $long, string $short) {
    $port = new BrocadePortInterface($long);
    $this->assertEquals($short, $port->short());
  }

  public function dataLongToShort(): array {
    return [
      [
        'long' => '40GigabitEthernet1/2/3',
        'short' => '1/2/3',
      ],
      [
        'long' => '10GigabitEthernet1/2/3',
        'short' => '1/2/3',
      ],
      [
        'long' => 'GigabitEthernet1/2/3',
        'short' => '1/2/3',
      ],
      [
        'long' => 'Ethernet1/2/3',
        'short' => '1/2/3',
      ],
      [
        'long' => '40gigabitethernet1/2/3',
        'short' => '1/2/3',
      ],
      [
        'long' => '10GigabitEthernet1/2/3:4',
        'short' => '1/2/3:4',
      ],
    ];
  }
}
