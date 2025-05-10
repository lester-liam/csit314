<?php

require_once "/var/www/html/entity/ServiceCategory.php";

class UpdateServiceCategoryController
{
    private $serviceCategory;

    public function __construct()
    {
        $this->serviceCategory = new ServiceCategory();
    }

    public function updateServiceCategory(int $id, string $category, ?string $description): bool
    {
        return $this->serviceCategory->updateServiceCategory($id, $category, $description);
    }
}

/**
 * Script to handle the submission of the Update Service Category form.
 * Expects a POST request with `id`, 'category' and 'description' parameters.
 */
if (isset($_POST['id']) && isset($_POST['category']) && isset($_POST['description'])) {

    // Instantiate New Controller
    $controller = new UpdateServiceCategoryController();

    // Convert string ID to integer
    $id = (int) $_POST['id'];

    // If description is empty, pass 'null' to Controller for default value in Entity
    if ($_POST['description'] === "") {
        $status = $controller->updateServiceCategory($id, $_POST['category'], null);
    } else {
        $status = $controller->updateServiceCategory(
                                    $id,
                                    $_POST['category'],
                                    $_POST['description']
                                );
    }

    // Redirect back to the form with status message
    if ($status) {
        header("Location: ../updateServiceCategory.php?id=$id&status=1");
        exit();
    } else {
        header("Location: ../updateServiceCategory.php?id=$id&status=0");
        exit();
    }
} else {
    $id = (int) $_POST['id'];
    header("Location: ../updateServiceCategory.php?id=$id&status=0");
    exit();
}