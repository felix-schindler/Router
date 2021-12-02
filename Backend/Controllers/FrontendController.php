<?php

class FrontendController extends Controller
{
	public function execute() : void
	{
		(new FrontendView())->render();
	}
}
