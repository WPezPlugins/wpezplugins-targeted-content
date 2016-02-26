<?php

namespace wpezplugins_targeted_content\model;

if ( ! class_exists('wpezplugins_targeted_content\model\TC_Frontend') ) {

    class TC_Frontend
    {
        /**
         * An instance of options\TC_Options
         * @var options\TC_Options
         */
        protected $_options;


        function __construct($obj_options = false )
        {
            $this->_options = $obj_options;
        }

        /**
         * This is where the query magic happens
         * @param string $str_post_type
         * @param string $mix_value
         *
         * @return array|bool
         */
        public function query_by_key_value($str_post_type = '' , $mix_value = ''){

            if ( $this->_options == false ){
                return false;
            }

            if ( empty($str_post_type) || empty($mix_value) ){
                return false;
            }

            if ( $this->_options->_wpq_by_parm == 'int' ){
                $mix_value = (int) $mix_value;
                if ( $mix_value == 0 ){
                    $mix_value = -1;
                }
            } elseif ( $this->_options->_wpq_by_parm == 'array'){
                $mix_value = str_replace(' ', '', $mix_value);
                $mix_value = explode(',', $mix_value);
            }

            // set up the wpq args
            $arr_wpq_args = array(
                'post_type' => $str_post_type,
                $this->_options->_wpq_by => $mix_value,
                'orderby' => $this->_options->_wpg_orderby
            );

            // do the query!
            $obj_query = new \WP_Query( $arr_wpq_args );
            $arr_posts = $obj_query->posts;

            wp_reset_postdata();

            // what do we have?
            if ( isset ($arr_posts[0]) ){
                return $arr_posts;
            } else {
                return false;
            }
        }
    }
}