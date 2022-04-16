<?php

class HomeController extends Controller
{
	protected array $paths = ['/'];

	protected function execute(): void {
		$layout = new LayoutView();
		$layout->addChild(new HeadingView("Home"));
		$layout->render();
	}
}
