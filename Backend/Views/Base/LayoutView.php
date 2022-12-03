<?php

class LayoutView extends View
{
	public function render(): void
	{
?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title><?= TITLE ?></title>
			<style>
				html {
					--sans: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
					--mono: ui-monospace, SFMono-Regular, SF Mono, Menlo, Consolas, Liberation Mono, monospace;

					color-scheme: dark light;
					font-family: var(--sans);
				}

				body {
					display: grid;
					grid-template-rows: auto 1fr auto;
					min-height: 100vh;
					margin: 0;
				}

				body>header,
				body>footer {
					padding: 1em;
				}

				body>main {
					padding: 0 1em;
				}

				body>footer {
					text-align: center;
				}

				body>header> :first-child,
				body>main> :first-child,
				body>footer> :first-child {
					margin-top: 0;
				}

				body>header> :last-child,
				body>main> :last-child,
				body>footer> :last-child {
					margin-bottom: 0;
				}
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
				<?php foreach ($_SERVER as $key => $value) : ?>
					<p><?= $key ?>: <?= $value ?></p>
				<?php endforeach; ?>

				<?php $this->renderChildren(); ?>
			</main>

			<footer>
				<p>This page was generated in <?= (microtime(true) - $GLOBALS['start']) * 1000 ?> ms</p>
			</footer>
		</body>

		</html>
<?php
	}
}
