<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type\Brocade;

use App\Bandwidth\Format;

class BrocadePortInterface extends Format\PortInterface {
  protected $primaryPrefixes = ['ethernet1/2', '40GigabitEthernet'];

  protected $aggregateRegex = '/(ae)([0-9]+)/';

  /**
   * @return string
   */
  public function short() {
    return preg_replace('/([0-9]*(gigabit)?ethernet)/', '', parent::short());
  }
}
