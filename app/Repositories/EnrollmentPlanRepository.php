<?php
namespace App\Repositories;

use App\Core\Database;

class EnrollmentPlanRepository {
    private Database $db;

    public function __construct(?Database $db = null) {
        $this->db = $db ?? new Database();
    }

    public function getPlanByStudentId(int $studentID): array {
        return $this->db->query('
            SELECT pi.plannedItemID, pi.sectionID, c.courseID, c.courseCode, c.courseName, c.credits,
                   s.sectionCode, s.timeslot, s.room, s.capacity,
                   (SELECT COUNT(*) FROM PlannedItem p WHERE p.sectionID = s.sectionID) AS interestedCount,
                   (SELECT COUNT(*) FROM PlannedItem p WHERE p.sectionID = s.sectionID AND p.createdAt < pi.createdAt) AS studentsBefore,
                   pi.enrollmentStatus, pi.priority
            FROM PlannedItem pi
            JOIN Schedule sch ON pi.scheduleID = sch.scheduleID
            JOIN Section s ON pi.sectionID = s.sectionID
            JOIN Course c ON s.courseID = c.courseID
            WHERE sch.studentID = ?
            ORDER BY c.courseCode
        ', [$studentID]);
    }

    public function getCourseIdBySectionId(int $sectionID): ?int {
        $row = $this->db->queryOne(
            'SELECT courseID FROM Section WHERE sectionID = ?',
            [$sectionID]
        );

        return $row ? (int) $row['courseID'] : null;
    }

    public function hasPlannedCourseForStudent(int $studentID, int $courseID, string $semester): bool {
        $row = $this->db->queryOne('
            SELECT pi.plannedItemID
            FROM PlannedItem pi
            JOIN Schedule sch ON pi.scheduleID = sch.scheduleID
            JOIN Section s ON pi.sectionID = s.sectionID
            WHERE sch.studentID = ? AND s.courseID = ? AND sch.semester = ?
            LIMIT 1
        ', [$studentID, $courseID, $semester]);

        // PDO fetch() returns false when no row exists (not null)
        return $row !== false;
    }

    public function hasPlannedSectionForStudent(int $studentID, int $sectionID, string $semester): bool {
        $row = $this->db->queryOne('
            SELECT pi.plannedItemID
            FROM PlannedItem pi
            JOIN Schedule sch ON pi.scheduleID = sch.scheduleID
            WHERE sch.studentID = ? AND pi.sectionID = ? AND sch.semester = ?
            LIMIT 1
        ', [$studentID, $sectionID, $semester]);

        return $row !== false;
    }

    public function getOrCreateScheduleId(int $studentID, string $semester, int $academicYear = 2026): int {
        $schedule = $this->db->queryOne(
            'SELECT scheduleID FROM Schedule WHERE studentID = ? AND semester = ? LIMIT 1',
            [$studentID, $semester]
        );

        if ($schedule) {
            return (int) $schedule['scheduleID'];
        }

        $this->db->execute(
            'INSERT INTO Schedule (studentID, semester, academicYear, status) VALUES (?, ?, ?, ?)',
            [$studentID, $semester, $academicYear, 'draft']
        );

        return (int) $this->db->lastInsertId();
    }

    public function addPlannedItem(
        int $scheduleID,
        int $sectionID,
        int $priority = 1
    ): int {
        $this->db->execute(
            'INSERT INTO PlannedItem (scheduleID, sectionID, priority) VALUES (?, ?, ?)',
            [$scheduleID, $sectionID, $priority]
        );

        return (int) $this->db->lastInsertId();
    }

    public function removePlannedItem(int $plannedItemID): bool {
        $this->db->execute('DELETE FROM PlannedItem WHERE plannedItemID = ?', [$plannedItemID]);
        return true;
    }
}
