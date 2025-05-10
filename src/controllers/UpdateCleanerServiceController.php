<?php

require_once('/var/www/html/entity/CleanerService.php');

class UpdateCleanerServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Update User Account, Returns Boolean Value (Success/Fail)
    public function updateCleanerService($id, $cleanerID, $serviceName, $price) {
        return $this->cleanerService->updateCleanerService($id, $cleanerID, $serviceName, $price);
    }

}

// `updateCleanerService.php` Script
// Executes when Update Cleaner Service is Submitted (POST Request)
if (
    isset($_POST['id']) &&
    isset($_POST['cleanerID']) &&
    isset($_POST['serviceName']) &&
    isset($_POST['price'])
    ) {

        // Convert string ID to integer
        $id = (int) $_POST['id'];
        $cleanerID = (int) $_POST['cleanerID'];
        $price = (float) $_POST['price'];

        // Instantiate New Controller & Service
        $controller = new UpdateCleanerServiceController();
        $status = $controller->updateCleanerService($id, $cleanerID, $_POST['serviceName'], $price);

        // Display Success or Fail
        if ($status) {
            header("Location: ../updateCleanerService.php?id=$id&status=1");
            exit();

        } else {
            header("Location: ../updateCleanerService.php?id=$id&status=0");
            exit();
        }

} else {

    $id = (int) $_POST['id'];
    header("Location: ../updateCleanerService.php?id=$id&status=0");
    exit();

}

?>