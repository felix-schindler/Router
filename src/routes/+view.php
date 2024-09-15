<?php
/**
 * @param null $data
 * @return void
 */
function render($data): void
{
	?>
<h1>Works!</h1>
<p>Data: <?= print_r($data) ?></p>
<?php
}
?>
