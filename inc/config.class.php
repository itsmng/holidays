<?php
require '../vendor/autoload.php';

class PluginHolidaysConfig extends CommonDBTM {

   
   
   function showConfigForm($postData) {
        global $DB;
        
        $criteria = "SELECT * FROM glpi_plugin_holidays_countrylist";
        $iterators = $DB->request($criteria);

        echo "<form method='post' action='./config.form.php' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='2'>".__("Holidays configuration", 'holidays')."</th></tr>";       
        echo "<tr class='tab_bg_1'>";
        echo "<td>".__("Country")."</td>";
        echo "<td id='country' name='country'>";
        foreach($iterators as $iterator) {
               $country[$iterator['country']] = $iterator['country'];
            }
         if ($postData)
            Dropdown::showFromArray("country", $country, ['value' => $postData['country']]);
         else
            Dropdown::showFromArray("country", $country);
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>".__("Calendar")."</td>";
        echo "<td>";
        if ($postData)
         Calendar::dropdown([
            'name' => 'calendar',
            'value' => $postData['calendar']
         ]);
         else
        Calendar::dropdown(
           ["name" => "calendar"]
         );
        echo "</td></tr>";
        echo "<tr class='tab_bg_1'><td class='center' colspan='2'>";
        echo "<input type='submit' name='update' class='submit'>";
        echo "</td></tr>";
        echo "</table>";
        Html::closeForm();
    }
   
   function addHolidaysToCalendar($postData)
   {
      $holiday = new Holiday();
      $cid = $postData['calendar'];
      $calendar_holiday = new Calendar_Holiday();

      if (!$cid) {
         Session::addMessageAfterRedirect(
            sprintf(__("Please chose a calendar!", 'holidays')),
            true,
            ERROR
         );
         return false;
      }

      $holidays = Yasumi\Yasumi::create($postData['country'], date("Y"));
      $holidayList = $holidays->getHolidayDates();

      foreach ($holidayList as $hName => $hDate) {

         if (str_contains($hName, 'substitute'))
            continue; 

         $new_holiday = [
            'name'   => $hName,
            'entities_id'   => 0,
            'is_recursive'   => 0,
            'comment'   => '',
            'begin_date'   => date('Y-m-d',strtotime($hDate)),
            'end_date'   => date('Y-m-d',strtotime($hDate)),
            'is_perpetual'  => 0
         ];

         $hid = $holiday->add($new_holiday);

         $new_calendar = [
            'calendars_id'   => $cid,
            'holidays_id'  => $hid
         ];
         $calendar_holiday->add($new_calendar);

         if ($calendar_holiday) {
            Session::addMessageAfterRedirect(
               sprintf(__("Holidays has been added to the calendar!", 'holidays')),
               true,
               INFO
            );
         }

      }
   }
}