<?php
/*
 *
 */

namespace wpezplugins_targeted_content\cpt;

if ( ! class_exists('wpezplugins_targeted_content\cpt\TC_CPT') ) {

    class TC_CPT
    {

        protected $_settings;

        function __construct( $obj_options = false )
        {
            $this->_options = $obj_options;

            add_action('init', array($this, 'register_post_type_do'));
            add_action('init', array($this, 'add_post_type_support_do'));
        }

        /*
         *
         */
        public function register_post_type_do()
        {

            register_post_type( $this->_options->_post_type, $this->register_post_type_args() );
        }

        /*
         *
         */
        public function register_post_type_args()
        {

            return array(
                'label'                 => $this->_options->_cpt_label,
                'public' 	            => true,			// default: false
                'exclude_from_search'	=> true,			// default: opposite of public (if this is not set)
                'publicly_queryable'	=> false,			// default: value of public
                'show_ui'				=> true, 			// default: value of public
                'show_in_nav_menus'		=> true, 			// dafault: value of public
                'show_in_menu'			=> true,			// default: value of show_ui
                'show_in_admin_bar' 	=> false,			// default: value of the show_in_menu argument
                'taxonomies'            => $this->_options->_cpt_taxonomies,
                'menu_position'         => $this->_options->_cpt_menu_position,
            );
        }

        /*
         *
         */
        function add_post_type_support_do()
        {
            add_post_type_support( $this->_options->_post_type, $this->_options->_post_type_supports );
        }

    }
}