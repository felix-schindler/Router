<?php

class HomeController extends Controller
{
	protected function getRoutes(): array {
		return ["/"];
	}

	public function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new HeadingView("Home"));
		$layout->render();
	}
}
