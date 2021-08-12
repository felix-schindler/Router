<?php

class ErrorController extends Controller
{
	protected function getRoutes() : array
	{
		return [];
	}

	public function execute() : void
	{
		(new LayoutView())->addChild(new ErrorView())->render();
	}
}
