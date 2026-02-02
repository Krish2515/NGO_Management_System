<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5 ">
  <!-- Brand -->
  <a class="navbar-brand" href="#">NGO</a>

  <!-- ✅ Project link next to NGO -->
  <a class="nav-link text-white" href="project.php" style="margin-right: 20px;">Projects</a>

  <!-- Toggler -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar content -->
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">

      <li class="nav-item active">
        <a class="nav-link" href="#">Please Login To Continue <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" 
           aria-haspopup="true" aria-expanded="false">
          Login Here
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="login/adminLogin.php">Admin</a>
          <a class="dropdown-item" href="login/donorLogin.php">Donor</a>
          <a class="dropdown-item" href="login/volunteerLogin.php">Volunteer</a>
        </div>
      </li>

    </ul>
  </div>
</nav>