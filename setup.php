<?php
/*/
yasumi
holidayapi
fullcalendar.io
gg calandar API

itsm-ng/front/calendar.php

/*/

function plugin_init_holidays() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['holidays'] = true;

   if (Session::haveRight("profile", UPDATE)) {
      $PLUGIN_HOOKS['config_page']['holidays'] = 'front/config.form.php';
   }

}

function plugin_version_holidays() {
   return array('name'           => "Holidays Plugin",
                'version'        => '1.0.0',
                'author'         => 'Esteban Hulin',
                'license'        => 'GPLv2+',
                'homepage'       => 'https://github.com/Noblerie',
                'minGlpiVersion' => '9.5');
 }

 function plugin_holidays_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '9.5', 'lt')) {
      echo "This plugin Requires GLPI >= 9.5";
      return false;
   }
   return true;
}

function plugin_holidays_check_config($verbose=false) {

   return true;
}