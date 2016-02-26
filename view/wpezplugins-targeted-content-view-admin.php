<?php

namespace wpezplugins_targeted_content\view;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists('wpezplugins_targeted_content\view\TC_Admin') ) {

    class TC_Admin
    {

        function __construct(){

        }


        public function post_date_one($obj_post = object){

            echo $obj_post->post_date;
            echo '<br>' . $obj_post->post_status;

        }


        public function post_modified_one($obj_post = object){

            echo $obj_post->post_modified;
        }


        public function post_excerpt_one($obj_post = object){

            echo $obj_post->post_excerpt;
        }


    }

}