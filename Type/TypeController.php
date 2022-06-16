<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type;

use App\Api\Controller;

class TypeController extends Controller {
  /**
   * @var TypeService
   */
  protected $type;

  /**
   * @var TypeTransformer
   */
  protected $transform;

  /**
   * TypeController constructor.
   *
   * @param TypeService     $type
   * @param TypeTransformer $transform
   */
  public function boot(TypeService $type, TypeTransformer $transform) {
    $this->type = $type;
    $this->transform = $transform;
  }

  public function index() {
    $items = $this->transform->all(collection($this->type->all()));
    return response()->api($items);
  }
}
