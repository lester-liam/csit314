<?php

require_once('/var/www/html/entity/ServiceHistory.php');

class SearchBookingsController {

    private $serviceHistory;

    public function __construct() {
        $this->serviceHistory = new ServiceHistory();
    }

    // Search User Account, Return Array[0 to Many] of User Profiles
    public function searchBookings(
        $homeownerID,
        $searchTerm,
        $category,
        $dateOption
    ) {
        return $this->serviceHistory->searchBookings(
                                        $homeownerID,
                                        $searchTerm,
                                        $category,
                                        $dateOption
                                      );
    }
}
?>