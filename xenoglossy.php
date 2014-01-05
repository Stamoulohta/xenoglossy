<?php
/*
 * vim: fileencoding=utf-8
 * Plugin Name: Xenoglossy
 * Plugin URI: http://www.stamoulohta.com/projects/xenoglossy
 * Description: WordPress plugin for the creation of multilingual posts.
 * Version: 01.00.00
 * Author: George Stamoulis
 * Author URI: http://www.stamoulohta.com
 * Licence: LGPL3
 *
 ********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

define('MAINDIR', dirname(__FILE__). DIRECTORY_SEPARATOR);

include(MAINDIR . 'constants.php');
include(MAINDIR . 'functions.php');
include(MAINDIR . 'register.php');
include(MAINDIR . 'selector.php');
include(MAINDIR . 'perserverance.php');
include(MAINDIR . 'orphans.php');
include(MAINDIR . 'server.php');
include(MAINDIR . 'locale.php');
include(MAINDIR . 'admin.php');

?>
