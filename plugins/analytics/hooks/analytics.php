<?php defined('SYSPATH') or die('No direct script access.');

class analytics {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
        {
		Event::add('ushahidi_action.nav_main_top', array($this, 'add_nav_tab'));
                Event::add('ushahidi_action.header_scripts', array($this, 'add_chartjs'));
	}

        public function add_nav_tab($this_page = FALSE, $dontshow = FALSE)
        {
            $menu_items = array();

            if( ! is_array($dontshow))
            {
                    // Set $dontshow as an array to prevent errors
                    $dontshow = array();
            }

            if( ! in_array('analytics',$dontshow))
            {
                    $menu_items[] = array( 
                            'page' => 'analytics',
                            'url' => url::site('analytics'),
                            'name' => Kohana::lang('ui_main.analytics')
                    );	
            }


            // create a new view
            $analytics_tab = View::factory( 'analytics_tab' );

            $analytics_tab->menu_items = $menu_items;

            // render view
            $analytics_tab->render( TRUE );
        }

        public function add_chartjs()
        {
            $js = View::factory('chartjs_js');
            $js->render( TRUE );
        }

        public function get_categories(){
            $incidents = reports::fetch_incidents(TRUE);

            echo "$incidents";
        }
}

new analytics;
