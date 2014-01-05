<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* PATHS */
define('HTMLDIR', MAINDIR . 'html'   . DIRECTORY_SEPARATOR);

/* HANDLES */
define('LANGTAX'   , 'xg_lang');        // taxonomy
define('PARENTAX'  , 'xg_parent');      // taxonomy
define('TRANSLAT'  , 'translation');    // post_type
define('XG_LANGS'  , 'xg_langs');       // option
define('XG_SELECT' , 'xg_selector');    // option
define('XG_HIDE'   , 'xg_hide_plea');   // option
define('XG_MENU'   , 'xg_opts');        // menu slug
define('XG_EDIT'   , 'xg_editor');      // menu slug
define('XG_ABOUT'  , 'xg_about');       // menu slug
define('XG_GET'    , 'lang');           // flag

/* URLS */
define('MAINURL'   , plugins_url('xenoglossy/'));
define('ADMINURL'  , admin_url());
define('CSSURL'    , MAINURL . 'css/');
define('IMGURL'    , MAINURL . 'images/');
define('OPTSURL'   , ADMINURL . 'admin.php?page=' . XG_MENU);
define('EDITURL'   , ADMINURL . 'admin.php?page=' . XG_EDIT);

?>
