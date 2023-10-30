<?php

function plugin_holidays_install() : bool {
    global $DB;
    $migration = new Migration(130);
    
    $countries = array(
        '0'                       => 'Argentina',
        '1'                       => 'Australia',
        '2'                       => 'Austria',
        '3'                       => 'Belgium',
        '4'                       => 'Bosnia',
        '5'                       => 'Brazil',
        '6'                       => 'Canada',
        '7'                       => 'Croatia',
        '8'                       => 'CzechRepublic',
        '9'                       => 'Denmark',
        '10'                       => 'Estonia',
        '11'                       => 'Finland',
        '12'                       => 'France',
        '13'                       => 'Georgia',
        '14'                       => 'Germany',
        '15'                       => 'Greece',
        '16'                       => 'Hungary',
        '17'                       => 'Ireland',
        '18'                       => 'Italy',
        '19'                       => 'Japan',
        '20'                       => 'Latvia',
        '21'                       => 'Lithuania',
        '22'                       => 'Luxembourg',
        '23'                       => 'Netherlands',
        '24'                       => 'NewZealand',
        '25'                       => 'Norway',
        '26'                       => 'Poland',
        '27'                       => 'Portugal',
        '28'                       => 'Romania',
        '29'                       => 'Russia',
        '30'                       => 'Slovakia',
        '31'                       => 'SouthAfrica',
        '32'                       => 'SouthKorea',
        '33'                       => 'Spain',
        '34'                       => 'Sweden',
        '35'                       => 'Switzerland',
        '36'                       => 'Turkey',
        '37'                       => 'Ukraine',
        '38'                       => 'UnitedKingdom',
        '39'                       => 'USA'
    );

    if (!$DB->tableExists("glpi_plugin_holidays_countrylist")) {
        $countrylist = "CREATE TABLE `glpi_plugin_holidays_countrylist` (
            `id` INT(11) NOT NULL auto_increment,
            `country` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=innodb  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $DB->queryOrDie($countrylist, "erreur lors de la création de la table de configuration ".$DB->error());

        foreach ($countries as $country) {
            $countrylist = "INSERT INTO `glpi_plugin_holidays_countrylist` (country) VALUES ('$country')";
            $DB->queryOrDie($countrylist, $DB->error());
        }
    }

    $migration->executeMigration();

    return true;
}

function plugin_holidays_uninstall() : bool {
    global $DB;

    $tables = [
        'countrylist',
    ];

    foreach($tables as $table) {
        $tablename = 'glpi_plugin_holidays_' . $table;
        //Create table only if it doesn't exist yet
        if($DB->tableExists($tablename)) {
            $DB->queryOrDie(
                "DROP TABLE `$tablename`",
                $DB->error()
            );
        }
    }

    return true;
}
