<?php

require_once('/var/www/html/entity/ServiceHistory.php');

class ViewMatchesController {

    private $serviceHistory;

    public function __construct() {
        $this->serviceHistory = new ServiceHistory();
    }

    // Returns All User Profiles
    public function viewMatches($cleanerID) {
        return $this->serviceHistory->viewMatches($cleanerID);
    }
}

?>