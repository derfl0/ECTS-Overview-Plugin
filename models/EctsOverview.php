<?php

/**
 * EctsOverview.php
 * model class for table EctsOverview
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
class EctsOverview extends SimpleORMap {

    public function __construct($id = null) {
        $this->db_table = 'ects_overview';
        $this->has_many['courses'] = array(
            'class_name' => 'EctsCourse'
        );
        parent::__construct($id);
        
        // YOU SHALL NOT PASS!!!!!!!!!!!!
        if ($this->user_id && $this->user_id != $GLOBALS['user']->id) {
            throw new AccessDeniedException("YOU SHALL NOT PASS");
        }
    }

    public function reload() {
            $stmt = DBManager::get()->prepare('SELECT seminare.* FROM seminar_user JOIN seminare USING (seminar_id) LEFT JOIN ects_courses ON (overview_id = ? AND ects_courses.seminar_id = seminar_user.seminar_id) WHERE user_id = ? AND ects_courses.seminar_id is null AND seminare.ects IS NOT NULL');
            $stmt->execute(array($this->id, $GLOBALS['user']->id));
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $course = Course::import($data);
                EctsCourse::create(array(
                    'overview_id' => $this->id,
                    'seminar_id' => $course->id,
                    'semester_id' => $course->start_semester->id,
                    'ects' => $course->ects,
                    'active' => $this->autofill
                ));
            }
    }

}
