<?php

require_once('/var/www/html/entity/ServiceHistory.php');

class ViewBookingsController {

    private $serviceHistory;

    public function __construct() {
        $this->serviceHistory = new ServiceHistory();
    }

    // Returns All User Profiles
    public function viewBookings($homeownerID) {
        return $this->serviceHistory->viewBookings($homeownerID);
    }
}

?>