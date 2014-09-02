<?php
/**
 * EctsCourse.php
 * model class for table EctsCourse
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Florian Bieringer <florian.bieringer@uni-passau.de>
 * @copyright   2014 Stud.IP Core-Group
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 * @since       3.0
 */

class EctsCourse extends SimpleORMap
{
    public function __construct($id = null) {
        $this->db_table = 'ects_courses';
        $this->belongs_to['course'] = array(
            'class_name' => 'Course',
            'foreign_key' => 'seminar_id'
        );
        parent::__construct($id);
    }
}
