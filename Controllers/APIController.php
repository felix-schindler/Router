<?php

class APIController extends Controller
{
	protected array $paths = ['/api/sample'];
	protected array $methods = ['POST'];

	protected function execute(): View {
		return new APIView();
	}
}
