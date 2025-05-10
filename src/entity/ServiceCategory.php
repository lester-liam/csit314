<?php

require_once('Database.php');

class ServiceCategory
{
    protected int $id;              // ID
    protected string $category;     // Service Category Name
    protected string $description;  // Description of Service Category

    /**
     * Inserts a New ServiceCategory Record
     *
     * @param string $category      Service Category
     * @param ?string $description   Description of Service Category (nullable)
     *
     * @return bool True if the insertion was successful, false otherwise.
     */
    public function createServiceCategory(string $category, ?string $description): bool
    {
        // New DB Connection
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            // SQL Statement
            $sql = "INSERT INTO `ServiceCategory` (`category`) VALUES (:category)";

            // Include 'description' field in SQL query if a description is provided.
            if (!is_null($description)) {
                $sql = "INSERT INTO `ServiceCategory` (`category`, `description`)
                        VALUES (:category, :description)";
            }

            // Bind Paramaters
            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':category', $category);

            // If a description is provided, bind parameter
            if (!is_null($description)) {
                $stmt->bindParam(':description', $description);
            }

            // Execute Statement
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // If execution was sucessful, return true
            // Otherwise, return false
            if ($execResult) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database insert failed: " . $e->getMessage());
            unset($db_handle);
            return false;
        }
    }

    /**
     * Reads a specific ServiceCategory record by its ID.
     *
     * @param int $id   ID of the ServiceCategory to retrieve.
     *
     * @return ?ServiceCategory The ServiceCategory object if found, null otherwise.
     */
    public function readServiceCategory(int $id): ?ServiceCategory
    {
        // New DB Connection
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            // Bind Parameters and Execute Statement
            $stmt = $db_conn->prepare("SELECT * FROM `ServiceCategory` WHERE `id` = :id");
            $stmt->bindParam(':id', $id);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // If execution was sucessful, return ServiceCategory Object
            // Otherwise, return null
            if ($execResult) {
                $serviceCategory = $stmt->fetchObject('ServiceCategory');
                return $serviceCategory;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    /**
     * Reads all ServiceCategory records from the database.
     *
     * @return ?array An array of ServiceCategory objects if successful, null otherwise.
     */
    public function readAllServiceCategory(): ?array
    {
        // New DB Connnection
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            // Execute Statement
            $stmt = $db_conn->prepare("SELECT * FROM `ServiceCategory`");
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // If execution was sucessful, return fetchAll ServiceCategory Objects
            // Otherwise, return null
            if ($execResult) {
                $serviceCategory = $stmt->fetchAll(PDO::FETCH_CLASS, 'ServiceCategory');
                return $serviceCategory;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    /**
     * Updates an existing ServiceCategory record.
     *
     * @param int $id The ID of the ServiceCategory to update.
     * @param string $category The new Service Category Name.
     * @param ?string $description The new Description of the Service Category (nullable).
     *
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateServiceCategory(
        int $id,
        string $category,
        ?string $description
    ): bool {
        // New DB Connnection
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            // SQL Statement
            $sql = "UPDATE `ServiceCategory`
                    SET `category` = :category, `description` = :description
                    WHERE `id` = $id";

            // Set default 'description' value in SQL query if no description provided.
            if (is_null($description)) {
                $description = 'No Description';
            }

            // Bind Parameters
            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);

            // Execute Statement
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // If execution was sucessful, return true
            // Otherwise, return false
            if ($execResult) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database update failed: " . $e->getMessage());
            unset($db_handle);
            return false;
        }
    }

    /**
     * Deletes a specific ServiceCategory record by its ID.
     *
     * @param int $id The ID of the ServiceCategory to delete.
     *
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteServiceCategory(int $id): bool
    {
        // New DB Connnection
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            // Execute SQL Statement
            $stmt = $db_conn->prepare("DELETE FROM `ServiceCategory` WHERE `id` = $id");
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // If execution was sucessful, return true
            // Otherwise, return false
            if ($execResult) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database delete failed: " . $e->getMessage());
            unset($db_handle);
            return false;
        }
    }

    /**
     * Searches for ServiceCategory records based on a search term
     * in the category or description.
     *
     * @param string    $searchTerm The term to search for.
     *
     * @return ?array   An array of ServiceCategory objects
     *                  matching the search term (nullable)
     */
    public function searchServiceCategory(string $searchTerm): ?array
    {
        // New DB Connnection
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL Statement
        try {
            // Add Wildcard Operators to Search Term
            $searchTerm = "%" . $searchTerm . "%";

            // Bind Parameters
            $stmt = $db_conn->prepare("SELECT * FROM `ServiceCategory` WHERE `category` LIKE :term OR `description` LIKE :term");
            $stmt->bindParam(':term', $searchTerm);

            // Execute Statement
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // If execution was sucessful, return fetchAll ServiceCategory Objects
            // Otherwise, return null
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'ServiceCategory');
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            return null;
        }
    }

    // Accessor Methods
    public function getId(): int
    {
        return $this->id;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}