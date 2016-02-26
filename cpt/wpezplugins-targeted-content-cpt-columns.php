<?php

namespace wpezplugins_targeted_content\cpt;

if ( ! class_exists('wpezplugins_targeted_content\cpt\TC_CPT_Columns') ) {

    class TC_CPT_Columns
    {
        /**
         * An instance of options\TC_Options
         * @var options\TC_Options
         */
        protected $_options;
        /**
         * An instance of shared\TC_Shared
         * @var shared\TC_Shared
         */
        protected $_shared;
        /**
         * An instance of view\TC_Admin
         * @var view\TC_Admin
         */
        protected $_view_admin;

        /**
         * Does the method being used for post_date CPT all admin col snippet exist?
         * @var bool
         */
        protected $_bool_exists_view_post_date_method;
        /**
         * Does the method being used for post_modified CPT all admin col snippet exist?
         * @var bool
         */
        protected $_bool_exists_view_post_modified_method;
        /**
         * Does the method being used for post_excerpt CPT all admin col snippet exist?
         * @var bool
         */
        protected $_bool_exists_view_post_excerpt_method;

        function __construct($arr_args = false)
        {
            if ( ! is_array( $arr_args ) ) {
                return;
            }
            if ( ! isset($arr_args['options']) || ! isset($arr_args['shared']) || ! isset($arr_args['view_admin']) ) {
                return;
            }

            $this->_options = $arr_args['options'];
            $this->_shared = $arr_args['shared'];
            $this->_view_admin = $arr_args['view_admin'];

            $this->_bool_exists_view_post_date_method = false;
            if ( method_exists( $this->_view_admin, $this->_options->_col_view_post_date_method ) ){
                $this->_bool_exists_view_post_date_method = true;
            }

            $this->_bool_exists_view_post_modified_method = false;
            if ( method_exists( $this->_view_admin, $this->_options->_col_view_post_modified_method ) ){
                $this->_bool_exists_view_post_modified_method = true;
            }

            $this->_bool_exists_view_post_excerpt_method = false;
            if ( method_exists( $this->_view_admin, $this->_options->_col_view_post_excerpt_method ) ){
                $this->_bool_exists_view_post_excerpt_method = true;
            }

            add_filter('manage_posts_columns', array($this, 'manage_posts_columns_do'));
            add_action('manage_posts_custom_column',  array($this, 'manage_posts_custom_column_do'));
        }

        /**
         * @param $arr_cols
         *
         * @return array
         */
        function manage_posts_columns_do($arr_cols)
        {
            global $post;

            if ( $post->post_type == $this->_options->_post_type )
            {
                $str_http = "http://";
                if( isset($_SERVER['HTTPS'] ) ) {
                    $str_http = "https://";
                }

                // TODO - is there an easier / better way to do this. (hint: yeah probably)
                $str_sn = $_SERVER['SERVER_NAME'];
                $str_ps = $_SERVER['PHP_SELF'];
                $str_ru = $_SERVER['REQUEST_URI'];
                $arr_ru = $this->_shared->url_query_to_assoc_arr($str_ru);

                // TODO - this feels messy. clean up (?)
                $str_order_new = 'asc';
                $str_post_date_default = 'post_type=' . $this->_options->_post_type . '&orderby=post_date&order=';
                $str_post_modified_default = 'post_type=' . $this->_options->_post_type . '&orderby=post_modified&order=';

                if ( isset($arr_ru['orderby']) && $arr_ru['orderby'] == 'post_date' && isset($arr_ru['order']) ){
                    $str_order_new = $this->order_toggle($arr_ru['order']);
                } elseif ( isset($arr_ru['orderby']) && $arr_ru['orderby'] == 'post_modified' && isset($arr_ru['order']) ){
                    $str_order_new = $this->order_toggle($arr_ru['order']);
                }

                if ($this->_options->_col_post_date_active === true){
                    $arr_cols['post_date'] = '<a href="'. $str_http . $str_sn . $str_ps . '?' . $str_post_date_default .  $str_order_new. '">' . $this->_options->_col_post_date_label . '</a>';
                }

                if ($this->_options->_col_post_modified_active === true){
                    $arr_cols['post_modified'] = '<a href="'. $str_http . $str_sn . $str_ps . '?' . $str_post_modified_default .  $str_order_new. '">' . $this->_options->_col_post_modified_label . '</a>';
                }


                // TODO - again, feels messy. clean up (?)
                if ( $this->_options->_col_date_unset === true){
                    unset($arr_cols['date']);
                }
                $temp_cb = $arr_cols['cb'];
                unset($arr_cols['cb']);
                $temp_title = $arr_cols['title'];
                unset($arr_cols['title']);

                $arr_cols_new['cb'] = $temp_cb;
                $arr_cols_new['title'] = $temp_title;
                if ($this->_options->_col_post_excerpt_active === true) {
                    $arr_cols_new['post_excerpt'] = $this->_options->_col_post_excerpt_label;
                }

                $arr_cols_new = array_merge($arr_cols_new, $arr_cols);

                return $arr_cols_new;

            }
            return $arr_cols;

        }

        /**
         * Toggle the order value between asc and desc
         * @param string $str_order
         *
         * @return string
         */
        function order_toggle( $str_order = 'asc' ){

            $str_order_new = 'asc';
            if ( strtolower($str_order) == 'asc'){
                $str_order_new = 'desc';
            }
            return  $str_order_new;
        }


        /**
         * "View do" the various column snippets
         * @param $str_col_name
         */
        function manage_posts_custom_column_do($str_col_name) {
            global $post;

            switch ($str_col_name) {

                case 'post_date':
                    if ( $this->_bool_exists_view_post_date_method ) {
                        $str_temp = $this->_options->_col_view_post_date_method;
                        $this->_view_admin->$str_temp( $post );
                    }
                    break;

                case 'post_modified':
                    if ( $this->_bool_exists_view_post_modified_method ) {
                        $str_temp = $this->_options->_col_view_post_modified_method;
                        $this->_view_admin->$str_temp( $post );
                    }
                    break;

                case 'post_excerpt':
                    if ( $this->_bool_exists_view_post_excerpt_method ) {
                        $str_temp = $this->_options->_col_view_post_excerpt_method;
                        $this->_view_admin->$str_temp( $post );
                    }
                    break;
            }
        }

    }
}