<?php

class SecondHome extends Controller
{
	protected function getRoutes() : array
	{
		return ["/article/:id"];
	}

	protected function getAccessMethods() : array
	{
		return ["GET"];
	}

	public function execute() : void
	{
		$layout = new LayoutView();
		$layout->addChild(new TextView("Welcome to another route"));
		if (($id = $this->getParam("id")) !== null) {
			$layout->addChild(new TextView("ID: " . $id));
		}

		$layout->render();
	}
}
