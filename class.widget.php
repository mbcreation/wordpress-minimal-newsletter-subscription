<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * MinewsubWidget
 * Class for Widget.
 * @since 0.1
 */

if ( ! class_exists( 'MinewsubWidget' ) ) {

	class MinewsubWidget extends WP_Widget {

		function MinewsubWidget() {
			parent::__construct( false, __('Minimal newsletter subscription form', 'minewsub') , array( 'description' => __('Display a form to register to newsletter', 'minewsub')) );
		}

		function widget( $args, $instance )
		{
			extract( $args );
				
			echo $before_widget;
        	echo $GLOBALS['Minimal_Newsletter_Subscription_Plugin_Front']->newsletter_form();        	
		
			echo $after_widget;
			
		}

		function update( $new_instance, $old_instance ) {
			// Save widget options
		}

		function form( $instance ) {
			// Output admin widget options form
		}
	}

}

