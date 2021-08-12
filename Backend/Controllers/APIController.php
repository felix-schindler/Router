<?php

class APIController extends Controller
{
	protected function getRoutes() : array
	{
		return ["/api"];
	}

	public function execute() : void
	{
		(new APIView())->render();
	}
}
