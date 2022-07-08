<?php

class SecondHome extends Controller
{
	protected array $paths = ['/article/:id'];

	protected function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new TextView('Welcome to another route'));
		if (($id = $this->param('id')) !== null) {
			$layout->addChild(new TextView("ID: $id"));
		}

		$layout->render();
	}
}
