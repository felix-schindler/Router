<?php

class APIController extends Controller
{
	protected array $paths = ['/api/sample'];
	protected array $methods = ['POST'];

	public function execute(): void {
		(new APIView())->render();
	}
}
