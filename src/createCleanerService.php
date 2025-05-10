<?php

// Start the session (if not already started)
session_start();

// Include the controller
require_once 'controllers/ViewServiceCategoryController.php';

// Ensure User is Logged In
if (!isset($_SESSION['id']) && !isset($_SESSION['username']) && !isset($_SESSION['userProfile'])) {
    header("Location: login.php");
    exit();
}

// Ensure User is Platform Management
if ($_SESSION['userProfile'] != "Cleaner") {
     header("Location: login.php");
     exit();
}

// Get category by ID
if (!isset($_GET['id'])) {

  header("Location: viewCleanerService.php");
  exit();

} else {
  $controller = new ViewServiceCategoryController();
  $serviceCategory = $controller->readServiceCategory($_GET['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Service</title>
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

  <div class="form-container">
    <h2>Create Service</h2>
    <br>
    <form action="controllers/CreateCleanerServiceController.php" method="post">
      <?php if (isset($_GET['status']) && $_GET['status'] == 0) { ?>
        <div class="alert-danger" role="alert">
          <strong>Create Failed: Please Contact Admin</strong>
        </div>
      <?php } else if (isset($_GET['status']) && $_GET['status'] == 1) { ?>
        <div class="alert-success" role="alert">
          <strong>New Service Created Successfully</strong>
        </div>
      <?php } ?>

      <input type="hidden" name="cleanerID" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
      <input type="hidden" name="serviceCategoryID" value="<?php echo htmlspecialchars($serviceCategory->getId()); ?>">

      <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($serviceCategory->getCategory()); ?>" readonly>
        <span id='categoryValidation' class='text-danger'></span>
      </div>

      <div class="form-group">
        <label for="serviceName">Service Name</label>
        <input type="text" id="serviceName" name='serviceName' required>
        <span id='sNameValidation' class='text-danger'></span>
      </div>

      <div class="form-group">
        <label for="price">Price</label>
        <input type="number" id="price" name='price' required>
        <span id='priceValidation' class='text-danger'></span>
      </div>

      <div class="submit-row">
        <button type="button" onclick='window.location.href="selectCleanerServiceCategory.php"' class="back-button">Back</button>
        <button type="submit" id="submit-button" class="submit-button">Create</button>
      </div>
    </form>
  </div>

  <script>
    const form = document.querySelector("form");

    document.getElementById("submit-button").addEventListener("click", function (event) {
      event.preventDefault();
      let isValid = true;

      const category = document.getElementById("category").value.trim();
      if (!category) {
        document.getElementById("categoryValidation").innerText = "Category cannot be empty.";
        isValid = false;
      } else {
        document.getElementById("categoryValidation").innerText = "";
      }

      // Service Name Validation: Not NULL and less than 64 characters
      const sNameInput = document.getElementById('serviceName');
      const trimmedSName = sNameInput.value.trim();
      if (!trimmedSName) {
          document.getElementById('sNameValidation').innerText = "Service Name cannot be empty.";
          isValid = false;
      } else if (trimmedSName.length > 48) {
          document.getElementById('sNameValidation').innerText = "Service Name cannot exeed more than 48 characters.";
          isValid = false;
      } else {
          document.getElementById('sNameValidation').innerText = "";
      }

      // Price Validation: Not NULL, non-negative, less than 10,000, and limited to 2 decimal points
      const priceInput = document.getElementById('price');
      var trimmedPrice = priceInput.value.trim();
      const priceValue = parseFloat(trimmedPrice);

      if (!trimmedPrice) {
        document.getElementById('priceValidation').innerText = "Price cannot be empty.";
        isValid = false;
      } else if (isNaN(priceValue) || priceValue < 0) {
        document.getElementById('priceValidation').innerText = "Price must be a non-negative number.";
        isValid = false;
      } else if (priceValue >= 10000) {
        document.getElementById('priceValidation').innerText = "Price cannot be 10,000 or more.";
        isValid = false;
      } else {
        if (!/^\d+(\.\d{2})?$/.test(trimmedPrice)) {
          document.getElementById('priceValidation').innerText = "Price must have 2 decimal points.";
          isValid = false;
        } else {
          document.getElementById('priceValidation').innerText = "";
        }
      }

      if (isValid) {
        if (confirm("Confirm Create Service?")) {
          form.submit();
        }
      }
    });
  </script>
</body>
</html>
