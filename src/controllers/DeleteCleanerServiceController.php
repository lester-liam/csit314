<?php

require_once('/var/www/html/entity/CleanerService.php');

class DeleteCleanerServiceController {

    private $cleanerService;

    public function __construct() {
        $this->cleanerService = new CleanerService();
    }

    // Delete Cleaner Service
    public function deleteCleanerService($id, $cleanerID) {
        return $this->cleanerService->deleteCleanerService($id, $cleanerID);
    }
}

// `updateCleanerService.php` Script
// Executes when Delete Button is Click (GET Request)
if (isset($_GET['id']) && isset($_GET['cleanerID'])) {

    // Convert ID to Integer Value
    $id = (int) $_GET['id'];
    $cleanerID = (int) $_GET['cleanerID'];

    // Instantiate New Controller & Delete
    $controller = new DeleteCleanerServiceController();
    $status = $controller->deleteCleanerService($id, $cleanerID);

    // Alert Success or Fail, then Redirect
    if ($status) {

?>

        <script> alert ("Delete Successful"); window.location.href="../viewCleanerService.php"; </script>

<?php

    } else { ?>

        <script> alert ("Delete Failed"); window.location.href="../viewCleanerService.php"; </script>

<?php

    }


} else { ?>

    <script> alert ("Delete Failed"); window.location.href="../viewCleanerService.php"; </script>

<?php } ?>