<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type;

use Throwable;

class TypeNotFoundException extends \Exception {
  public function __construct(
    string $slug = "",
    int $code = 0,
    Throwable $previous = null
  ) {
    parent::__construct('Switch type not found: ' . $slug, $code, $previous);
  }
}
