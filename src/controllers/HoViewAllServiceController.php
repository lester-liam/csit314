<?php

require_once '/var/www/html/entity/CleanerService.php';

class HoViewAllServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Returns All User Profiles
    public function hoViewAllService() {
        return $this->cleanerService->hoViewAllService();
    }
}

?>