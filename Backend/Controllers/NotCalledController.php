<?php

class NotCalledController extends Controller
{
	protected array $paths = ['/:type/:id'];

	public function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new TextView($this->getParam("type")));
		if (($id = $this->getParam("id")) !== null) {
			$layout->addChild(new TextView("ID: " . $id));
		}

		$layout->render();
	}
}
