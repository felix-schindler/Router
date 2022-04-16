<?php

class HomeController extends Controller
{
	protected array $paths = ['/', '/:id'];
	protected array $methods = ['*'];

	protected function execute(): void {
		// $layout = new LayoutView();
		// $layout->addChild(new HeadingView("Home"));

		// Query
		echo "PARAM<br>";
		echo $this->param("id") . "<br>";
		echo $this->param("not_defined") ?? "not set" . "<br>";

		echo "<br>QUERY<br>";
		echo IO::query("str") . "<br>";			// String
		print_r(IO::query("arr"));				// Array
		echo "<br>";
		echo IO::query("not_defined") ?? "not set" . "<br>";	// Not set

		// Body
		echo "<br>BODY<br>";
		echo IO::body("str") . "<br>";
		print_r(IO::body("arr"));
		echo "<br>";
		echo IO::body("not_defined") ?? "not set" . "<br>";

		// $layout->render();
	}
}
