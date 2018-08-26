<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">mfsoap</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Strona główna <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=settings">Ustawienia</a>
            </li>
            <?php
            if($_SESSION['logged'] == true){
                echo '<li class="nav-item">';
                echo '    <a class="nav-link" href="index.php?page=logout">Wyloguj się</a>';
                echo '</li>';
            }
            else{
                echo '<li class="nav-item">';
                echo '    <a class="nav-link" href="index.php">Zaloguj się</a>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</nav>
