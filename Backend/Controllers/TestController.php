<?php

class TestController extends Controller
{
	protected array $paths = ['/test', '/test/:id'];
	protected array $methods = ['*'];

	protected function execute(): void
	{
		// Query
		echo 'PARAM<br>';
		echo $this->param('id');
		echo '<br>';
		echo $this->param('not_defined') ?? 'not set';
		echo '<br>';

		echo '<br>QUERY<br>';
		echo IO::query('str');						// String
		echo '<br>';
		print_r(IO::query('arr'));				// Array
		echo '<br>';
		echo IO::query('not_defined') ?? 'not set';	// Not set
		echo '<br>';

		// Body
		echo '<br>BODY<br>';
		echo IO::body('str');
		echo '<br>';
		print_r(IO::body('arr'));
		echo '<br>';
		echo IO::body('not_defined') ?? 'not set';
		echo '<br>';
	}
}
