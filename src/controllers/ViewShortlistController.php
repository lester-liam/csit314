<?php

require_once('/var/www/html/entity/Shortlist.php');

class ViewShortlistController {

    private $shortlist;

    public function __construct() {
        $this->shortlist = new Shortlist();
    }

    // Returns Cleaner Service
    public function viewShortlist($homeownerID, $serviceID) {
        return $this->shortlist->viewShortlist($homeownerID, $serviceID);
    }
}


if (isset($_GET['homeownerID']) && isset ($_GET['serviceID'])) {

    $homeownerID = (int) $_GET['homeownerID'];
    $serviceID = (int) $_GET['serviceID'];

    $viewController = new ViewShortlistController();

    $shortlist = $viewController->viewShortlist($homeownerID, $serviceID);

    if (is_null($shortlist)) {
        echo json_encode(['error' => 'Invalid request']);
        exit();
    }

    // Prepare data to send back to JavaScript
    $response = [
        'category' => $shortlist->getCategory(),
        'serviceName' => $shortlist->getServiceName(),
        'cleanerName' => $shortlist->getCleanerName(),
        'price' => $shortlist->getPrice(),
    ];

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>