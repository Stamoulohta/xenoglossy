<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* Applies the locale filter. */
add_filter('locale', 'xenoglossy_check', 0);

/**
 * Returns the chosen locale.
 *
 * @return string the locale
 */
function xenoglossy_check(){
    $locale = get_default_locale();
    if(isset($_GET[XG_GET]) && $_GET[XG_GET] != 'reset'){
        if($locale != $_GET[XG_GET]){
            return $_GET[XG_GET];
        }
    }
}


?>
