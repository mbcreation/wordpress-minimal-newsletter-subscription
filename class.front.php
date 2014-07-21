<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Minimal_Newsletter_Subscription_Plugin_Front
 * Class for frontend.
 * @since 0.1
 */

if ( ! class_exists( 'Minimal_Newsletter_Subscription_Plugin_Front' ) ) {

	class Minimal_Newsletter_Subscription_Plugin_Front
	{
		protected $email_value;
		protected $errors;
		protected $ok;
		protected $table;
		
		public function __construct()
		{
			$this->email_value = '';
			$this->ok = false;
			$this->errors = array();
			
			global $wpdb;
			$this->table = $wpdb->prefix.'minewsub_newsletter';
			
			$this->hooks();
			
		}

		protected function hooks()
		{
			add_action('init', array(&$this, 'handle_form_submission'));
			add_shortcode('newsletter_form', array(&$this, 'newsletter_form'));
			
			add_action('plugins_loaded', array(&$this, 'handle_form_submission'));
			
			add_action( 'widgets_init', array($this, 'register_widgets') );
		}
		
		public function register_widgets()
		{
			register_widget( 'MinewsubWidget' );
		}
		
		public function update_db_check() {
			global $minewsub_db_version;
			if (!get_site_option( 'minewsub_db_version' ) || get_site_option( 'minewsub_db_version' ) != $minewsub_db_version) {
				$this->install();
			}
		}
		
		public function install()
		{
			global $minewsub_db_version;
			
			$installed_ver = get_option( 'minewsub_db_version' );

			if( !$installed_ver || $installed_ver != $minewsub_db_version ) {
				
				$minewsub_db_version = '1.0';
			 
				$sql = "CREATE TABLE ".$this->table." (
					id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					email VARCHAR(255) NOT NULL,
					optin int(1) DEFAULT 1 NOT NULL,
					UNIQUE KEY email (email)
				);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

			  	update_option( 'minewsub_db_version', $minewsub_db_version );
			}
		}
		
		public function handle_form_submission()
		{
			if(isset($_POST['minewsub_email']))
			{
				if(wp_verify_nonce( $_REQUEST['_wpnonce'], 'minewsub' ))
				{
					$this->email_value = $_POST['minewsub_email'];
					
					if(empty($_POST['minewsub_email']))
						$this->errors[] = __('Email is mandatory', 'minewsub');
					elseif(!is_email($_POST['minewsub_email']))
						$this->errors[] = __('Email is not valid', 'minewsub');
					else
					{
						global $wpdb;
						$user_count = $wpdb->get_var( $wpdb->prepare('SELECT COUNT(*) FROM '.$this->table.' where email=%s', $_POST['minewsub_email']) );
						
						if($user_count>0)
							$this->errors[] = __('Email already registered', 'minewsub');
					}
					
					if(count($this->errors)==0)
					{
						$this->ok = true;
						global $wpdb;
						$wpdb->insert( $this->table, 
							array('time' => date('Y-m-d H:i:s'), 'email' => $_POST['minewsub_email'], 'optin' => '1'),
						 	array( '%s', '%s', '%d')
						 );
					}
				}
			}
		}
		
		public function newsletter_form()
		{
			if(!$this->ok)
			{
				$str = '<form action="'.$_SERVER['REQUEST_URI'].'" class="minimal-newsletter-subscription" method="post">';
			
				if(count($this->errors)>0)
				{
					$str .= '<div class="alert-error"><ul>';
					foreach($this->errors as $error)
						$str .= '<li>'.$error.'</li>';
					$str .= '</ul></div>';
				}
			
						
				$champ_email = '<p>';
			
					$champ_email .= '<label for="minewsub_email">'.__('Email', 'minewsub').'<span class="required">*</span></label>';
					$champ_email .= '<input type="email" id="minewsub_email" name="minewsub_email" value="'.$this->email_value.'" />';
			
				$champ_email .= '</p>';
				
				$champ_email = apply_filters('minewsub_input_email_html_filter', $champ_email);
				
				$str.= $champ_email;
			
				$champ_ok = '<p>';
					$champ_ok .= apply_filters('minewsub_submit_filter', '<input type="submit" value="'.__('Register', 'minewsub').'">');
				$champ_ok .= '</p>';
				
				$champ_ok = apply_filters('minewsub_input_ok_html_filter', $champ_ok);
				
				$str.= $champ_ok;
				$str .= wp_nonce_field( 'minewsub' , '_wpnonce', true, false );
			
				$str .= '</form>';
			
				return apply_filters('minewsub_form_html_filter', $str);
			}
			else
			{
				$str = '<div class="alert-success"><p>'.__('You successfully have been registered.', 'minewsub').'</p></div>';
				return apply_filters('minewsub_validation_html_filter', $str);
			}
		}
		
	} // Minimal_Newsletter_Subscription_Plugin_Front
}

