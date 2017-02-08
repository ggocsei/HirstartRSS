<?php
/*
Plugin Name: Hírstart Feed
Plugin URI: http://pirin.hu
Description: RSS Feed a hirstart.hu-hoz
Author: Gábor Göcsei
Version: 0.1
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
    $hirstart_cat_title = get_term_meta($_POST['tag_ID'], '_hirstart_cat_title', true);
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



add_action('admin_init', 'my_general_section');
function my_general_section() {
    add_settings_section(
        'my_settings_section', // Section ID
        'My Options Title', // Section Title
        'my_section_options_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );

    add_settings_field( // Option 1
        'option_1', // Option ID
        'Option 1', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'option_1' // Should match Option ID
        )
    );

    add_settings_field( // Option 2
        'option_2', // Option ID
        'Option 2', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'option_2' // Should match Option ID
        )
    );

    register_setting('general','option_1', 'esc_attr');
    register_setting('general','option_2', 'esc_attr');
}

function my_section_options_callback() { // Section Callback
    echo '<p>A little message on editing info</p>';
}

function my_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}
?>