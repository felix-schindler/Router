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
	abstract public function render(): void;

	/**
	 * Add a child
	 *
	 * @param View $child A child
	 * @return View Current view
	 */
	public function addChild(View $child): View
	{
		$this->children[] = $child;
		return $this;
	}

	/**
	 * Add multiple children
	 *
	 * @param View ...$children Children
	 * @return View Current view
	 */
	public function addChildren(View ...$children): View
	{
		$this->children = array_merge($this->children, $children);
		return $this;
	}

	/**
	 * Get all children
	 *
	 * @return array<View> Children
	 * @see View::$children
	 */
	public function getChildren(): array
	{
		return $this->children;
	}

	/**
	 * Render children
	 */
	public function renderChildren(?string $element = null): void
	{
		foreach ($this->children as $child) {
			if ($element === null)
				$child->render();
			else
				echo "<$element>{$child->__toString()}</$element>";
		}
	}

	/**
	 * Get rendered (HTML) View as string
	 */
	public function __toString(): string
	{
		ob_start();
		$this->render();
		if (($content = ob_get_clean()) !== false)
			return $content;
		return '';
	}
}
