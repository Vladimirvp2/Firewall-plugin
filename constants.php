<?php

/*
    This file is part of Site Access Manager plugin.

    Site Access Manager plugin is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Site Access Manager plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Site Access Manager plugin.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
* General plugin constants
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

define('SAM_OBJECT','fw');
define('SAM_DIR_NAME','site-access-manager');


// ban constants for wordpress DB storage and retrieving
// ban page
define('SAM_BAN_PERIOD','ban_period');
define('SAM_BAN_CHECK_PERIOD','ban_check_period');
define('SAM_BAN_CHECK_NUMBER','ban_check_number');
define('SAM_ADD_BAN_NUMBER_FOR_ADD_TO_BLACKLIST','ban_number_for_blacklist');
define('SAM_EMAIL_ME_IF_ADD_TO_BANLIST', 'email_while_add_to_banlist');
define('SAM_EMAIL_IF_ADD_TO_BANLIST', 'email_if_add_to_banlist');

define('SAM_BAN_LIST','banlist');

// splash sreen page
define('SAM_SHOW_SPLASHSCREEN','splashscreen');
define('SAM_SPLASHSCREEN_TITLE','splashscreen_title');
define('SAM_DEFAULT_SPLASHSCREEN_TITLE','Сайт находится на техническом обслуживании');
define('SAM_SPLASHSCREEN_MESSAGE','splashscreen_message');
define('SAM_DEFAULT_SPLASHSCREEN_MESSAGE','Как только работы будут завершены мы снова с вами встретимся! Приносим извинения за неудобства');

define('SAM_ENABLE_CUSTOM_SPLASHSCREEN','splashscreen_advanced_enable');
define('SAM_SPLASHSCREEN_CUSTOM_CSS','splashscreen_custom_css');
define('SAM_SPLASHSCREEN_CUSTOM_HTML','splashscreen_custom_html');

// access denied page
define('SAM_DEFAULT_ACCESS_DENIED_MESSAGE','Access denied');
define('SAM_DEFAULT_ACCESS_DENIED_TITLE','Message');





// blacklist page
define('SAM_ADD_TO_BLACKLIST','add_to_blacklist');
define('SAM_BLACKLIST_AREA','blacklist_area');


global $wpdb;

// DB tables
define('SAM_DB_TABLE_PREFIX', 'sam_');
define('SAM_DB_TABLE_REQUEST', $wpdb->prefix . SAM_DB_TABLE_PREFIX . 'request');
define('SAM_DB_TABLE_BANLIST', $wpdb->prefix . SAM_DB_TABLE_PREFIX . 'banlist');
define('SAM_DB_TABLE_BLACKLIST', $wpdb->prefix . SAM_DB_TABLE_PREFIX . 'blacklist');
define('SAM_DB_TABLE_BANNUMBERLIST', $wpdb->prefix . SAM_DB_TABLE_PREFIX . 'bannumberlist');
define('SAM_DB_TABLE_STATISTICS', $wpdb->prefix .  SAM_DB_TABLE_PREFIX . 'statistics');

// All referer
define('SAM_ALL_REFERERS','all');

define('SAM_START_YEAR', 2017);

define('SAM_LG_DOMAIN', 'sam');





?>