<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type;

class TypeService {
  /**
   * @var Type[]
   */
  protected $types = [];

  /**
   * @param Type $type
   *
   * @return $this
   */
  public function add(Type $type) {
    $this->types[$type->getSlug()] = $type;

    return $this;
  }

  /**
   * @param string $slug
   *
   * @return Type
   * @throws TypeNotFoundException
   */
  public function get($slug) {
    if (!isset($this->types[$slug])) {
      throw new TypeNotFoundException($slug);
    }

    return $this->types[$slug];
  }

  /**
   * @param string $slug
   *
   * @return string
   * @throws TypeNotFoundException
   */
  public function getName($slug) {
    return trans($this->get($slug)->getLang() . '.name');
  }

  /**
   * @return Type[]
   */
  public function all() {
    return array_values($this->types);
  }

  /**
   * @return string[]
   */
  public function allowed() {
    return array_keys($this->types);
  }

  // This is bound to a singleton using Laravel currently
  public static function instance(): self {
    return app(static::class);
  }
}
