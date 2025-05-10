<?php

require_once('/var/www/html/entity/CleanerService.php');

class HoViewServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Returns Cleaner Service
    public function hoViewService($id) {
        return $this->cleanerService->hoViewService($id);
    }
}


if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $viewController = new HoViewServiceController();

    $service = $viewController->hoViewService($id);

    if (is_null($service)) {
        echo json_encode(['error' => 'Invalid request']);
        exit();
    }

    // Prepare data to send back to JavaScript
    $response = [
        'category' => $service->getCategory(),
        'serviceName' => $service->getServiceName(),
        'cleanerName' => $service->getCleanerName(),
        'price' => $service->getPrice(),
    ];

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>