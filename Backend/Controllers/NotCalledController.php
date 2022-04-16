<?php

class NotCalledController extends Controller
{
	protected array $paths = ['/:type/:id'];

	public function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new TextView($this->param("type")));
		if (($id = $this->param("id")) !== null) {
			$layout->addChild(new TextView("ID: " . $id));
		}

		$layout->render();
	}
}
