<?php

require_once('/var/www/html/entity/CleanerService.php');

class ServiceViewController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Returns All User Profiles
    public function getCleanerServiceViews($id) {
        return $this->cleanerService->getCleanerServiceViews($id);
    }
}

?>