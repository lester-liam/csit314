<?php

require_once "entity/ServiceHistory.php";

class BookingCategoryController {

    private $serviceHistory;

    public function __construct() {
        $this->serviceHistory = new ServiceHistory();
    }

    // Returns All User Profiles
    public function getHoCategories($homeownerID) {
        return $this->serviceHistory->getHoCategories($homeownerID);
    }
}

?>