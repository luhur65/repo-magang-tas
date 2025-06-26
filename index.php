<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

  <title>Belajar 4 - Penggunaan JQGrid pada Master Detail</title>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous"> -->

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/js/jquery-ui.min.js"></script>
  <script src="./assets/js/trirand/i18n/grid.locale-id.js"></script>
  <script src="./assets/js/trirand/jquery.jqGrid.min.js"></script>

  <!-- inputmask -->
  <script src="./assets/inputmask/jquery.inputmask.js"></script>
  <script src="./assets/inputmask/bindings/inputmask.binding.js"></script>

  <!-- select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <link rel="stylesheet" href="./assets/css/jquery-ui.css">
  <link rel="stylesheet" href="./assets/css/trirand/ui.jqgrid.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>

  <style type="text/tailwindcss">
    * {
      font-family: "DM Sans", sans-serif;
    }

    input[type="text"] {
      text-transform: uppercase;
    }

    .highlight {
      background-color: yellow;
      transition: background-color 0.5s ease;
    }
  </style>

</head>

<body>

  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <!-- <a class="navbar-brand" href="?page=home">JQGrid</a> -->
      <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> -->
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <!-- <a class="nav-link active" aria-current="page" href="?page=home">Home</a> -->
          <!-- <a class="nav-link" href="?page=master">Master</a> -->
          <!-- <a class="nav-link" href="?page=pelanggan">Pelanggan</a> -->
          <!-- <a class="nav-link" href="#">Pricing</a>
          <a class="nav-link disabled" aria-disabled="true">Disabled</a> -->
        </div>
      </div>
    </div>
  </nav>

  <main class="p-8">
    <?php require_once('route.php'); ?>
  </main>

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script> -->

  <script src="./assets/jqgrid-script.js"></script>
  <!-- <script src="./assets/penjualan.js"></script> -->

</body>

</html>