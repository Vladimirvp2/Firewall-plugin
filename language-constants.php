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
* Contains language constants for the plugin
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

// general
define('SAM_LG_TITLE_IP', __( 'IP', SAM_LG_DOMAIN) );
define('SAM_LG_TITLE_START_TIME', __( 'START TIME', SAM_LG_DOMAIN) );
define('SAM_LG_TITLE_END_TIME', __( 'END TIME', SAM_LG_DOMAIN) );
define('SAM_LG_TITLE_OPERATION', __('OPERATION', SAM_LG_DOMAIN) );
define('SAM_LG_TITLE_NUMBER', __('NUMBER', SAM_LG_DOMAIN) );
define('SAM_LG_ADD', __('Add', SAM_LG_DOMAIN) );
define('SAM_LG_CLEAR', __( 'Clear', SAM_LG_DOMAIN) );
define('SAM_LG_ALL', __( 'All', SAM_LG_DOMAIN) );
define('SAM_LG_YES', __('Yes', SAM_LG_DOMAIN) );
define('SAM_LG_NO', __('No', SAM_LG_DOMAIN) );
define('SAM_LG_OK', __('Ok', SAM_LG_DOMAIN) );
define('SAM_LG_REMOVE', __('Remove', SAM_LG_DOMAIN ) );


// months
define('SAM_LG_JANUARY', __( 'Janaury', SAM_LG_DOMAIN) );
define('SAM_LG_FEBRUARY', __('February', SAM_LG_DOMAIN) );
define('SAM_LG_MARCH', __('March', SAM_LG_DOMAIN) );
define('SAM_LG_APRIL', __('April', SAM_LG_DOMAIN) );
define('SAM_LG_MAY', __( 'May', SAM_LG_DOMAIN) );
define('SAM_LG_JUNE', __( 'June', SAM_LG_DOMAIN) );
define('SAM_LG_JULY',  __( 'July', SAM_LG_DOMAIN) );
define('SAM_LG_AUGUST', __( 'August', SAM_LG_DOMAIN) );
define('SAM_LG_SEPTEMBER', __('September', SAM_LG_DOMAIN) );
define('SAM_LG_OCTOBER', __('October', SAM_LG_DOMAIN) );
define('SAM_LG_NOVEMBER', __('November', SAM_LG_DOMAIN) );
define('SAM_LG_DECEMBER', __('December', SAM_LG_DOMAIN) );


//pages
define('SAM_LG_PAGE_SPLASH_SCREEN', __('Splash screen', SAM_LG_DOMAIN) );
define('SAM_LG_PAGE_ACCESS_DENIED', __('Access denied screen', SAM_LG_DOMAIN) );
define('SAM_LG_PAGE_BANLIST', __('Ban list', SAM_LG_DOMAIN) );
define('SAM_LG_PAGE_BANLIST', __('Black list', SAM_LG_DOMAIN) );


// general
define('SAM_LG_GENERAL_PAGE_NAME', __( 'General settings' , SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_CONDITIONS', __('Ban conditions', SAM_LG_DOMAIN) );
define('SAM_LG_BAN_PERIOD', __('Ban period', SAM_LG_DOMAIN) );
define('SAM_LG_ADD_TO_BLACKLIST_CONDITION', __( 'Conditions for adding to blacklist', SAM_LG_DOMAIN) );
define('SAM_LG_EMAIL_IF_ADDED_TO_BANLIST',  __('Email if IP added to banlist', SAM_LG_DOMAIN )  );
define('SAM_LG_ENTER_EMAIL', __('enter valid email', SAM_LG_DOMAIN ) );
define('SAM_LG_TIME_SECONDS', __('Time(seconds)', SAM_LG_DOMAIN ) );
define('SAM_LG_NUMBER_OF_REQUESTS', __('Number of requests', SAM_LG_DOMAIN ) );
define('SAM_LG_NUMBER_OF_BANS', __('Number of bans', SAM_LG_DOMAIN ) );
define('SAM_LG_AJAX_ERROR', __( 'Error, try later', SAM_LG_DOMAIN ) );
define('SAM_LG_AJAX_ERROR_BAD_IP', __('Incorrect IP', SAM_LG_DOMAIN ) );
define('SAM_LG_AJAX_ERROR_IP_EXISTS_IN_BLACKLIST', __("Can't add IP. Such IP already added to blacklist", SAM_LG_DOMAIN ) );
define('SAM_LG_AJAX_ERROR_IP_EXISTS_IN_BANLIST', __( "Can't add IP. Such IP already added to banlist", SAM_LG_DOMAIN ) );

define('SAM_LG_AJAX_ERROR_BAD_PERIOD', __("Can't add IP. Incorrect period", SAM_LG_DOMAIN ) );

// splash screen
define('SAM_LG_SPLASH_SCREEN_PAGE_TITLE', __( 'Splash screen settings', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_GENERAL', __('General', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_ADVANCED', __( 'Advanced', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_SHOW', __( 'Show splash screen', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_TITLE', __( 'Title', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_MESSAGE', __( 'Message', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_CUSTOM_CSS', __( 'Custom css', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_USE_CUSTOM_SCREEN', __( 'Use custom screen', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_CUSTOM_HTML_SCREEN', __('Custom screen html', SAM_LG_DOMAIN ) );
define('SAM_LG_SPLASH_CUSTOM_TEXT', __('If specified, it overrides the default splash screen', SAM_LG_DOMAIN ) );
define('SAM_LG_BODY_HTML_CONTENT', __('Body html content', SAM_LG_DOMAIN ) );


// access denied
define('SAM_LG_ACCESS_DENIED_PAGE_TITLE', __('Access denied screen settings', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_GENERAL', __('General', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_ADVANCED', __('Advanced', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_GENERAL_WINDOW_TITLE', __('Title', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_GENERAL_MESSAGE', __('Message', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_ADVANCED_SHOW_WINDOW', __('Show window', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_ADVANCED_CSS_STYLES', __( 'CSS styles', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_ADVANCED_HTML', __( 'HTML structure', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_ADVANCED_HELP_TEXT', __( 'If specified, it overrides the default window', SAM_LG_DOMAIN ) );
define('SAM_LG_ACCESS_DENIED_ADVANCED_BODY_CONTENT', __('Body html content', SAM_LG_DOMAIN ) );


// banlist
define('SAM_LG_BAN_PAGE_TITLE', __('Banlist operations', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_BANLIST_SECTION', __('Banlist', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_NUMBER_OF_BANS_LIST_SECTION', __('Number of bans list', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_CLEAR_BANLIST', __('Clear banlist', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_ADD_IP_TO_BANLIST', __('Add IP to the banlist', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_BANLIST', __('Banlist', SAM_LG_DOMAIN ));
define('SAM_LG_BAN_PAGE_CLEAR_NUMBER_OF_BANS_LIST', __('Clear number of bans list', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_NUMBER_OF_BANS_LIST', __('Number of bans list', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_BAN_PERIOD', __('Ban period(seconds)', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_REMOVE_IP_FROM_BANLIST_Q', __( 'Are you sure you want to remove the IP', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_CLEAN_BANLIST_Q', __('Are you sure you want to clear banlist?', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_REMOVE_IP_FROM_NUMBER_OF_BANS_LIST_Q', __('Are you sure you want to remove the IP?', SAM_LG_DOMAIN ) );
define('SAM_LG_BAN_PAGE_CLEAN_NUMBER_OF_BANS_LIST_Q', __('Are you sure you want to clear the number of bans list?', SAM_LG_DOMAIN ) );

// blacklist
define('SAM_LG_BLACKLIST_PAGE_TITLE', __('Blacklist settings', SAM_LG_DOMAIN ));
define('SAM_LG_BLACKLIST_PAGE_CLEAR_BLACKLIST', __('Clear the blacklist', SAM_LG_DOMAIN ));
define('SAM_LG_BLACKLIST_PAGE_ADD_IP_TO_BLACKLIST', __('Add IP to the blacklist', SAM_LG_DOMAIN ));
define('SAM_LG_BLACKLIST_PAGE_BLACKLIST', __('Black list', SAM_LG_DOMAIN ));
define('SAM_LG_BLACKLIST_PAGE_REMOVE_IP_FROM_BLACKLIST_Q', __('Are you sure you want to remove the IP ', SAM_LG_DOMAIN ));
define('SAM_LG_BLACKLIST_PAGE_CLEAR_BLACKLIST_Q', __('Are you sure you want to clear blacklist?', SAM_LG_DOMAIN ));


// statistics
define('SAM_LG_STATISTICS_PAGE_TITLE', __('Statistics', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_PAGE_STATISTICS_OF_REQUESTS', __('Statistics of requests', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_PAGE_STATISTICS_CLEAR_STATISTICS', __('Clear statistics', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_PAGE_STATISTICS_FILTER', __('Filter', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_PAGE_STATISTICS_REFERER', __('Referer', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_PAGE_STATISTICS_APPLY_FILTER', __('Apply filter', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_PAGE_STATISTICS_NUMBER_OF_REQUESTS', __('Number of requests', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_REMOVE_INFO', __('The statistics will be removed including specified dates', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_FROM', __('From', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_TILL', __('Till', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_CLEAR', __('Clear statistics', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_CLEAR_Q', __('Do you really want to remove statistics between the dates(included):', SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_CLEAR_START_LATER_ERROR', __("Start period can't be later than end period!", SAM_LG_DOMAIN ));
define('SAM_LG_STATISTICS_ENTER_CUSTOM_REFERER', __("Enter custom referer", SAM_LG_DOMAIN ));


define('SAM_LG_SITE', __('site', SAM_LG_DOMAIN ) );
define('SAM_LG_CUSTOM', __('custom', SAM_LG_DOMAIN ) );



?>