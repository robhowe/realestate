<?php
/**
 * run_class.php
 *
 * Invokes the class and runs the SQL script creation process.
 *
 * @category   engrain_test
 * @package    engrain_test
 * @author     Rob Howe <rob@robhowe.com>
 * @copyright  2020 Rob Howe
 * @version    Bitbucket via git: $Id$
 * @link       http://realestate.robhowe.com
 * @since      version 1.0
 */


// No need for a class auto-loader for a project this simple.  Same goes for namespacing, or even MVC.
require_once '../src/unit_data_process.php';
?>

Welcome to the unit_data processing process.<br />
<br />

<?php
$obj = new unit_data_process;
$numProcessed = $obj->createSqlScripts();
?>

The unit_data input file has just been processed.  It contained <?php echo $numProcessed; ?> records.<br />
The corresponding SQL scripts have been generated.<br />
You may now run them manually on the database.<br />
Process complete.<br />
