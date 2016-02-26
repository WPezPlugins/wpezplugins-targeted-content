<?php

namespace wpezplugins_targeted_content\options;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists('wpezplugins_targeted_content\options\TC_Options') ) {

    class TC_Options
    {
        /**
         * Name of the the shortcode. e.g., [eztc...] where eztc is the name of the shortcode
         * @var string
         */
        public $_shortcode_name;
        /**
         * Name of the shortcode attribute that is the key. That is, which URL query string arg are we using to get the query value
         * @var string
         */
        public $_sc_atts_0;
        /**
         * Use the default functionality. That is, if the initial query returns nothing do we then default
         * @var bool
         */
        public $_default;
        /**
         * The default can be set in the shortcde or via URL query string. This property set to false will disable via URL query string
         * @var bool
         */
        public $_default_via_url;

        /**
         * Name of the custom post type (CPT) used for storing the targeted content
         * @var string
         */
        public $_post_type;
        /**
         * What does this CPT support
         * @var array
         */
        public $_post_type_supports;
        /**
         * What taxonmies does this CPT use
         * @var array
         */
        public $_cpt_taxonomies;
        /**
         * What is the admin menu position of the CPT
         * @var int
         */
        public $_cpt_menu_position;

        /**
         * CPT label
         * @var string
         */
        public $_cpt_label;
        /**
         * On the CPT admin "All" listing, display a column for the post_excerpt
         * @var bool
         */
        public $_col_post_excerpt_active;
        /**
         * Column header label for the post_excerpt
         * @var string
         */
        public $_col_post_excerpt_label;
        /**
         * On the CPT admin "All" listing, display a column for the post_date
         * @var bool
         */
        public $_col_post_date_active;
        /**
         * Column header label for the post_date
         * @var string
         */
        public $_col_post_date_label;
        /**
         * On the CPT admin "All" listing, display a column for the post_modified
         * @var bool
         */
        public $_col_post_modified_active;
        /**
         * Column header label for the post_modified
         * @var string
         */
        public $_col_post_modified_label;
        /**
         * Remove the standard WP column for Date
         * @var bool
         */
        public $_col_date_unset;
        /**
         * Name of the method used to display the post_except snippet column
         * @var string
         */
        public $_col_view_post_excerpt_method;
        /**
         * Name of the method used to display the post_date snippet column
         * @var string
         */
        public $_col_view_post_date_method;
        /**
         * Name of the method used to display the post_modified snippet column
         * @var string
         */
        public $_col_view_post_modified_method;


        /**
         * Name of the method for displaying on the frontend
         * @var string
         */
        public $_wpq_view_frontend_method;
        /**
         * The default post type to WP_Query against. In theory, this could be different than the new CPT (above).
         * @var string
         */
        public $_wpq_post_type;
        /**
         * Query by? Ref: // ref: http://codex.wordpress.org/Class_Reference/WP_Query#Post_.26_Page_Parameters
         * @var string
         */
        public $_wpq_by;
        /**
         * Order query results by? ref: ref: http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
         * @var string
         */
        public $_wpg_orderby;
        /**
         * The parm type of the value used in the query. Expected values: 'string' (default), 'int', 'array'
         * @var string
         */
        public $_wpq_by_parm;


        /**
         * Name of the frontend method used to display the targeted content
         * @var string
         */
        public $_view_frontend_method;
        /**
         * Wrap the targeted content in a div?
         * @var bool
         */
        public $_view_frontend_wrap_active;
        /**
         * Class of the wrapper div
         * @var string
         */
        public $_view_frontend_wrap_class;



        function __construct(){

            $this->_shortcode_name = 'eztc';
            $this->_sc_atts_0 = 'key';
            $this->_default = true;
            $this->_default_via_url = true;

            $this->_post_type = 'wpezp_tarcon';
            $this->_post_type_supports = array('title', 'editor','excerpt', 'revisions');
            $this->_cpt_taxonomies = array( 'post_tag', 'category');
            $this->_cpt_menu_position = 20;

            $this->_cpt_label = 'Targeted Content';
            $this->_col_post_excerpt_active = true;
            $this->_col_post_excerpt_label = 'Excerpt';
            $this->_col_post_date_active = true;
            $this->_col_post_date_label = 'Date Added';
            $this->_col_post_modified_active = true;
            $this->_col_post_modified_label = 'Date Modified';
            $this->_col_date_unset = true;                          // true will remove the standard WP Date column

            $this->_col_view_post_excerpt_method = 'post_excerpt_one';
            $this->_col_view_post_date_method = 'post_date_one';
            $this->_col_view_post_modified_method = 'post_modified_one';

            $this->_wpq_model_frontend_method = 'query_by_key_value';
            $this->_wpq_post_type = $this->_post_type;
            $this->_wpq_by = 'name';  // ref: http://codex.wordpress.org/Class_Reference/WP_Query#Post_.26_Page_Parameters
            $this->_wpg_orderby = 'none'; // ref: http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
            $this->_wpq_by_parm = 'string'; // values: 'string', 'int', 'array'

            $this->_view_frontend_method = 'one';
            $this->_view_frontend_wrap_active = true;
            $this->_view_frontend_wrap_class = 'wpezp-targeted-content';

        }
    }

}