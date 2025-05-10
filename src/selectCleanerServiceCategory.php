<?php

// Start the session (if not already started)
session_start();

// Include the controller
require_once 'controllers/ViewAllServiceCategoryController.php';

// Ensure User is Logged In
if (!isset($_SESSION['id']) && !isset($_SESSION['username']) && !isset($_SESSION['userProfile'])) {
    header("Location: login.php");
    exit();
}

// Ensure User is Cleaner
if ($_SESSION['userProfile'] != "Cleaner") {
     header("Location: login.php");
     exit();
}

// Retrieve all Service Category with Controller
$controller = new ViewAllServiceCategoryController();

// Get all Service Category
$serviceCategory = $controller->readAllServiceCategory();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Service Category</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="navbar-left">
      <img src='img/cleaning-logo.png' alt="Cleaning Logo" width='48px' height='48px'/>
      <a href="viewCleanerService.php" class="active">My Services</a>
      <a href="viewMatches.php">My Matches</a>
    </div>
    <div class="navbar-right">
      <span class="navbar-right-text">Logged in as,<br/>(<?php echo htmlspecialchars($_SESSION["userProfile"]); ?>) <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
      <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </div>

  <!-- Headline -->
  <h1>Choose Service Type</h1>

  <div class="section-container">
    <div class="search">
      <div class="search-group">
        <div class="input-wrapper">
          <input type="hidden" type="text" placeholder="Search...">
        </div>
      </div>
      <button onclick='window.location.href="viewCleanerService.php"' class="create-button">
        <ion-icon name="return-down-back-outline"></ion-icon>
        Back
      </button>
    </div>

    <!-- User Table -->
    <table class="display-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Service Category</th>
          <th>Service Name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($serviceCategory as $sc): ?>
          <tr>
              <td><?php echo htmlspecialchars($sc->getId()); ?></td>
              <td><?php echo htmlspecialchars($sc->getCategory()); ?></td>
              <td><?php echo htmlspecialchars($sc->getDescription()); ?></td>
              <td>
                <button class="view-button" onclick='window.location.href="createCleanerService.php?id=<?php echo htmlspecialchars($sc->getId()); ?>"'><ion-icon name="add-outline"></ion-icon>Create</button>
              </td>

          </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <script>
    function deleteButtonClicked(id) {
      if (confirm("Confirm Delete Service Category?") == true) {
        window.location.href='./controllers/DeleteServiceCategoryController.php?id=' + id;
      }
    }
  </script>

</body>
</html>