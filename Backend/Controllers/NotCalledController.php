<?php

class NotCalledController extends Controller
{
	protected function getRoutes(): array {
		return ["/:type/:id"];
	}

	protected function getAccessMethods(): array {
		return ["GET"];
	}

	public function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new TextView($this->getParam("type")));
		if (($id = $this->getParam("id")) !== null) {
			$layout->addChild(new TextView("ID: " . $id));
		}

		$layout->render();
	}
}
