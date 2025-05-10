<?php

require_once('/var/www/html/entity/Shortlist.php');

class ShortlistServiceController {

    private $shortlist;

    public function __construct() {
        $this->shortlist = new Shortlist();
    }

    // Delete Cleaner Service
    public function newShortlist($homeownerID, $serviceID) {
        return $this->shortlist->newShortlist($homeownerID, $serviceID);
    }
}

// `updateCleanerService.php` Script
// Executes when Delete Button is Click (GET Request)
if (isset($_GET['homeownerID']) && isset($_GET['serviceID'])) {

    // Convert ID to Integer Value
    $homeownerID = (int) $_GET['homeownerID'];
    $serviceID = (int) $_GET['serviceID'];

    // Instantiate New Controller & Delete
    $controller = new ShortlistServiceController();
    $status = $controller->newShortlist($homeownerID, $serviceID);

    // Success / Fail
    if ($status) {
        $response = [
            'isSuccess' => true,
        ];
    } else {
        $response = [
            'isSuccess' => false,
        ];
    }

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>