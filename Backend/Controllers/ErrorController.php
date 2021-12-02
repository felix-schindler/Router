<?php

class ErrorController extends Controller
{
	public function execute(): void {
		(new LayoutView())->addChild(new ErrorView())->render();
	}
}
