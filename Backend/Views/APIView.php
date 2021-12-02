<?php

class APIView extends View
{
	/**
	 * @param array<mixed>|null $data
	 * @param boolean $success Whether the request was successfull or not
	 * @param integer $code (HTTP) code for the request
	 * @param string $message Request answer
	 */
	public function __construct(
		public ?array $data = null,
		public bool $success = true,
		public int $code = 200,
		public string $message = "OK!"
	){}

	public function render(): void {
		header("Content-type: application/json; charset=utf-8");
		header("Access-Control-Allow-Origin: " . DOMAIN);
		// header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
		// header("Access-Control-Max-Age: 86400");

		echo json_encode(
			[
				"status" => [
					"success" => $this->success,
					"code" => $this->code,
					"message" => $this->message
				],
				"data" => $this->data
			],
		);
	}
}
