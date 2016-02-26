<?php

namespace wpezplugins_targeted_content\shared;

if ( ! class_exists('wpezplugins_targeted_content\shared\TC_Shared') ) {

    class TC_Shared
    {

        function __construct($arr_args = array())
        {

        }

        /*
         * parse a URL Query string into an assoc array
         */
        public function url_query_to_assoc_arr($str_url_query='')
        {

            if ( empty($str_url_query) ){
                return array();
            }

            // explode the URL QUERY by &
            $arr_explode_1 = explode('&',$str_url_query );
            $arr_exploded = array();
            foreach ($arr_explode_1 as $str_x1){
                // explode the exploded URL QUERY by =
                $arr_explode_2 = explode('=', $str_x1);
                // pair it up: key => value + trim() + stringtolower()
                $arr_exploded[strtolower(trim($arr_explode_2[0]))] = strtolower(trim($arr_explode_2[1]));
            }

            return $arr_exploded;
        }

    }
}