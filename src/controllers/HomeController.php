<?php

class HomeController extends Controller
{
	protected array $paths = ['/'];

	protected function execute(): View
	{
		$layout = new LayoutView();
		$layout->addChild(new HeadingView('Home'));
		return $layout;
	}
}
