<?php

session_start();

require_once 'controllers/ViewAllServiceCategoryController.php';
require_once 'controllers/SearchServiceCategoryController.php';

// Check if User is Logged In
if (
    !isset($_SESSION['id']) &&
    !isset($_SESSION['username']) &&
    !isset($_SESSION['userProfile'])
) {
    header("Location: login.php");
    exit();
}

// Check if UserProfile is Valid
if ($_SESSION['userProfile'] != "Platform Management") {
    header("Location: login.php");
    exit();
}

// Search Service Category if GET['q'] Parameter Exists
if (isset($_GET['q'])) {
  if ($_GET['q'] != '') {
    // Remove Quotes, URL Decode, Trim Consecutive/Trailing Whitespaces
    $searchTerm = str_replace(['"', "'"], '', $_GET['q']);

    // URL Decode
    $searchTerm = urldecode($searchTerm);

    // Trim Consecutive/Trailing Whitespaces
    $searchTerm = preg_replace('/\s{2,}/', ' ', $searchTerm);
    $searchTerm = ltrim($searchTerm);
    $searchTerm = rtrim($searchTerm);

    // Search Service Category
    $controller = new SearchServiceCategoryController();
    $serviceCategory = $controller->searchServiceCategory($searchTerm);
  }
} else {
    // Read All ServiceCategory
    $controller = new ViewAllServiceCategoryController();
    $serviceCategory = $controller->readAllServiceCategory();
}
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
      <a href="viewServiceCategory.php" class="active">Services</a>
      <a href="#">Report</a>
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
  <h1>Service Category</h1>
  <div class="section-container">
    <div class="search">
      <div class="search-group">
        <div class="input-wrapper">
          <ion-icon name="search-outline"></ion-icon>
          <input type="text" id="search_term" type="text" placeholder="Search..."
                 value=<?php if (isset($_GET['q'])) { echo $_GET['q']; } ?>>
        </div>
        <button onclick='searchBtnClicked()' class="search-button">Search</button>
      </div>
      <button onclick='window.location.href="createServiceCategory.php"' class="create-button">
        <ion-icon name="add-outline"></ion-icon>
        Create
      </button>
    </div>

    <!-- Display Table -->
    <table class="display-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Service Category</th>
          <th>Description</th>
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
              <button class="view-button"
                      onclick="updateBtnClicked('<?php echo $sc->getId(); ?>')">
                <ion-icon name="eye-outline"></ion-icon>View
              </button>
              <button class="delete-button"
                      onclick="deleteBtnClicked('<?php echo ($sc->getId()); ?>')">
                <ion-icon name="trash-outline"></ion-icon>Delete
              </button>
            </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- JavaScript -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script>
    // Search Button Clicked
    function searchBtnClicked() {

      // Get Search Input
      var searchTermInput = document.getElementById("search_term");
      var searchTerm = searchTermInput.value;

      // Alphanumeric & Single Whitespace Regex
      searchTerm = searchTerm.replace(/[^a-zA-Z0-9\s]+/g, '');
      searchTerm = searchTerm.replace(/\s+/g, ' ');

      // Update URL to include GET['q'] Parameter
      window.location.href = 'viewServiceCategory.php?q="' + searchTerm + '"';
    }

    // Update Button Clicked
    function updateBtnClicked(id) {
      // Update URL Location
      window.location.href = "updateServiceCategory.php?id=" + id;
    }

    // Delete Button Clicked
    function deleteBtnClicked(id) {
      // Prompt Confirmation & Call Delete Controller
      if (confirm("Confirm Delete Service Category?") == true) {
        window.location.href='./controllers/DeleteServiceCategoryController.php?id=' + id;
      }
    }
  </script>
</body>
</html>