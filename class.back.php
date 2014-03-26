<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Minimal_Newsletter_Subscription_Plugin_Back
 * Class for frontend.
 * @since 0.1
 */

if ( ! class_exists( 'Minimal_Newsletter_Subscription_Plugin_Back' ) ) {

	class Minimal_Newsletter_Subscription_Plugin_Back
	{
		protected $table;
		
		public function __construct()
		{			
			global $wpdb;
			$this->table = $wpdb->prefix.'minewsub_newsletter';
			
			add_action('admin_menu', array($this, 'register_menu_page'));
        	add_action('init', array($this, 'do_export'));
        
		}
    

		public function register_menu_page()
		{
			add_menu_page( __('Newsletter', 'minewsub') , __('Newsletter', 'minewsub'), apply_filters('minewsub_admin_capability_filter', 'edit_posts'), 'minewsub', array($this, 'exporter') );
		}


		public function do_export()
		{
			if(isset($_POST['valide_export']))
			{
				if(wp_verify_nonce( $_REQUEST['_wpnonce'], 'export_minewsub_subscribers' ))
				{
					global $wpdb;
					$rows = $wpdb->get_results( 'SELECT * FROM '. $this->table.' where optin=1 order by time asc');
			
					$filename = 'minewsub-subscribers' .'-'. date( 'Y-m-d_H-i-s' ) . '.csv';
					header( 'Content-Encoding: utf-8');
					header( 'Content-Description: File Transfer' );
					header( 'Content-Disposition: attachment; filename=' . $filename );
					header( 'Content-Type: text/csv; charset=utf-8', true );
				
					echo '"'.__('Subscription date', 'minewsub').'";"'.__('Email', 'minewsub').'"'."\n";
				
					foreach($rows as $r)
					{
						echo '"'.$r->time.'";';
						echo '"'.$r->email.'"';
						echo "\n";
					}
					die();
				}
			}
		}
		
		public function exporter()
		{
			global $wpdb;
			$nb = $wpdb->get_var('SELECT COUNT(*) FROM '.$this->table.' where optin=1');
			echo '<div class="wrap">
				<h2>'.__('Export subscribers', 'minewsub').'</h2>
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="margin-top: 20px;">
					<p>'.sprintf(__('You currently have %s subscribers.', 'minewsub'), $nb).'</p>
					<input type="hidden" name="valide_export" value="1">'.wp_nonce_field( 'export_minewsub_subscribers' , '_wpnonce', true, false ).'
					<input type="submit" class="button button-primary button-large" value="'.__('Export subscribers', 'minewsub').'" />
				</form>
			</div>';
		}
		
		
	} // Minimal_Newsletter_Subscription_Plugin_Back
}

