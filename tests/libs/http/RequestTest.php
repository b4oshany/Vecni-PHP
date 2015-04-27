<?php

use libs\vecni\http\Request;

class RequestTest extends PHPUnit_Framework_TestCase{

  public function test_get_request(){
        $_GET["simple"] = "<1321></1321>";
        $_GET["simple2"] = "13211321";
        $_GET["name"] = "Oshane";
        $_GET["squote"] = "Oshane's hat";
        $_GET["dquote"] = 'Oshane asked "Hi, how are you?"';

        $simple = Request::GET("simple");
        $simple2 = Request::GET("simple2");
        $name = Request::GET("name");
        $squote = Request::GET("squote");
        $dquote = Request::GET("dquote");

        # The get request is encodes special html characters to ASCII format.
        $this->assertEquals("&lt;1321&gt;&lt;/1321&gt;", $simple);
        $this->assertEquals("13211321", $simple2);
        $this->assertEquals("Oshane", $name);
        $this->assertEquals("Oshane's hat", $squote);
        $this->assertEquals('Oshane asked "Hi, how are you?"', $dquote);
  }

  public function test_post_request(){
        $_POST["simple"] = "<?php echo 1321 ?>";
        $_POST["simple2"] = "13211321";
        $_POST["name"] = "Oshane";
        $_POST["squote"] = "Oshane's hat";
        $_POST["dquote"] = 'Oshane asked "Hi, how are you?"';

        $simple = Request::POST("simple");
        $simple2 = Request::POST("simple2");
        $name = Request::POST("name");
        $squote = Request::POST("squote");
        $dquote = Request::POST("dquote");

        # The get request is encodes special html characters to ASCII format.
        $this->assertEquals("&lt;?php echo 1321 ?&gt;", $simple);
        $this->assertEquals("13211321", $simple2);
        $this->assertEquals("Oshane", $name);
        $this->assertEquals("Oshane's hat", $squote);
        $this->assertEquals('Oshane asked "Hi, how are you?"', $dquote);
  }
}

?>