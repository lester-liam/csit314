<?php
require_once "entity/ServiceHistory.php";

class MatchCategoryController {

    private $serviceHistory;

    public function __construct() {
        $this->serviceHistory = new ServiceHistory();
    }

    // Returns All User Profiles
    public function getCategories($cleanerID) {
        return $this->serviceHistory->getCategories($cleanerID);
    }
}

?>