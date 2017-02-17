<?php
/*
Plugin Name: Hírstart Feed
Plugin URI: http://pirin.hu
Description: RSS Feed a hirstart.hu-hoz
Author: Gábor Göcsei
Version: 0.3
Author URI: http://pirin.hu
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('init', 'hirstartfeed');
add_action('edit_category_form_fields', 'addHirstartCategoryToCat');
add_action('edited_category', 'saveCategoryFields');

function hirstartfeed(){
	add_feed('hirstart', 'hirstartfeedtemplate');
}

function hirstartfeedtemplate(){
	load_template( untrailingslashit( dirname( __FILE__ ) ) . "/hirstart-feed-rss-template.php" );
}

function addHirstartCategoryToCat(){
    $hirstart_cat_title = get_term_meta($_REQUEST['tag_ID'], '_hirstart_cat_title', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="hirstart_cat_title"><?php _e('Hírstart kategória'); ?></label></th>
        <td>
        <input type="text" name="hirstart_cat_title" id="hirstart_cat_title" value="<?php echo $hirstart_cat_title ?>"><br />
        <p class="description">A használható kategóriákat a <a target="_blank" href="http://www.hirstart.hu/category">http://www.hirstart.hu/category</a> oldalon találod.</p>
        </td>
    </tr>
    <?php
}

function saveCategoryFields() {
    if ( isset( $_POST['hirstart_cat_title'] ) ) {
        update_term_meta($_POST['tag_ID'], '_hirstart_cat_title', $_POST['hirstart_cat_title']);
    }
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