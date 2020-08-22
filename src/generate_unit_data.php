<?php
/**
 * generate_unit_data.php
 *
 * This class generates random data for units, and outputs it to a new .json file.
 *
 * @category   engrain_test
 * @package    engrain_test
 * @author     Rob Howe <rob@robhowe.com>
 * @copyright  2020 Rob Howe
 * @version    Bitbucket via git: $Id$
 * @link       http://realestate.robhowe.com
 * @since      version 1.0
 */


class generate_unit_data
{
    const DATA_DIR = '../data';

    public $_JSON_filename = self::DATA_DIR . '/random_unit_data.json';  // output file

    protected $_unitDataArray = ['Units' => []];  // the random unit data being generated


    public function __construct($options = NULL)
    {
        // Could possibly put options like choosing the output filename here,
        // or flags for allowing overwriting of existing files, or what random data ranges to use.
    }


    /**
     * Create a JSON file with random unit data.
     *
     * NOTE - This new random_unit_data file is not compatible with the unit_data_process since it's missing
     *        numerous fields.  If desired, it could easily be made compatible with minimal refactoring.
     *
     * @param int $num  Number of units to create
     *
     * @return int  Number of units created
     */
    public function createUnitDataFile($num = 100)
    {
        for ($loop=0; $loop < $num; $loop++) {
            $unit = [];
            $unit['UnitNumber'] = $this->generateUnitNumber();
            $unit['Bedrooms'] = rand(0, 3);
            $unit['MarketRent'] = rand(1000, 9999);
            $unit['DateAvailable'] = $this->generateDateAvailable();

            $this->_unitDataArray['Units'][] = $unit;
        }

        // Write it all out to a JSON file.
        $jsonData = json_encode($this->_unitDataArray);
        $result = file_put_contents($this->_JSON_filename, $jsonData, LOCK_EX);
        if ($result === false) {
            throw new Exception('Error during output of random_unit_data file');
        }
        return $num;
    }


    protected function generateUnitNumber()
    {
        // Let's arbitrarily use 3 digits and a letter.
        $num = rand(0, 999);
        $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $letter = substr($charset, rand(0, 25), 1);
        $unitNumber = str_pad($num . $letter, 4, '0', STR_PAD_LEFT);  // add two zero's on the left if needed;

        // It is not required for the unitNumber to be unique across the entire data set.
        //   Otherwise, we could simply check $this->_unitDataArray to ensure uniqueness.

        return $unitNumber;
    }


    /**
     * Generate a random available date anywhere between now and 6 months from now (180 days).
     * In format:  MM/DD/YYYY
     */
    public function generateDateAvailable()
    {
        $days = rand(0, 180);
        $dateAvailable = Date('m/d/Y', strtotime('+' . $days . ' days'));
        return $dateAvailable;
    }


}

