<?php

class FrontendController extends Controller
{
	protected function getRoutes() : array
	{
		return [];
	}

	protected function userRequired() : bool
	{
		return false;
	}

	public function execute() : void
	{
		(new FrontendView())->render();
	}
}
