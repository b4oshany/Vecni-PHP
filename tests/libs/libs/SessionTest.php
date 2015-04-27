<?php

use libs\vecni\libs\Session;

class SessionTest extends PHPUnit_Framework_TestCase{

  /**
   * Test the setting of session variable.
   * @runInSeparateProcess
   */
  public function test_set_session(){
    Session::set("name", "oshane");
    $name = Session::get("name");

    $this->assertEquals("oshane", $name);
  }

  /**
   * Test the removal of session variable.
   * @depends test_set_session
   * @runInSeparateProcess
   */
  public function test_remove_session(){
      Session::remove("name");
      $name = Session::get("name");

      $this->assertNotEquals("oshane", $name);
  }

}

?>