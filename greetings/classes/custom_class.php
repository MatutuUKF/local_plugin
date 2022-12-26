<?php

// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.


defined('MOODLE_INTERNAL') || die();


require_once('C:/xampp/htdocs/moodle41/cohort/lib.php');
require "search.php";
require_once($CFG->libdir . '/tablelib.php');

/**
 * MyCustomTable class for displaying a table of cohorts.
 *
 * @package    local_greetings
 * @copyright  2022 Your name <matus4286@gmail.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MyCustomTable extends table_sql
{

    /**
     * Constructor for the MyCustomTable class.
     *
     * @param int $uniqueid Unique id of the table.
     */
    public function __construct($uniqueid)
    {
        parent::__construct($uniqueid);

        // Set up the table options.
        //$this->sortable(true, 'id', SORT_ASC);
        //$this->pageable(true);
        //$this->is_downloadable(true);
    }

    public function out($pagesize, $useinitialsbar, $downloadhelpbutton = '')
    {   global $USER;

        $mform = new MyForm();
        $mform->display();
        $filterdata = $mform->get_data();
        //manual filtering
        // cohorts
        //$cohorts = cohort_get_user_cohorts($USER->id);
        //$cohortid = 1;
        //manual filtering
        //$coursename = 'Mathematics';

        // Define the columns and column headers for the table.
        $this->define_columns(array('coursename','fullname','cohort','enrolmentdate','status','credits cost'));
        $this->define_headers(array( 'coursename','fullname','cohort','enrolmentdate','status','credits cost'));

        // Set the SQL query for the table.
        $this->set_sql(
            'ue.id, c.fullname as coursename ,u.firstname,u.lastname, cm.cohortid as cohort,
             IF(cc.timecompleted IS NULL, "Not completed", "Completed") AS status,
             FROM_UNIXTIME(ue.timecreated) as enrolmentdate',
            '{course} c 
            INNER JOIN {enrol} e ON c.id = e.courseid 
            INNER JOIN {user_enrolments} ue ON e.id = ue.enrolid 
            INNER JOIN {user} u ON ue.userid = u.id 
            LEFT JOIN {course_completions} cc ON c.id = cc.course AND u.id = cc.userid 
            LEFT JOIN {cohort_members} cm ON u.id = cm.userid',
            'u.deleted = 0 AND u.suspended = 0 AND cm.cohortid = :cohortid',
            array(
                'cohortid' =>  $filterdata->cohortid
            )

        );
        echo $filterdata->cohortid;
        // Call the parent out() method to display the table.
        parent::out($pagesize, $useinitialsbar);
    }


}