<?php
require_once "entity/CleanerService.php";

class ViewAllCleanerServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Returns All User Profiles
    public function viewAllCleanerService($cleanerID) {
        return $this->cleanerService->viewAllCleanerService($cleanerID);
    }
}

?>