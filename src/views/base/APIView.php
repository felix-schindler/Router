<?php

class APIView extends View
{
	/**
	 * @param array<mixed>|null $data JSON data to be displayed
	 * @param int $maxAge Cache max age (default: 5 minutes)
	 */
	public function __construct(
		public ?array $data = null,
		private readonly int $maxAge = 300
	) {
	}

	public function render(): void
	{
		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: ' . DOMAIN);
		header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept');
		header('Access-Control-Max-Age: ' . $this->maxAge);

		echo json_encode($this->data);
	}
}
