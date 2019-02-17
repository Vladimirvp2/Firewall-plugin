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
* Custom access splash screen
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

$htmlVal = get_option( SAM_SPLASHSCREEN_CUSTOM_HTML  );
$cssVal = get_option( SAM_SPLASHSCREEN_CUSTOM_CSS );

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>> 

	<head>
		<title><?php bloginfo('name'); ?></title>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">
			<?php echo $cssVal; ?>
		</style>
	</head>

	<body>

		<?php echo $htmlVal; ?>
				
	</body>
</html>