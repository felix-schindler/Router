<?php

class LayoutView extends View
{
	public function render(): void {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Title</title>
	<base href="/">
	<style>
		:root {
			--on-bg: #000;
			--bg: #fff;

			color-scheme: dark light;
			font-family: system-ui;
			color: var(--on-bg);
			background-color: var(--bg);
		}
		@media screen and (prefers-color-scheme: dark) { :root { --on-bg: #fff; --bg: #151515; } }
	</style>
</head>
<body>
	<header>
		<a href="/">Home</a>
		<a href="/article/2">Other one</a>
		<a href="/article/321">Should not be called</a>
		<a href="/api/sample">API</a>
		<a href="/err">Error</a>
	</header>

	<main>
		<?php $this->renderChildren(); ?>
	</main>

	<footer>

	</footer>
</body>
</html>
<?php
    }
}
