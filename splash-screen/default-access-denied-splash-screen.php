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
* Default access denied screen
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

$title_ = esc_attr( get_option(SAM_ACCESS_DENIED_TITLE, SAM_DEFAULT_ACCESS_DENIED_TITLE) );
$message_ = esc_attr( get_option(SAM_ACCESS_DENIED_MESSAGE, SAM_DEFAULT_ACCESS_DENIED_MESSAGE) );
$cssVal = get_option( SAM_CUSTOM_DENIED_WINDOW_CSS );

?>



<!DOCTYPE html>
<html <?php language_attributes(); ?>> 

	<head>
		<title><?php bloginfo('name'); ?></title>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="description" content="<?php bloginfo('description'); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel='stylesheet' id='sam_splash_screen_css-css'  href='<?php echo SAM_BASENAME ?>css/sam_splash_screen.css' type='text/css' media='all' />
		
		<style type="text/css">
			<?php echo $cssVal; ?>
		</style>
		
	</head>

	<body>

		<div class="fw-splash-screen">
			<h1 class="fw-h1">  <?php echo $title_; ?>  </h1>
			<p class="fw-splash-desc"> <?php echo $message_; ?> </p> 
		</div>
				
	</body>
</html>







