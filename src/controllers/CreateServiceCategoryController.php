<?php

require_once "/var/www/html/entity/ServiceCategory.php";

class CreateServiceCategoryController
{
    private $serviceCategory;

    public function __construct()
    {
        $this->serviceCategory = new ServiceCategory();
    }

    public function createServiceCategory(string $category, ?string $description): bool
    {
        return $this->serviceCategory->createServiceCategory($category, $description);
    }
}

/**
 * Script to handle the submission of the Create Service Category form.
 * Expects a POST request with 'category' and 'description' parameters.
 */
if (isset($_POST['category']) && isset($_POST['description'])) {

    // Instantiate New Controller
    $controller = new CreateServiceCategoryController();

    // If description is empty, pass 'null' to Controller for default value in Entity
    if ($_POST['description'] === "") {
        $status = $controller->createServiceCategory($_POST['category'], null);
    } else {
        $status = $controller->createServiceCategory($_POST['category'], $_POST['description']);
    }

    // Redirect back to the form with status message
    if ($status) {
        header("Location: ../createServiceCategory.php?status=1");
        exit();
    } else {
        header("Location: ../createServiceCategory.php?status=0");
        exit();
    }
} else {
    header("Location: ../createServiceCategory.php?status=0");
    exit();
}