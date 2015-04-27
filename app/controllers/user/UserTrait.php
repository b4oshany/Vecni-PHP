<?php

namespace controllers\user;

trait UserTrait{
  /** @var controllers\user\property\Favourite $favourites Favourite properties of a user */
  public $favourites;

  public function favourites_db(){
    $this->favourites = new property\Favourite($this->user_id);
    return $this->favourites;
  }

}
?>