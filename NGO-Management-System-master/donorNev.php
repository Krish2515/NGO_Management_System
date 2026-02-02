<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
    <a class="navbar-brand" href="javascript:void(0)">NGO</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Left side -->
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="javascript:void(0)">Donor <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)"><?= htmlentities($donor_name) ?></a>
            </li>
        </ul>

        <!-- Right side -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="update/donorUpdate.php">Edit Profile</a>
            </li>

            <?php if (isset($indexFlag) && $indexFlag == 1) {?>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>