--
-- Database: `lab_admin_system`
--

-- --------------------------------------------------------

--
-- 1. Table structure for `users` (Admin Authentication)
--
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `create_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

-- --------------------------------------------------------

--
-- 2. Table structure for `dynamic_sections`
-- Stores configuration for the Complaint Form fields (e.g., Lab Name, System Number)
--
CREATE TABLE IF NOT EXISTS `dynamic_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `input_type` varchar(50) NOT NULL, -- e.g., 'dropdown', 'text', 'email'
  `display_order` int(11) DEFAULT 0,
  `is_unique` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- 3. Table structure for `labs_sections`
-- Stores configuration for extra columns in the Labs Management page
--
CREATE TABLE IF NOT EXISTS `labs_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `input_type` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- 4. Table structure for `systems_sections`
-- Stores configuration for columns in individual system tables (e.g., OS, Processor)
--
CREATE TABLE IF NOT EXISTS `systems_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- 5. Table structure for `lab_series_config`
-- Stores numbering series settings (Prefix, Start, End) for generating system numbers
--
CREATE TABLE IF NOT EXISTS `lab_series_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_name` varchar(255) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `start_no` int(11) DEFAULT NULL,
  `end_no` int(11) DEFAULT NULL,
  `padding` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lab_name` (`lab_name`)
);

-- --------------------------------------------------------

--
-- 6. Table structure for `labs_unit`
-- Master list of all labs.
--
CREATE TABLE IF NOT EXISTS `labs_unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_name` varchar(255) NOT NULL,
  `lab_code` varchar(50) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `building_name` varchar(100) DEFAULT NULL,
  `lab_incharge` varchar(100) DEFAULT NULL,
  `programmer` varchar(100) DEFAULT NULL,
  `projector` varchar(50) DEFAULT NULL,
  `no_of_system_capacity` int(11) DEFAULT 0,
  `no_of_systems_present` int(11) DEFAULT 0,
  `lab_name_table` varchar(255) DEFAULT NULL, -- Stores the table name for specific lab systems (e.g., 'lab_01')
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- 7. Table structure for `complaints`
-- Stores the current active state of complaints.
--
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `other_details` text,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `issue_fixed_at` datetime DEFAULT NULL,
  `partially_completed_at` datetime DEFAULT NULL,
  `complaint_modified_by` varchar(100) DEFAULT NULL,
  -- Note: Dynamic columns (e.g., lab_name, system_number) are added via ALTER TABLE by your PHP script
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- 8. Table structure for `complaints_log`
-- Stores the history of all complaints (including deleted or modified states).
--
CREATE TABLE IF NOT EXISTS `complaints_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `other_details` text,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `issue_fixed_at` datetime DEFAULT NULL,
  `partially_completed_at` datetime DEFAULT NULL,
  `complaint_modified_by` varchar(100) DEFAULT NULL,
  -- Note: Dynamic columns here must match the 'complaints' table
  PRIMARY KEY (`id`)
);