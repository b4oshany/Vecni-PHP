<?php

use libs\vecni\http\Response;

class ResponseTest extends PHPUnit_Framework_TestCase{

  /**
   * Check if the json response is ok.
   * @runInSeparateProcess
   */
  public function test_json_response(){
      $ar = array("framework"=>"Vecni", "version"=>"0.6.1");
      $json = Response::json_response(200, $ar);
      $this->assertEquals(json_encode(array("status"=>200, "message"=>$ar)), $json);

  }

  /**
   * Check if the json feed is match.
   * @runInSeparateProcess
   */
  public function test_json_feed(){
      $ar = array("framework"=>"Vecni", "version"=>"0.6.1");
      $fjson = Response::json_feed($ar);

      $this->assertEquals(json_encode($ar), $fjson);
  }

}

?>