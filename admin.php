<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* Trigger for admin pages. */
add_action('admin_menu', 'xenoglossy_on_admin_menu');

/**
 * Loads the administration pages.
 */
function xenoglossy_on_admin_menu(){
    include(MAINDIR . 'editor.php');
    include(MAINDIR . 'options.php');
    include(MAINDIR . 'about.php');
    load_style('xenoglossy', 'menu.css');
    add_menu_page('Xenoglossy', 'Xenoglossy', 'manage_options', XG_MENU, 'xenoglossy_options_page', IMGURL . 'menu.svg', '20.365');
    add_submenu_page( XG_MENU, 'Xenoglossy Options', 'Options', 'manage_options', XG_MENU, 'xenoglossy_options_page');
    add_submenu_page( XG_MENU, 'Translate', 'Translate', 'manage_options', XG_EDIT, 'xenoglossy_editor_page');
    add_xenoglossy_about_page(get_option(XG_HIDE));
}

?>
