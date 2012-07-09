<?php
/**
 * @package IE Check
 * @version 1.0.0
 */
/*
Plugin Name: IE Check
Plugin URI: http://josemarqu.es/ie-check/
Description: Checks if the browser is an older version of Internet Explorer, releases rage if it's IE<9
Author: José Marques
Version: 1.0
Author URI: http://josemarqu.es
*/


// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'iecheck_add_defaults');
register_deactivation_hook(__FILE__, 'iecheck_delete_plugin_options');
register_uninstall_hook(__FILE__, 'iecheck_delete_plugin_options');
add_action('admin_init', 'iecheck_init' );
add_action('admin_menu', 'iecheck_add_options_page');
add_filter( 'plugin_action_links', 'iecheck_plugin_action_links', 10, 2 );


// Init plugin options to white list our options
function iecheck_init(){
	register_setting( 'iecheck_plugin_options', 'iecheck_options', 'iecheck_validate_options' );
}

// Define default option settings
function iecheck_add_defaults() {
	$tmp = get_option('iecheck_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('iecheck_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"title" => "Wow",
						"text" => "",
						"browserPageURI" => "http://http://browsehappy.com/",
						"footerText" => "Please upgrade! It will make everyone happy!",
						"allowDismiss" => "yes",
						"displayMode" => "fullScreen"
		);
		update_option('iecheck_options', $arr);
	}
}

// Delete options table entries ONLY when plugin deactivated AND deleted
function iecheck_delete_plugin_options() {
	delete_option('iecheck_options');
}


// Add menu page
function iecheck_add_options_page() {
	add_options_page('Configure', 'IE Check', 'manage_options', __FILE__, 'iecheck_render_form');
}


// Render the Plugin options form
function iecheck_render_form() {
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/IE-Check/plugin.css" />
	<div class="wrap ie_check">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br/></div>
		<h2>IE Check configuration</h2>
		<p>In case you want to change the default text, here are some options for you!</p>
		

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php" >
			<?php settings_fields('iecheck_plugin_options'); ?>
			<?php $options = get_option('iecheck_options'); ?>

			<p>
				<label>Title</label>
				<input type="text" size="50" name="iecheck_options[title]" value="<?php echo $options['title']; ?>" />
			</p>
			<p class="wysiwyg">
				<label>Intro text</label>
				<span>Leave this field empty if you want to see the standard intro message.</span>
			</p>
				<?php
					$args = array("textarea_name" => "iecheck_options[text]","media_buttons" => false, "teeny" => true,"textarea_rows" =>3);
					wp_editor( $options['text'], "iecheck_options[text]", $args );
				?>
				<span></span>
			
			<p>
				<label>Browser page URI</label>
				<input type="url" size="80" name="iecheck_options[browserPageURI]" value="<?php echo $options['browserPageURI']; ?>" />
			</p>
			<p class="wysiwyg">
				<label>Footer text</label>
			</p>
				<?php
					$args = array("textarea_name" => "iecheck_options[footerText]","media_buttons" => false, "teeny" => true,"textarea_rows" =>3);
					wp_editor( $options['footerText'], "iecheck_options[footerText]", $args );
				?>
				<span></span>
				
			<p>
				<label class="options">Allow warning dismissal</label>

				<label class="options"><input name="iecheck_options[allowDismiss]" type="radio" value="yes" <?php checked('yes', $options['allowDismiss']); ?> /> Yes</label>

				<label class="options"><input name="iecheck_options[allowDismiss]" type="radio" value="no" <?php checked('no', $options['allowDismiss']); ?> /> No</label>

			</p>	

			<p>
				<label>Display mode</label>
				<select name='iecheck_options[displayMode]'>
					<option value='fullScreen' <?php selected('fullScreen', $options['displayMode']); ?>>full screen</option>
					<option value='header' <?php selected('header', $options['displayMode']); ?>>header</option>
					<option value='footer' <?php selected('footer', $options['displayMode']); ?>>footer</option>							
				</select>
			</p>	

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			
		</form>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function iecheck_validate_options($input) {
	 // strip html from textboxes
	$input['title'] =  wp_filter_nohtml_kses($input['title']); 
	//$input['browserPageURI'] =  wp_filter_nohtml_kses($input['browserPageURI']); 

	return $input;
}


/**
 * @param string $browser_version the browser version
 * @param int $years number of years since browser was released
 * @param string $years_label singular or plural years label
 * @param string $browser_version the browser version
*/

function ie_check(){

	$options = get_option('iecheck_options');

	$browser_version = 1;
	$years = 0;
	$years_label = " year";

	if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$_SERVER['HTTP_USER_AGENT'],$matched) ) {
    	
    	$browser_version=$matched[1];

		if($browser_version<9){
			switch($browser_version){
	    		case 5:
	    			$years = date("Y") - 2000;
	    			break;
	    		case 6:
	    			$years = date("Y") - 2001;
	    			break;
	    		case 7:
	    			$years = date("Y") - 2006;
	    			break;
	    		case 8:
	    			$years = date("Y") - 2009;
	    			break;	

	    		default:
	    			$years = date("Y") - 2010;
	    			break;		
	    	}

	    	if($years >1) $years_label = " years";

	    	//this should be the link to the plugin folder, if the path is not the stand it will not work
	    	echo '<link rel="stylesheet" type="text/css" href="'.plugins_url().'/IE-Check/ie_check.css" />';

    		//load jQuery
	    	if(!wp_script_is('jquery')) {
			    wp_enqueue_script("jquery"); 
			} 

			echo '<script type="text/javascript" >
		    			jQuery(document).ready(function(){jQuery("body").addClass("'.$options['displayMode'].'Warning");});
		    	</script>';

			echo '<div class="browserFeedback '.$options['displayMode'].'">';
			echo '<h3>'.$options['title'].'</h3>';
			
			if($options['text']!='')
				echo '<div class="text">'.$options['text'].'</div>';
			else{
				echo '<p> You are using Microsoft Internet Explorer '.$browser_version.', which is over '.$years.$years_label.' old!!! </p>
					<p>Seriously, you need to move on!</p>';
			}
			echo '<p>Here is a <a href="'.$options['browserPageURI'].'">list of good browsers you can use</a>.</p>';
			echo '<div class="footerText">'.$options['footerText'].'</div>';

			if($options['allowDismiss']=='yes'){
				echo '<p><a href="#" id="dismissWarning">Leave me alone</a></p>';

			

		    	echo '<script type="text/javascript" >
		    			jQuery(document).ready(function(){
		    				jQuery("#dismissWarning").click(function(){
		    					jQuery(".browserFeedback").hide();
		    				});
						});
		    		</script>';
			}
				
			echo '</div>';

		}
   	 	
	}

}

?>
