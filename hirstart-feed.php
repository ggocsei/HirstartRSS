<?php
/*
Plugin Name: Hírstart Feed
Plugin URI: http://pirin.hu
Description: RSS Feed a hirstart.hu-hoz
Author: Gábor Göcsei
Version: 1.0
Author URI: http://pirin.hu
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('init', 'hirstartfeed');

if ( is_admin() ){ // admin actions
	add_action('edit_category_form_fields', 'addHirstartCategoryToCat');
	add_action('edited_category', 'saveCategoryFields');
}

function hirstartfeed(){
	add_feed('hirstart', 'hirstartfeedtemplate');
}

function hirstartfeedtemplate(){
	load_template( untrailingslashit( dirname( __FILE__ ) ) . "/hirstart-feed-rss-template.php" );
}

function addHirstartCategoryToCat(){
	$hirstart_cat_title = get_term_meta($_REQUEST['tag_ID'], '_hirstart_cat_title', true);
	$hirstart_cat_tag = get_term_meta($_REQUEST['tag_ID'], '_hirstart_cat_tag', true);
	($hirstart_cat_tag==1)?$hirstart_cat_tag="checked='checked'":$hirstart_cat_tag="";
	?>
	<style>
		tr.hirstartrow{
			background-color: lightgrey
		}

		tr.hirstartrow td,tr.hirstartrow th{
			padding-left: 5px;
		}

		#hirstart_cat_excluded_tags{
			display: none;
		}
	</style>
	<tr class="form-field hirstartrow">
		<th colspan="2" scope="row" valign="top">
			<h3><?php _e('Hírstart beállítások'); ?></h3>
		</th>
	</tr>
	<tr class="form-field hirstartrow">
		<th scope="row" valign="top"><label for="hirstart_cat_title"><?php _e('Kategória'); ?></label></th>
		<td>
		<input type="text" name="hirstart_cat_title" id="hirstart_cat_title" value="<?php echo $hirstart_cat_title ?>"><br />
		<p class="description">
		Az itt megadott név kerül az RSS-be az eredeti kategórianév helyett.<br/>
		A használható kategória megnevezéseket a <a target="_blank" href="http://www.hirstart.hu/category">http://www.hirstart.hu/category</a> oldalon találod.</p>
		</td>
	</tr>
	<tr class="form-field hirstartrow">
		<th scope="row" valign="top"><label for="hirstart_cat_tag"><?php _e('Címke'); ?></label></th>
		<td>
		<input type="checkbox" name="hirstart_cat_tag" id="hirstart_cat_tag" <?= $hirstart_cat_tag ?>><br />
		<p class="description">Ennél a kategóriánál ha van címke akkor az kerül megjelenítésre ha bekapcsolod a funkciót. Ha nincs, akkor a megadott hírstart kategórianév (ha van).<br/>Amennyiben a címke leírásába bekerül a !nohirstart úgy az nem kerül megjelenítésre.</p>
		</td>
	</tr>
	<?php
}

function saveCategoryFields() {
	if ( isset( $_POST['hirstart_cat_title'] ) ) {
		update_term_meta($_POST['tag_ID'], '_hirstart_cat_title', $_POST['hirstart_cat_title']);
	}
	(isset($_POST['hirstart_cat_tag']) && $_POST['hirstart_cat_tag']=="on")?$_POST['hirstart_cat_tag']="1":'0';
	update_term_meta($_POST['tag_ID'], '_hirstart_cat_tag', $_POST['hirstart_cat_tag']);
}

/*install*/
register_activation_hook( __FILE__, 'install_hirstartfeed' );
function install_hirstartfeed() {
	hirstartfeed();
	flush_rewrite_rules();
}

/*uninstall*/
register_deactivation_hook( __FILE__, 'uninstall_hirstartfeed' );
function uninstall_hirstartfeed() {
	remove_action('init', 'hirstartfeed');
	remove_action('edit_category_form_fields', 'addHirstartCategoryToCat');
	remove_action('edited_category', 'saveCategoryFields');
	flush_rewrite_rules();
}
?>