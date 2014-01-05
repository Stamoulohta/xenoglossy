<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* Triggers before any headers are sent. */
add_action('init', 'perserverance');

/**
 * Preserves the requested locale until expicitly changed.
 */
function perserverance(){
    if(!isset($_GET[XG_GET]) && isset($_SERVER['HTTP_REFERER'])
                                                    && strpos($_SERVER['HTTP_REFERER'], XG_GET.'=') !== False){
        if(preg_match('/' . XG_GET . '=reset/', $_SERVER['HTTP_REFERER'])){ return; }
        $uri = get_appendable_uri();
        preg_match('/' . XG_GET . '=(\w*)($|&|#)/', $_SERVER['HTTP_REFERER'], $locale);
        exit(wp_safe_redirect($uri . $locale[0]));
    }
}

?>
