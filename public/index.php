<?php
/**
 * index.php
 *
 * Real estate code challenge.
 *
 * NOTE - For purposes of this exercise, I've implemented sorting via a js plugin and filtering using
 *        the GET method.  For an enterprise-level app, I'd likely choose a pure AJAX solution for both.
 *
 * @category   engrain_test
 * @package    engrain_test
 * @author     Rob Howe <rob@robhowe.com>
 * @copyright  2020 Rob Howe
 * @version    Bitbucket via git: $Id$
 * @link       http://realestate.robhowe.com
 * @since      version 1.0
 */

// For a project this simple, we're not even going to use MVC.

require_once '../src/generate_unit_data.php';

// Process any data if this was a GET request.
$filterUnits = filter_input(INPUT_GET, 'filter-units', FILTER_SANITIZE_NUMBER_INT);
// For this exercise, we won't do much validating.
$filterUnits = empty($filterUnits) ? 0 : $filterUnits;
// We'll now just assume $filterUnits is one of:  0, 30, 60, 90

$genUnitDataObj = new generate_unit_data;

// Not at all MVC here...
function filterUnits($val) {
    global $filterUnits;
    $dateAvailable = new DateTime($val['DateAvailable']);
    $dateAvailableStr = $dateAvailable->sub(new DateInterval('P'.$filterUnits.'D'))->format('m/d/Y');
    return intval(strtotime($dateAvailableStr) < strtotime('now'));
}

?>

<head>
    <!-- I chose to implement table sorting here using jQuery and the tablesorter plugin, because
         why reinvent the wheel.  And, it's nicer than forcing a page-load just to sort.
    -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
    <script>$(function() { $("#unit-table").tablesorter(); });</script>  <!-- Init the tablesorter plugin -->

    <!-- Instead of making the filter functionality use a Submit button, let's make it auto-load on select. -->
    <script>$(function() { $('#filter-units').on('change', function() {
                    window.location = '/?filter-units=' + this.value;
                });
            });
    </script>

    <!-- Note - for this simple project we won't even use an external .css file for our styles -->
    <style>
        table {
            margin: 0px;
            margin-left: 20px;
            border-collapse: collapse;
            border: 1px solid black;
        }
        tr:nth-child(2n+1) {
            background-color: #F7F7F7;
        }
        th {
            cursor: pointer;
            font-weight: bold;
            background-color: #DDD;
            padding: 10px;
            border: 1px solid #888;
        }
        td {
            text-align: right;
            padding: 4px;
            border: 1px solid #CCC;
        }
    </style>

</head> 

Welcome to my Real Estate unit_data viewing code challenge webpage.<br />
<br />
To run the "SQL script creation process", <a href="/run_class.php">click here</a>.<br />
To generate new random unit_data, <a href="/gen_data.php">click here</a>.<br />
<br />
Here is a sortable view of the currently generated unit data:<br />
Click on the table header to sort by that column.<br />
<br />

<?php
    // Get the contents of the newly-generated random JSON data file.
    $jsonStr = file_get_contents($genUnitDataObj->_JSON_filename);

    // Note - For this project we'll assume the .json file is trusted, properly formatted, and error-free.  :)
    //        Later we can add robust filesize checking and data validation to avoid XSS, etc.

    // Convert to array
    $unitDataArray = json_decode($jsonStr, true);

    $units = $unitDataArray['Units'];

    // Let's do any filtering here, rather than in the "View" below.
    //   We would sort here too, if we were using a server-side solution for that.
    if ($filterUnits) {
        $units = array_filter($units, 'filterUnits');
    }
?>

<form action="/" method="get" style="margin-left: 20px;">
    <!-- This could easily be refactored to support a dynamic date range using "from" "to" vars -->
    <label for="filter-units">Show units available within</label>
    <select name="filter-units" id="filter-units">
        <option value="30" <?php if ($filterUnits == 30) { echo 'selected="selected"'; } ?>>30 days</option>
        <option value="60" <?php if ($filterUnits == 60) { echo 'selected="selected"'; } ?>>30 - 60 days</option>
        <option value="90" <?php if ($filterUnits == 90) { echo 'selected="selected"'; } ?>>60 - 90 days</option>
        <option value="0" <?php if ($filterUnits == 0) { echo 'selected="selected"'; } ?>>all</option>
    </select>
</form>

<table id="unit-table" class="tablesorter">
    <thead>
        <tr>
            <th>Unit Number</th>
            <th>Bedrooms</th>
            <th>Market Rent</th>
            <th>Available Date</th>
        </tr>
    </thead>
    <tbody>
<?php
    // We'll just assume this data is sanitized and not riddled with XSS.  ;)
    foreach ($units as $unit) {
        echo "<tr>\n";
        echo "<td>{$unit['UnitNumber']}</td>";
        echo "<td>{$unit['Bedrooms']}</td>";
        echo "<td>{$unit['MarketRent']}</td>";
        echo "<td>{$unit['DateAvailable']}</td>\n";
        echo "</tr>\n";
    }
?>

    </tbody>
</table>
<br />
