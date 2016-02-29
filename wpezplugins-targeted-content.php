<?php
/**
Plugin Name: WPezPlugins: Targeted Content
Plugin URI: https://github.com/WPezPlugins/wpezplugins-targeted-content
Description: Injects content (via a shortcode) from a custom post type (Targeted Content) based on a shortcode atts; that atts can also map to URL Query args.
Version: 0.4.1
Author: Mark Simchock for Alchemy United
Author URI: http://AlchemyUnited.com?utm_source=github&utm_medium=repo&utm_campaign=wpezp-tc
License: GPLv2+
Text Domain: wpezp-targeted-content
Domain Path: /languages
*/

/*
 * CHANGE LOG
 *
 * 0.4.1 - Mon 29 Feb 2016
 * -- Changed: Moved options into model
 * -- Changed: Updated README
 *
 * 0.4.0 - Thur 25 Feb 2016
 * -- Added: Namespace'ing
 * -- Changed: Refactored where necessary
 * -- Added: Commenting
 * -- Added: Init'ed on GitHub (finally)
 *
 * 0.3.0 - Fri 12 Feb 2016
 * -- Added: Excerpt to cols
 * -- Changed: Major refactoring. Broken code into smaller specialized classes.
 * -- Added: Settings file to make customization ez :)
 *
 * 0.2.0 - Fri 12 Feb 2016
 * -- Added: cats and tags to SC Content custom post listing.
 * -- Added: cols + sorts to SC Content custom post listing.
 *
 * 0.1.0 - Thur 11 Feb 2016
 * -- The basics. It works!
 */

namespace wpezplugins_targeted_content;

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
    echo 'Hello...Good-bye.';
    exit;
}


if ( ! class_exists('wpezplugins_targeted_content\model\TC_Options') ) {
    require('model/wpezplugins-targeted-content-options.php');
}

if ( ! class_exists('wpezplugins_targeted_content\shared\TC_Shared') ) {
    require('inc/wpezplugins-targeted-content-shared.php');
}

if ( ! class_exists('wpezplugins_targeted_content\cpt\TC_CPT_Columns') ) {
    require('cpt/wpezplugins-targeted-content-cpt-columns.php');
}


if ( ! class_exists('wpezplugins_targeted_content\model\TC_Frontend') ) {
    require('model/wpezplugins-targeted-content-model-frontend.php');
}

if ( ! class_exists('wpezplugins_targeted_content\view\TC_Admin') ) {
    require('view/wpezplugins-targeted-content-view-admin.php');
}

if ( ! class_exists('wpezplugins_targeted_content\view\TC_Frontend') ) {
    require('view/wpezplugins-targeted-content-view-frontend.php');
}

if ( ! class_exists('wpezplugins_targeted_content\cpt\TC_CPT') ) {
    require('cpt/wpezplugins-targeted-content-cpt.php');
}


if ( ! class_exists('wpezplugins_targeted_content\Targeted_Content') ) {

 //   class class_ez_sc_content
    class Targeted_Content
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
         * An instance of view\TC_Frontend_
         * @var view\TC_Frontend
         */
        protected $_view_frontend;
        /**
         * An instance of model\TC_Frontend
         * @var model\TC_Frontend
         */
        protected $_model_frontend;
        /**
         * Does the method being used for the frontend model exist?
         * @var bool
         */
        protected $_bool_exists_model_frontend_method;
        /**
         * Does the method being used for the frontend view exist?
         * @var bool
         */
        protected $_bool_exist_view_frontend_method;


        function __construct( $args = array() )
        {
            $this->_options = new model\TC_Options();

            if ( ! is_admin()){
                $this->_model_frontend = new model\TC_Frontend($this->_options);
            }

            $this->_view_admin = new view\TC_Admin($this->_options);
            $this->_view_frontend = new view\TC_Frontend($this->_options);

            $arr_args = array(
                'options'   => $this->_options,
                'model_frontend' => $this->_model_frontend,
                'view_admin'   => $this->_view_admin,
                'view_frontend'   => $this->_view_frontend,
            );

            // We pass the whole lot to TC_Shared because ya never know what you might need there :)
            $this->_shared = new shared\TC_Shared($arr_args);

            $arr_args = array_merge(
                array(
                    'shared'    =>   $this->_shared
                ),
                $arr_args
            );


            if ( is_admin() ) {
                new cpt\TC_CPT($this->_options);
                // passin it all in boss
                new cpt\TC_CPT_Columns($arr_args);
            }

            $this->_bool_exists_model_frontend_method = false;
            if ( method_exists($this->_model_frontend, $this->_options->_wpq_model_frontend_method) ){
                $this->_bool_exists_model_frontend_method = true;
            }
            $this->_bool_exist_view_frontend_method = false;
            if ( method_exists($this->_view_frontend, $this->_options->_view_frontend_method) ){
                $this->_bool_exist_view_frontend_method = true;
            }

            add_action('init', array($this, 'add_shortcode_do') );
        }


        /**
         *
         */
        public function add_shortcode_do(){
            add_shortcode( $this->_options->_shortcode_name, array($this, 'shortcode_handler') );
        }


        /**
         * @param array $arr_atts_sc
         *
         * @return string
         */
        public function shortcode_handler($arr_atts_sc = array()){

            // key is not in sc
            if ( ! isset($arr_atts_sc[$this->_options->_sc_atts_0]) ){
                return '';
            }

            $arr_atts = shortcode_atts($this->shortcode_defaults(), $arr_atts_sc);

            // there is a key but for some reason its empty / blank
            if ( empty($arr_atts[$this->_options->_sc_atts_0]) ){
               return '';
            }

            // cool - we have a key and it has a non-blank value :)
            $str_key = $arr_atts[$this->_options->_sc_atts_0];
            // TODO - check to see if post_type is valid (?)
            $str_post_type = $arr_atts['post_type'];

            // kinda don't need this atm but let's just play it this way just in case there's other url parsing
            $str_url = 'http://foo-bar.com' . $_SERVER['REQUEST_URI'];
            $arr_parse_url = parse_url($str_url, PHP_URL_QUERY);
            $arr_assoc_url_query = $this->_shared->url_query_to_assoc_arr($arr_parse_url);

            // do we ant to use the key as it's own default value
            $bool_default = $this->_options->_default;
            if ( isset($arr_assoc_url_query['default']) && $this->_options->_default_via_url === true ){
                // ref: http://stackoverflow.com/questions/7336861/how-to-convert-string-to-boolean-php
                $bool_str_default = $arr_assoc_url_query['default'] === 'true' ? true: false;
               if ($bool_str_default !== $bool_default){
                   $bool_default = $bool_str_default;
               }
            }
            // now check the $arr_atts[] to see if the default was set there
            if ( ! empty($arr_atts['default']) ){
                // ref: http://stackoverflow.com/questions/7336861/how-to-convert-string-to-boolean-php
                $bool_default = $arr_atts['default'] === 'true' ? true: false;
            }

            // start by presuming we have nothings
            $arr_posts = false;
           // if the model method exists then do it!
            if ( $this->_bool_exists_model_frontend_method ) {
                $str_model_frontend_method = $this->_options->_wpq_model_frontend_method;
                // if the key isn't in the url then we want the default for that key.
                if ( isset($arr_assoc_url_query[$str_key]) ){

                    // the key is in the url so use the => value to do the query
                    $str_value = $arr_assoc_url_query[$str_key];
                    $arr_posts = $this->_model_frontend->$str_model_frontend_method($str_post_type, $str_value);
                    // if the value doesn't return anything then use the key as a default (value) and re query
                    if ( $arr_posts === false && $bool_default ){
                        $arr_posts = $this->_model_frontend->$str_model_frontend_method($str_post_type, $str_key);
                    }

                    // the "key" is not in the query string so use the key as the query value IF the $bool_default setting sez so
                } elseif ( $bool_default === true ) {
                    $arr_posts = $this->_model_frontend->$str_model_frontend_method($str_post_type, $str_key);
                }
            }
            // if we still have false then return '' (just to be extra clean ;)
            if ( $arr_posts !== false ){

                // is the view method exists then do it!
                if ( $this->_bool_exist_view_frontend_method) {
                    $str_view_frontend_method = $this->_options->_view_frontend_method;
                    $str_content = $this->_view_frontend->$str_view_frontend_method($arr_posts);
                    return $str_content;
                }
            }
            return '';

        }

        /**
         * defaults for the shortcode.
         * @return array
         */
        public function shortcode_defaults(){

            return array(
                $this->_options->_sc_atts_0 => '',
                'post_type' => $this->_options->_wpq_post_type,
                'default' => ''
            );
        }
    }
}

new Targeted_Content();