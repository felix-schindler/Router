<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

readonly class PageProps
{
	/**
	 * @param array<string,string|string[]> $params
	 */
	public function __construct(
		public array $params,
	) {}
}

/**
 * The router, this is where everything is coming to life
 */
readonly class Router
{
	/**
	 * @param Logger $logger Default logger for this class
	 */
	public function __construct(
		private Logger $logger = new Logger("Router"),
	) {
		$stream_handler = new StreamHandler("php://stdout");
		$logger->pushHandler($stream_handler);
	}

	/**
	 * The total main function
	 *
	 * @throws Exception When not on server and not accessing via HTTPS or localhost
	 */
	public function 艳颖(): void
	{
		$method = IO::method();
		$pathname = IO::path();
		$this->logger->debug("{$method} {$pathname}");

		$props = null;
		$baseDir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "routes";
		$filePath = $baseDir . $pathname;
		$controllerPath = $filePath . '+controller.php';
		$viewPath = $filePath . '+view.php';
		$this->logger->debug("Loading controller {$controllerPath}");

		if (file_exists($controllerPath)) {
			include $controllerPath;

			$method = IO::method();
			switch ($method) {
				case "GET":
					if (function_exists("GET")) {
						$props = GET();
					} else {
						$this->logger->debug("Controller does not have {$method} function");
					}
					break;
				case "POST":
					if (function_exists("POST")) {
						$data = POST();
					} else {
						$this->logger->debug("Controller does not have {$method} function");
					}
					break;
				case "PUT":
					if (function_exists("PUT")) {
						$props = PUT();
					} else {
						$this->logger->debug("Controller does not have {$method} function");
					}
					break;
				case "DELETE":
					if (function_exists("DELETE")) {
						$props = DELETE();
					} else {
						$this->logger->debug("Controller does not have {$method} function");
					}
					break;
				default:
					// TODO: Return error
					break;
			}
		}

		$layoutContent = null;
		$layoutPath = $baseDir . DIRECTORY_SEPARATOR . "+layout.php";
		if (file_exists($layoutPath)) {
			ob_start();
			include $layoutPath;

			if (($content = ob_get_clean()) !== false) {
				$layoutContent = $content;
			} else {
				$this->logger->error("Failed to get content from layout");
			}
		} else {
			$this->logger->debug("Layout does not exist");
		}

		$this->logger->debug("Loading view {$viewPath}");
		if (file_exists($viewPath)) {
			include $viewPath;

			if (function_exists("render")) {
				ob_start();
				render($props);
				if (($content = ob_get_clean()) !== false) {
					if ($layoutContent !== null) {
						$content = str_replace("<% SLOT %>", $content, $layoutContent);
					}
					echo $content;
				} else {
					$this->logger->error("Failed to get content from view");
				}
			} else {
				$this->logger->error("View does not have render function");
			}
		} else {
			$this->logger->error("View does not exist");
		}
	}
}
