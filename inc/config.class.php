<?php

use Yasumi\Yasumi;
require '../vendor/autoload.php';

class PluginHolidaysConfig extends CommonDBTM {

   /**
    * Display the configuration form
    *
    * @param [type] $postData
    * @return void
    */
   function showConfigForm($postData) : void {
        global $DB;

        $criteria = "SELECT * FROM glpi_plugin_holidays_countrylist";
        $iterators = $DB->request($criteria);

        foreach($iterators as $iterator) {
            $country[$iterator['country']] = $iterator['country'];
        }

        $form = [
			'action' => './config.form.php',
			'buttons' => [
				[
					'type' => 'submit',
					'name' => 'update',
					'value' => __('Update'),
					'class' => 'btn btn-secondary',
				]
			],
			'content' => [
				__("Holidays configuration", 'holidays') => [
					'visible' => true,
					'inputs' => [
                        __('Country') => [
                            'name' => 'country',
                            'type' => 'select',
                            'values' => $country,
                            'value' => $postData['country'] ?? '',
                        ],
                        __('Calendar') => [
                            'name' => 'calendar',
                            'type' => 'select',
                            'actions' => getItemActionButtons(['info', 'add'], "Calendar"),
                            'values' => getOptionForItems("Calendar"),
                            'value' => $postData['calendar'] ?? '',
                        ]
					]
				]
			]
		];
		renderTwigForm($form);
    }

    /**
     * Select and insert the holidays in the calendar
     *
     * @param [type] $postData
     * @return boolean
     */
   function addHolidaysToCalendar($postData) : bool
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

      $holidays = Yasumi::create($postData['country'], date("Y"));
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
            'is_perpetual'  => 1
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
      return true;
   }
}
