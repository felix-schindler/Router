<?php

class NotCalledController extends Controller
{
	protected array $paths = ['/:type/:id'];

	protected function execute(): View
	{
		$layout = new LayoutView();
		$layout->addChild(new HeadingView('Doesn\'t get called when only using links from the LayoutView'));
		$layout->addChild(new TextView('Go and work on the Router!'));
		$layout->addChild(new TextView($this->param('type') ?? 'null'));
		if (($id = $this->param('id')) !== null)
			$layout->addChild(new TextView("ID: {$id}"));

		return $layout;
	}
}
