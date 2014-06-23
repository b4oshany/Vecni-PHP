<?php
namespace modules\mybook\post;
require_once "setup.php";
use modules\mybook\post\Post;
use configs\Websets;
Websets::startDatabase();
class Controller{    
    public static function getPosts($condition = 1){
        $posts = Post::getPosts($condition);  
        $html = "";
        foreach($posts as $post){
            $html .= '<div class="panel panel-default">
              <div class="panel-heading" ><a hre="'.$post->getPostBy("poster_id").'">'.$post->getPostBy("poster_name").'</a></div>
              <div class="panel-body">
                '.$post->getPostBy("post_message").'
              </div>
            </div>';
        }
        return $html;
    }
}
?>