<?php

require_once "/var/www/html/entity/ServiceCategory.php";

class DeleteServiceCategoryController
{
    private $serviceCategory;

    public function __construct()
    {
        $this->serviceCategory = new ServiceCategory();
    }

    public function deleteServiceCategory(int $id): bool
    {
        return $this->serviceCategory->deleteServiceCategory($id);
    }
}

/**
 * Script to handle the Deletion of the Service Category
 * Expects a GET request with `id` parameter
 */
if (isset($_GET['id'])) {
    // Convert string ID to integer
    $id = (int) $_GET['id'];
    // Instantiate New Controller
    $controller = new DeleteServiceCategoryController();
    $status = $controller->deleteServiceCategory($id);
    // Alert Status Message, then Redirect to Page
    if ($status) {
        echo '<script>
                alert ("Delete Successful");
                window.location.href="../viewServiceCategory.php";
             </script>';
    } else {
        echo '<script>
                alert ("Delete Failed");
                window.location.href="../viewServiceCategory.php";
             </script>';
    }
} else {
    echo '<script>
            alert ("Delete Failed");
            window.location.href="../viewServiceCategory.php";
         </script>';
}