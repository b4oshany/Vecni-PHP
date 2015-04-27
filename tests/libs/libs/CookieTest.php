<?php

use libs\vecni\libs\Cookie;

class CookieTest extends PHPUnit_Framework_TestCase{

  /**
   * Test the setting of cookie variable.
   * @runInSeparateProcess
   */
  public function test_set_cookie(){
    Cookie::set("name", "oshane", 3000);
    $name = Cookie::get("name");

    $this->assertEquals("oshane", $name);
  }

  /**
   * Test the removal of cookie variable.
   * @depends test_set_cookie
   * @runInSeparateProcess
   */
  public function test_remove_cookie(){
      Cookie::remove("name");
      $name = Cookie::get("name");

      $this->assertNotEquals("oshane", $name);
  }

}

?>