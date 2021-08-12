<?php

class HeadingView extends View
{
  public function __construct(
    private string $title
  ){}

  public function render() : void
  {
    ?>
      <h1><?=$this->title?></h1>
    <?php
  }
}
