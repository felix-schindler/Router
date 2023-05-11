<?php

class ArticleController extends Controller
{
	protected array $paths = ['/article/:id'];

	protected function execute(): View
	{
		$layout = new LayoutView();
		$layout->addChild(new TextView('Welcome to another route'));
		if (($id = $this->param('id')) !== null) {
			$layout->addChild(new TextView("ID: $id"));
		}

		return $layout;
	}
}
