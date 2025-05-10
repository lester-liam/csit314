<?php

require_once('/var/www/html/entity/CleanerService.php');

class CreateCleanerServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Update User Account, Returns Boolean Value (Success/Fail)
    public function createCleanerService($serviceCategoryID, $cleanerID, $serviceName, $price) {
        return $this->cleanerService->createCleanerService($serviceCategoryID, $cleanerID, $serviceName, $price);
    }

}

// `createCleanerService.php` Script
// Executes when Create Cleaner Service is Submitted (POST Request)
if (
    isset($_POST['serviceCategoryID']) &&
    isset($_POST['cleanerID']) &&
    isset($_POST['serviceName']) &&
    isset($_POST['price'])
    ) {

        // Convert string ID to integer
        $serviceCategoryID = (int) $_POST['serviceCategoryID'];
        $cleanerID = (int) $_POST['cleanerID'];
        $price = (float) $_POST['price'];

        // Instantiate New Controller & Service
        $controller = new CreateCleanerServiceController();
        $status = $controller->createCleanerService($serviceCategoryID, $cleanerID, $_POST['serviceName'], $price);

        // Display Success or Fail
        if ($status) {
            header("Location: ../createCleanerService.php?id=$serviceCategoryID&status=1");
            exit();

        } else {
            header("Location: ../createCleanerService.php?id=$serviceCategoryID&status=0");
            exit();
        }

} else {

    $id = (int) $_POST['id'];
    header("Location: ../createCleanerService.php?id=$serviceCategoryID&status=0");
    exit();

}

?>