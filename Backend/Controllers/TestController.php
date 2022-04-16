<?php

class TestController extends Controller
{
	protected array $paths = ['/test'];

	protected function execute(): void
	{
		print_r($_GET["arr"]);
		echo "<br>";
		print_r(IO::query("arr"));

		?>
		<form action="/test" method="get">
			<input type="checkbox" name="arr[]" value="Check 1">
			<input type="checkbox" name="arr[]" value="Check 2">
			<input type="submit">
		</form>
		<?php
	}
}
