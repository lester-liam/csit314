<?php

// Start the session (if not already started)
session_start();

// Include the controller
require_once 'controllers/ViewMatchesController.php';
require_once 'controllers/MatchCategoryController.php';

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

$cleanerID = (int) $_SESSION['id']; // Cleaner ID

// Retrieve all Cleaner Services with Controller
$controller = new ViewMatchesController();
$controller2 = new MatchCategoryController();


// Get all Cleaner Services & Unique Categories
$matches = $controller->ViewMatches($cleanerID);
$categories = $controller2 -> getCategories($cleanerID);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Services</title>

  <link rel="stylesheet" href="css/modal.css">
  <link rel="stylesheet" href="css/style.css">


</head>

<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="navbar-left">
      <img src='img/cleaning-logo.png' alt="Cleaning Logo" width='48px' height='48px'/>
      <a href="viewCleanerService.php">My Services</a>
      <a href="viewMatches.php" class="active">My Matches</a>
    </div>
    <div class="navbar-right">
      <span class="navbar-right-text">Logged in as,<br/>
        (<?php echo htmlspecialchars($_SESSION["userProfile"]); ?>)
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
      </span>
      <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </div>

  <!-- Headline -->
  <h1>My Services</h1>

  <div class="section-container">
    <div class="search">
      <div class="search-group">
        <div class="input-wrapper">
          <input type="text" id="search_term" type="text" placeholder="Search..." hidden>
        </div>
      </div>
      <button onclick='displaySearchModal()' class="create-button">
      <ion-icon name="search-outline"></ion-icon>
        Search
      </button>
    </div>

    <!-- User Table -->
    <table class="display-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer Name</th>
          <th>Service Category</th>
          <th>Completion Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($matches as $m): ?>
          <tr>
              <td><?php echo htmlspecialchars($m->getId()); ?></td>
              <td><?php echo htmlspecialchars($m->getName()); ?></td>
              <td><?php echo htmlspecialchars($m->getCategory()); ?></td>
              <td><?php echo htmlspecialchars($m->getServiceDate()); ?></td>
              <td>
                <button
                  class="view-button"
                  onclick="viewMatch(
                            '<?php echo $m->getCategory(); ?>',
                            '<?php echo $m->getName(); ?>',
                            '<?php echo $m->getServiceDate(); ?>')">
                <ion-icon name="eye-outline"></ion-icon>
                View
                </button>
              </td>
          </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

  </div>

  <!-- The Modal -->
  <div id="ViewMatchModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <h2>History Info</h2>
        <button class="close">&times;</button>
      </div>
      <div class="modal-body">
      <div class="form-group">
          <label for="viewCustName">Customer Name</label>
          <input type="text" id="viewCustName" disabled>
        </div>
        <div class="form-group">
          <label for="viewCategory">Service Category</label>
          <input type="text" id="viewCategory" disabled>
        </div>

        <div class="form-group">
          <label for="viewDate">Service Completion Date</label>
          <input type="text" id="viewDate" disabled>
        </div>
      </div>
    </div>

  </div>

  <!-- The Modal -->
  <div id="SearchMatchesModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <h2>Search</h2>
        <button class="close">&times;</button>
      </div>
      <div class="modal-body">
      <form action="searchMatchesResult.php" method="GET">
        <div class="form-group">
          <label for="searchTerm">Search Text:</label>
          <input type="text" id="searchTerm" name="searchTerm" placeholder="Optional Search By Text">
        </div>
        <div class="form-group">
          <label for="category">Search Category:</label>
          <?php
            if (empty($categories)) {
              echo '<input type="text" id="category" name="category" value="" readonly>';
            } else {
              echo '<select class="form-select" id="category" name="category">';
              echo '<option value="" selected></option>';
              foreach ($categories as $c) {
                echo '<option value=' .
                      htmlspecialchars($c['category']) .
                      '>' .
                      $c['category'] .
                      '</option>';
              }
              echo '</select>';
            }
          ?>
        </div>
        <div class="form-group">
          <label for="dateOption">Date Range:</label>
          <select class="form-select" id="dateOption" name="dateOption">
            <option value="0" selected>Past 7 Days</option>
            <option value="1">Past 30 Days</option>
            <option value="2">All Time</option>
          </select>
        </div>
        <div class="submit-row">
          <button type="submit" id="submit-button" class="submit-button">Search</button>
        </div>
      </form>
      </div>
    </div>

  </div>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <script>

    const modal = document.getElementById("ViewMatchModal");
    const modal2 = document.getElementById("SearchMatchesModal");
    const closeBtn = document.getElementsByClassName("close")[0];
    const closeBtn1 = document.getElementsByClassName("close")[1];

    // Close Modal onClick
    closeBtn.onclick = function() {
      modal.style.display = "none";
    }

    closeBtn1.onclick = function() {
      modal2.style.display = "none";
    }

    function viewMatch(category, custName, serviceDate) {

      const viewModal = document.getElementById("ViewMatchModal");
      document.getElementById('viewCustName').value = custName;
      document.getElementById('viewCategory').value = category;
      document.getElementById('viewDate').value = serviceDate;

      viewModal.style.display = "block";

    }

    function displaySearchModal() {

      const searchModal = document.getElementById("SearchMatchesModal");
      searchModal.style.display = "block";

    }

  </script>

</body>
</html>