<?php
namespace App\Services;

use App\Repositories\EnrollmentPlanRepository;

class EnrollmentPlanService {
    private EnrollmentPlanRepository $repository;

    public function __construct(?EnrollmentPlanRepository $repository = null) {
        $this->repository = $repository ?? new EnrollmentPlanRepository();
    }

    public function getEnrollmentPlan(int $studentID): array {
        return $this->repository->getPlanByStudentId($studentID);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function addSectionToPlan(
        int $studentID,
        int $sectionID,
        string $semester = '1st Semester',
        int $priority = 1
    ): int {
        $courseID = $this->repository->getCourseIdBySectionId($sectionID);

        if ($courseID === null) {
            throw new \InvalidArgumentException('Section not found.');
        }

        if ($this->repository->hasPlannedSectionForStudent($studentID, $sectionID, $semester)) {
            throw new \InvalidArgumentException('This section is already in your enrollment plan.');
        }

        if ($this->repository->hasPlannedCourseForStudent($studentID, $courseID, $semester)) {
            throw new \InvalidArgumentException(
                'You already have a planned section for this subject. Remove it first or switch sections from Alternative Sections.'
            );
        }

        $scheduleID = $this->repository->getOrCreateScheduleId($studentID, $semester);

        return $this->repository->addPlannedItem($scheduleID, $sectionID, $priority);
    }

    public function removeSectionFromPlan(int $plannedItemID): void {
        $this->repository->removePlannedItem($plannedItemID);
    }
}
