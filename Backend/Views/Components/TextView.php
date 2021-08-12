<?php

class TextView extends View
{
  public function __construct(
    private string $text
  ){}

  public function render() : void
  {
    ?>
      <p><?=$this->text?></p>
    <?php
  }
}
