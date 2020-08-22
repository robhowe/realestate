-- units_update.sql
--

--
-- Update `sqft` column of `units` table as per `floorplans` table.
--

UPDATE `units` SET `sqft` = (SELECT `floorplans`.`sqft` FROM `floorplans` WHERE `floorplans`.`id` = `units`.`floorplan_id`) WHERE `sqft` IS NULL;

