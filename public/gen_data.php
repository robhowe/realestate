<?php
/**
 * gen_data.php
 *
 * Invokes a class to generate random data for units, then output that data to a JSON file.
 *
 * @category   engrain_test
 * @package    engrain_test
 * @author     Rob Howe <rob@robhowe.com>
 * @copyright  2020 Rob Howe
 * @version    Bitbucket via git: $Id$
 * @link       http://realestate.robhowe.com
 * @since      version 1.0
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// No need for a class auto-loader for a project this simple.  Same goes for namespacing, or even MVC.
require_once '../src/generate_unit_data.php';
?>

Welcome to the unit_data creation process.<br />
<br />

<?php
$obj = new generate_unit_data;
$numCreated = $obj->createUnitDataFile(100);  // generate data for 100 units
?>

Random unit_data has been generated.<br />
The output JSON file contains <?php echo $numCreated; ?> records.<br />
You may now <a href="/">view this data</a> in your browser.<br />
