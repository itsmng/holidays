<?php
/*/
yasumi
holidayapi
fullcalendar.io
gg calandar API

itsm-ng/front/calendar.php

/*/

/**
 * Init plugin
 * Register hooks
 *
 * @return void
 */
function plugin_init_holidays() : void {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['holidays'] = true;

   if (Session::haveRight("profile", UPDATE)) {
      $PLUGIN_HOOKS['config_page']['holidays'] = 'front/config.form.php';
   }

}

/**
 * Define Name,Version,Author for plugin manager
 *
 * @return array
 */
function plugin_version_holidays() : array {
   return array('name'           => "Holidays Plugin",
                'version'        => '2.0',
                'author'         => 'Esteban Hulin, Minzord',
                'license'        => 'GPLv3+',
                'homepage'       => 'https://github.com/itsmng/holidays');
 }

 /**
  * Check if ITSM is installed and if vendors exists
  *
  * @return boolean
  */
 function plugin_holidays_check_prerequisites() : bool {
   if (version_compare(ITSM_VERSION, '2.0', 'lt')) {
      echo "This plugin requires ITSM >= 2.0";
      return false;
   }
   if (!is_readable(__DIR__ . '/vendor/autoload.php') || !is_file(__DIR__ . '/vendor/autoload.php')) {
      echo "Run composer install --no-dev in the plugin directory<br>";
      return false;
   }
   return true;
}
