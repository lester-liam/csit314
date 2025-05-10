<?php

require_once('/var/www/html/entity/ServiceHistory.php');

class SearchMatchesController {

    private $serviceHistory;

    public function __construct() {
        $this->serviceHistory = new ServiceHistory();
    }

    // Search User Account, Return Array[0 to Many] of User Profiles
    public function searchMatches(
        $cleanerID,
        $searchTerm,
        $category,
        $dateOption
    ) {
        return $this->serviceHistory->searchMatches(
                                        $cleanerID,
                                        $searchTerm,
                                        $category,
                                        $dateOption
                                      );
    }
}
?>