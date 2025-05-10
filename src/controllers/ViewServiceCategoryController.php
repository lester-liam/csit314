<?php

require_once "/var/www/html/entity/ServiceCategory.php";

class ViewServiceCategoryController
{
    private $serviceCategory;

    public function __construct()
    {
        $this->serviceCategory = new ServiceCategory();
    }

    public function readServiceCategory(int $id): ?ServiceCategory
    {
        return $this->serviceCategory->readServiceCategory($id);
    }
}