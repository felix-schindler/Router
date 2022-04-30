<?php

class NotCalledController extends Controller
{
	protected array $paths = ['/:type/:id'];

	protected function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new HeadingView("Doesn't get called when only using links from the LayoutView"));
		$layout->addChild(new TextView("Go and work on the Router!"));
		$layout->addChild(new TextView($this->param("type")));
		if (($id = $this->param("id")) !== null)
			$layout->addChild(new TextView("ID: " . $id));

		$layout->render();
	}
}
