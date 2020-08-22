<?php
/**
 * unit_data_process.php
 *
 * Implementation of unit_data_process which reads in a .json file and outputs .sql files which can be manually
 * run to update particular data fields.
 *
 * WARNING!!! - This class assumes the .json file received is not malicious!
 *              There is a huge SQL Injection risk involved when running this script.
 *              Also, the input file MUST be perfectly formatted in order to be processed successfully.
 *              This class must be made more robust before being considered anything more than an example.
 *
 * TODO - This project is an exercise consisting of reading and writing input files, but in the real-world,
 *        it would be much more useful and optimal to implement it as a process which reads data from an API
 *        and then updates the database directly, avoiding file collisions/permissions/maintenance issues.
 *
 * @category   engrain_test
 * @package    engrain_test
 * @author     Rob Howe <rob@robhowe.com>
 * @copyright  2020 Rob Howe
 * @version    Bitbucket via git: $Id$
 * @link       http://realestate.robhowe.com
 * @since      version 1.0
 */


class unit_data_process
{
    const DATA_DIR = '../data';

    protected $_JSON_filename = self::DATA_DIR . '/unit_data.json';  // input file
    protected $_mkt_rent_filename = self::DATA_DIR . '/update_units_market_rent.sql';  // output file
    protected $_available_date_filename = self::DATA_DIR . '/update_units_available_date.sql';  // output file


    public function __construct($options = NULL)
    {
        // Could possibly put options like updating the input & output filenames here,
        // or flags for allowing overwriting of existing files, or what data fields to process.
    }


    /**
     * This method creates 2 data files, but it could easily be refactored to output one
     * consolidated file using half as many UPDATE stmts.
     */
    public function createSqlScripts()
    {
        // Get the contents of the trusted JSON input file
        $jsonStr = file_get_contents($this->_JSON_filename);

        // Convert to array
        $unitDataArray = json_decode($jsonStr, true);

        // Note - For this project we'll assume the .json file is trusted, properly formatted, and error-free.  :)
        //        Later we can add robust filesize checking and data validation.
        $units = $unitDataArray['Units'];
        $marketRentSqlStr = '';  // the output SQL string
        $availableDateSqlStr = '';  // the output SQL string
        foreach ($units as $unit) {
            $externalId = $unit['ID'];
            $marketRent = $unit['MarketRent'];
            $marketRentSqlStr .= $this->getMarketRentSqlStr($externalId, $marketRent);
            $marketRentSqlStr .= "  \n";  // just to make it human-readable

            // Input file format must be:  MM/DD/YYYY
            $availableDate = date('Y-m-d', strtotime($unit['DateAvailable']));
            $availableDateSqlStr .= $this->getAvailableDateSqlStr($externalId, $availableDate);
            $availableDateSqlStr .= "  \n";  // just to make it human-readable
        }
        $marketRentSqlStr .= "-- COMMIT;\n";  // does this all need to be in a transaction?
        $availableDateSqlStr .= "-- COMMIT;\n";  // does this all need to be in a transaction?

        // Create the 2 SQL files, to presumably be run manually later.
        $result = file_put_contents($this->_mkt_rent_filename, $marketRentSqlStr, LOCK_EX);
        if ($result === false) {
            throw new Exception('Error during output of update_units_market file');
        }
        $result = file_put_contents($this->_available_date_filename, $availableDateSqlStr, LOCK_EX);
        if ($result === false) {
            throw new Exception('Error during output of update_units_avail file');
        }
        return count($units);
    }


    public function getMarketRentSqlStr($externalId, $marketRent)
    {
        // Little Bobby Tables would love this:
        $sql = <<<SQL
            UPDATE `units` SET `market_rent` = $marketRent WHERE `external_id` = $externalId;
SQL;

        return $sql;
    }


    public function getAvailableDateSqlStr($externalId, $availableDate)
    {
        // Little Bobby Tables would love this:
        $sql = <<<SQL
            UPDATE `units` SET `available_date` = '$availableDate' WHERE `external_id` = $externalId;
SQL;

        return $sql;
    }


}

