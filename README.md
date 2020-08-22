## Engrain Developer test

Feel free to email Tim Duff <[redacted]> with any questions you have on this test. 

  1. Use the `engrain_test.sql` script (in the database folder) to create a mysql database.
 
  2. Create a SQL query that will update the sqft in the `units` table based on 
     the related `floorplans` data.  Save that query as `units_update.sql` and 
     put it in the `database` folder.
 
  3. Write a PHP class that will consume the `data/unit_data.json` file and create a 
     list of SQL queries that can be used to update the following fields for the 
     units in the `units` table:

      `market rent`

      `available date`
 
  4. Save the PHP class in the src folder.
 
  5. Write a separate PHP file in the `public` folder called `run_class.php` that 
     invokes the class and runs the SQL script creation process.

  6. Write a PHP script that generates data for 100 units.  Each unit should be assigned 
     a unitnumber, a random available date anywhere between now and
     6 months from now (180 days), a random value of 0 to 3 bedrooms, and a random 4 digit market rent. 
     Have the script save the data to a new JSON file.   

  7. Write a PHP page called `index.php` that will consume the new JSON data
     file and display the following fields into a table:

      `Unit Number`

      `Bedrooms`

      `Market Rent`

      `Available Date`

  8. Add links to the page that sorts the data by bedroom count, and filters the data by  
     units available within 30 days, units available in 30 - 60 days, and units available in 60 - 90 days. 
    
  9. Check everything in to the master branch.  Zip or gzip the engrain_test folder and email it to [redacted].
