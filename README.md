# Lab Administration & Complaint Management System

A comprehensive web-based application designed to manage computer labs, track system configurations, and handle hardware/software complaints efficiently. This system allows administrators to maintain detailed inventories of lab systems and provides a streamlined interface for users to report issues.

## 🚀 Key Features

* **Lab Management:**
    * **Create Labs:** Add and manage multiple labs with details like Room No, Capacity, and In-charge.
    * **Dynamic Tables:** Automatically creates dedicated database tables for each new lab to store specific system inventories.
    * **Bulk Import:** Support for importing system details via CSV files.
    * **Dynamic Fields:** Admins can configure custom fields for Lab and System details via the configuration manager.

* **Complaint Handling:**
    * **User Reporting:** Simple interface for users to report issues (Hardware, Software, Network).
    * **Dynamic Dropdowns:** Lab names, Room numbers, and System numbers are fetched dynamically from the database.
    * **Status Tracking:** Admins can mark complaints as "Pending", "Partially Completed", or "Completed".
    * **Visual Indicators:** Color-coded rows (Red for Pending, Yellow for Partial) for quick status assessment.
    * **Email Notifications:** Automated email alerts to admins when complaints are lodged or statuses change.
    * **Logs:** Detailed history of all complaint actions, including who resolved the issue and when.

* **Reporting & Export:**
    * **PDF Generation:** Generate professional PDF reports using `jsPDF`.
    * **Export Options:**
        * **Labs Master List:** Overview of all labs.
        * **Lab Systems:** Inventory of a specific lab (optionally including complaint history).
        * **Single System:** Detailed history and specs for a specific computer.
    * **Preview Data:** View data in a popup modal before downloading the PDF.

## 🛠️ Technology Stack

* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** PHP (Native)
* **Database:** MySQL
* **Libraries:**
    * `jsPDF` & `jspdf-autotable` (Client-side PDF generation)

## 📂 Project Structure

* `complaint.php`: User-facing form to submit complaints with dynamic lab/system selection.
* `complaints_info.php`: Admin dashboard to view, filter, and manage complaint statuses.
* `dashboard_overview.php`: Visual overview of total labs, active complaints, and resolved issues.
* `labs_info_form.php`: Form to add new labs and configure their details.
* `manage_config.php`: Admin tool to add/remove dynamic fields (e.g., "OS", "Processor") and manage dropdown options.
* `export_*.php`: Modules for generating PDF reports (Labs, Lab Systems, Specific Systems).
* `db.php`: Database connection configuration.

## ⚙️ Installation & Setup

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/udaybhaNu89/Labs-Admin.git
    cd Labs-Admin
    ```

2.  **Database Setup:**
    * Create a MySQL database named `lab_admin_system` (or your preferred name).
    * Import the `database_structure.sql` file provided in the repository to create the required tables (`users`, `labs_unit`, `complaints`, `dynamic_sections`, etc.).

3.  **Configure Connection:**
    * Open `db.php` and update your database credentials:
        ```php
        $conn = mysqli_connect("localhost", "root", "your_password", "lab_admin_system");
        ```

4.  **Admin Access:**
    * Ensure the `users` table has an admin account. Insert an initial admin user manually if a registration page is not used.

5.  **Run the Application:**
    * Host the files on a local server (e.g., XAMPP, WAMP) or a web server.
    * Navigate to `index.php` (or `dashboard_overview.php` after logging in).

## 📖 Usage Guide

### 1. Setting up Labs
* Navigate to **Lab Hub** > **Add Lab**.
* Enter Lab Name (e.g., "Aryabhatta Lab"). The system will auto-generate a table named `aryabhatta_lab`.
* Upload a CSV file to bulk import system numbers or add them manually.

### 2. Managing Configuration
* Go to **Manage Config**.
* Add new dropdown options (e.g., "Projector Model") or sections.
* Reorder fields as they should appear on the Complaint Form.

### 3. Handling Complaints
* Users submit issues via `complaint.php`.
* Admins check `complaints_info.php`.
    * **Mark Complete:** Moves status to "Completed" (Green).
    * **Partial:** Marks as "Partially Completed" (Yellow) with a comment.
* The system logs every action in `complaints_log`.

### 4. Exporting Data
* Navigate to the **Export Hub**.
* Select the specific Lab or System Number.
* Choose whether to include "Complaint History".
* Click **Preview Data** to check the table, then **Export PDF** to download.

## 📄 License

This project is open-source and available for use and modification.
