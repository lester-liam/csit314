<?php

require_once "/var/www/html/entity/ServiceCategory.php";

class SearchServiceCategoryController
{
    private $serviceCategory;

    public function __construct()
    {
        $this->serviceCategory = new ServiceCategory();
    }

    public function searchServiceCategory(string $searchTerm): ?array
    {
        return $this->serviceCategory->searchServiceCategory($searchTerm);
    }
}