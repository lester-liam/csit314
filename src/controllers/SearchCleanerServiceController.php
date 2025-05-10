<?php
require_once "entity/CleanerService.php";

class SearchCleanerServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Search User Account, Return Array[0 to Many] of User Profiles
    public function searchCleanerService($cleanerID, $searchTerm) {
        return $this->cleanerService->searchCleanerService($cleanerID, $searchTerm);
    }

}

?>