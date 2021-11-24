<?php

class FrontendView extends View
{
	public function render() : void
	{
		include __DIR__ . "/../../index.html";
	}
}
