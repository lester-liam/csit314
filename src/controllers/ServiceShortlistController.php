<?php

require_once('/var/www/html/entity/CleanerService.php');

class ServiceShortlistController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Returns All User Profiles
    public function getCleanerServiceShortlists($id) {
        return $this->cleanerService->getCleanerServiceShortlists($id);
    }
}

?>