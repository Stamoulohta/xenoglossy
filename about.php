<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/**
 * Adds the About page to the admin menu if not disabled by the user.
 *
 * @param bool $hide_plea true if about page is disabled.
 */
function add_xenoglossy_about_page($hide_plea){
    if(!$hide_plea){
        add_submenu_page( XG_MENU, 'About Xenoglossy', 'About', 'manage_options', XG_ABOUT, 'xenoglossy_about_page');
    }
}

/**
 * Prints the About page.
 */
function xenoglossy_about_page(){
    load_style('xg_about', 'about.css');
    include(HTMLDIR . 'about.html');
}

?>
