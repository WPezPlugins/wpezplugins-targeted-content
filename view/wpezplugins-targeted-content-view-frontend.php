<?php
namespace wpezplugins_targeted_content\view;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists('wpezplugins_targeted_content\view\TC_Frontend') ) {

    class TC_Frontend
    {

        protected $_options;

        function __construct($obj_options = false){
            $this->_options = $obj_options;
        }

        public function one($arr_posts = array()){

            if ( $this->_options === false){
                return '';
            }

            if ( ! isset($arr_posts[0]) ){
                return '';
            }
            // i suppose you could argue these could be two different views (read: methods). let's not go there for now ;)
            if ($this->_options->_view_frontend_wrap_active === true ){
                return '<div class="' . esc_attr($this->_options->_view_frontend_wrap_class) . '">' . wp_kses_post($arr_posts[0]->post_content) . '</div>';
            } else{
                return wp_kses_post($arr_posts[0]->post_content);
            }

        }


    }

}