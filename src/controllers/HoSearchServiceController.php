<?php

require_once '/var/www/html/entity/CleanerService.php';

class HoSearchServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Search User Account, Return Array[0 to Many] of User Profiles
    public function hoSearchService($searchTerm) {
        return $this->cleanerService->hoSearchService($searchTerm);
    }

}

?>