<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * MinewsubWidget
 * Class for Widget.
 * @since 0.1
 */

if ( ! class_exists( 'MinewsubWidget' ) ) {

	class MinewsubWidget extends WP_Widget {

		function MinewsubWidget()
		{
			parent::__construct( false, __('Minimal newsletter subscription form', 'minewsub') , array( 'description' => __('Display a form to register to newsletter', 'minewsub')) );
		}

		function widget( $args, $instance )
		{
			extract( $args );
			$title = $instance['title'];
			$description = $instance['description'];
				
			echo $before_widget;
			
			if($title)
				echo $before_title.$title.$after_title;
			
			if($description)
				echo apply_filters('minewsub_widget_description', '<p>'.$description.'</p>', $description);
			
        	echo $GLOBALS['Minimal_Newsletter_Subscription_Plugin_Front']->newsletter_form();        	
		
			echo $after_widget;
			
		}

		function update( $new_instance, $old_instance )
		{
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['description'] = $new_instance['description'];
			return $instance;
		}

		function form( $instance )
		{
			$title = $instance['title'];
			$description = $instance['description'];
			
			 ?>
			 <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'minewsub' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>


        <p><label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'minewsub' ); ?></label>
        <br>
		<textarea style="width:100%;height: 55px;" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo $description; ?></textarea></p>
		<?php
		}
	}

}

