<?php
namespace modules\mybook\post;
require_once "setup.php";
use libs\database\Database;
use configs\Websets;
class Post{
	private $post_id, $post_title, $post_message, $poster_name, $poster_id, $date_created;
	static $db;
	public function __construct(){	
        self::$db = new Database();
	} 	
        
    public static function getPosts($condition = 1){
        self::$db = new Database();
        if($results = self::$db->fetch_query_results("select p.post_id, title, pt.content, usr.user_id, first_name, last_name, up.date_created from post as p join posted_text as pt on p.post_id = pt.post_id join user_post as up on up.post_id = pt.post_id join profile as usr on up.user_id = usr.user_id where ".$condition." order by up.date_created DESC")){
            $posts = array();
            foreach($results as $data){
                $post = new Post();
                $post->setPostData($data);
                array_push($posts, $post);
            }
            return $posts;
        }else{
            return false;   
        }
    }    
    
    public function setPostData($data){
        $this->post_id = $data["post_id"];
        $this->post_title = $data["title"];
        $this->post_message = $data["content"];
        $this->poster_name = $data["first_name"]." ".$data["last_name"];
        $this->poster_id = $data["user_id"];
        $this->date_created = $data["date_created"];
    }
    
    public function getPostBy($attribute){
		return (!empty($this->$attribute))? $this->$attribute:0;
	}
    
    public static function addPost($user, $type, $title, $content){
        self::$db = new Database();
        if(self::$db->query("call addPost('".$user."', '".$title."', '".$type."', '".$content."', '')")){
            return 1;            
        }else{
            return 0;
        }   
    }
}


?>