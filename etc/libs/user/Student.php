<?php
namespace libs\user;
require_once "setup.php";
require_once "User.php";
use configs\Vecni;

class Student extends User{
    public $student_email, 
    public $student_id, 
    public $univerity, 
    public $course;
    
}


?>