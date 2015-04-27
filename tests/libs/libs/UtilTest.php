<?php


class UtilTest extends PHPUnit_Framework_TestCase{
    
    /**
     * Test remove extra slashes.
     */
    public function test_remove_extra_slashes(){
        $text = "//app/static//js/app.js";
        
        $result1 = remove_extra_slashes($text);
        $expects = "/app/static/js/app.js";
        $this->assertEquals($expects, $result1);
        
        $text2 = "/$text";
        $result2 = remove_extra_slashes($text2);
        $this->assertEquals($expects, $result2);
    }
}

?>