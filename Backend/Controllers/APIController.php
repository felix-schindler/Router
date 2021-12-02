<?php

class APIController extends Controller
{
	protected array $paths = ['/api'];
	protected array $methods = ['POST'];

	public function execute(): void {
		(new APIView())->render();
	}
}
