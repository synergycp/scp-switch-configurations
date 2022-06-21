<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type;

use App\Api\Transformer;
use App\Hub\Format\FormatService;
use Illuminate\Database\Eloquent;

class TypeTransformer extends Transformer {
  /**
   * @var TypeService
   */
  protected $type;

  /**
   * TypeTransformer constructor.
   *
   * @param TypeService $type
   */
  public function boot(TypeService $type) {
    $this->type = $type;
  }

  /**
   * @param Type $item
   *
   * @return array
   * @throws TypeNotFoundException
   */
  public function item(Type $item) {
    return [
      'slug' => $item->getSlug(),
      'name' => $this->type->getName($item->getSlug()),
    ];
  }

  /**
   * @param Hub $item
   *
   * @return array
   * @throws TypeNotFoundException
   */
  public function resource(Hub $item) {
    return $this->item($item);
  }
}
