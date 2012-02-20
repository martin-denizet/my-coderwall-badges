<?php
/*
Plugin Name: My Coderwall Badges
Description: gets your badges from coderwall website and let you show them on your blog.
Author: Francesco Lentini
Version: 0.1
Plugin URI: https://github.com/flentini/my-coderwall-badges
Author URI: http://spugna.org/tpk
*/

add_action('init','cwb_init');
add_action('wp_enqueue_scripts', 'cwb_stylesheet');
add_action('admin_menu', 'cwb_init_admin');

function cwb_init(){
	require_once('cwbclass.php');
	define('CWB_URLPATH', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)));
	global $cwb;
	$cwb = new CWB();
}

function cwb_init_admin() {
	$ad_opt_page = add_menu_page('My CW Badges', 'My CW Badges', 
		'manage_options', 'cwbadges-plugin', 'cwb_options',CWB_URLPATH .'/css/coderwallicon.png');
	add_action('admin_print_styles-'.$ad_opt_page, wp_enqueue_style('cwb-css', CWB_URLPATH .'/css/style.css', false, false, 'all'));
}

function cwb_stylesheet(){
	$cwb_css_url = plugins_url('css/style.css', __FILE__);
	$cwb_css_file = WP_PLUGIN_DIR . '/coderwall-badges/css/style.css';
	 if ( file_exists($cwb_css_file) ) {
            wp_register_style('cwb-css', $cwb_css_url);
            wp_enqueue_style('cwb-css', $cwb_css_file, false, false, 'all');
     }
}

function cwb_options() {
	if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	global $cwb;
	 
	if (isset($_POST['cwusername'])&&!empty($_POST['cwusername'])) {
		$cwb->set_username($_POST['cwusername']);
	}
	?>
	
	<div class="wrap">
		<p><div id="icon-users" class="icon32"></div><h2>My Coderwall Badges</h2></p>	
		<div>	
			<div style="display: inline-block; float: left">
				<?php echo 'name: <h3>'.$cwb->get_name().'</h3>'; ?>
				<?php echo 'location: <h3>'.$cwb->get_location().'</h3>'; ?>
			</div>
			<div style="display: inline-block; margin-left: 150px; margin-bottom: 125px;">
			<form name="cwb_form" method="post" action="<?php str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<label for="cwusername">coderwall username: </label>
	        	<input id="cwusername" maxlength="45" size="25" name="cwusername" value="<?php echo $cwb->get_username(); ?>" />
	        	
				<input class="button-primary" type="submit" name="Save" value='<?php _e("Save"); ?>' />
			</form>
			</div>
		</div>	
		<div>
			<?php echo $cwb->get_badges(); ?>
		</div>
	</div>	
<?php }