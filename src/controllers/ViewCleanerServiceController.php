<?php

define('__ROOT__', '/var/www/html');

require_once(__ROOT__ . '/entity/CleanerService.php');
require_once(__ROOT__ . '/controllers/ServiceViewController.php');
require_once(__ROOT__ . '/controllers/ServiceShortlistController.php');

class ViewCleanerServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Returns Cleaner Service
    public function viewCleanerService($int, $cleanerID) {
        return $this->cleanerService->viewCleanerService($int, $cleanerID);
    }
}


if (isset($_GET['id']) && isset($_GET['cleanerID']) && isset($_GET['includeMetrics'])) {

    $id = (int) $_GET['id'];
    $cleanerID = (int) $_GET['cleanerID'];

    $viewController = new ViewCleanerServiceController();
    $numViewsController = new ServiceViewController();
    $numShortlistsController = new ServiceShortlistController();

    $service = $viewController->viewCleanerService($id, $cleanerID);

    if (is_null($service)) {
        echo json_encode(['error' => 'Invalid request']);
        exit();
    }

    $numViews = $numViewsController->getCleanerServiceViews($id);
    $numShortlists = $numShortlistsController->getCleanerServiceShortlists($id);

    // Prepare data to send back to JavaScript
    $response = [
        'id' => $service->getId(),
        'category' => $service->getCategory(),
        'serviceName' => $service->getServiceName(),
        'price' => $service->getPrice(),
        'numViews' => $numViews,
        'numShortlists' => $numShortlists,
    ];

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>