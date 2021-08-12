<?php

/**
 * Base class of all views
 */
abstract class View
{
  /**
   * @var array<View> Children that can be rendered
   */
  private array $children = [];

  /**
   * Renders HTML
   */
  abstract public function render() : void;

  /**
   * Add a child
   *
   * @param View $child A child
   * @return View Current view
   */
  public function addChild(View $child) : View
  {
    $this->children[] = $child;
    return $this;
  }

  /**
   * Render children
   */
  public function renderChildren() : void
  {
    foreach ($this->children as $child)
      $child->render();
  }

  /**
   * Get rendered HTML as string
   */
  public function __toString() : string
  {
      ob_start();
      $this->render();
      return ob_get_clean();
  }
}
