 
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <span type="button" class="btn btn-primary" name="logout"><i class="fa fa-power-off"></i>Strona główna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span type="button" class="btn btn-primary" name="logout"><i class="fa fa-power-off"></i>Kontakt</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=settings">
                    <span type="button" class="btn btn-primary" name="logout"><i class="fa fa-wrench"></i>&nbsp;Ustawienia</span>
                </a>
            </li>
            <?php 
            if($_SESSION['logged'] == true){
                echo '<li class="nav-item">';
                echo '    <a class="nav-link" href="index.php?page=logout">';
                echo '        <span type="button" class="btn btn-primary" name="logout"><i class="fa fa-power-off"></i>Wyloguj się</span>';
                echo '    </a>';
                echo '</li>';
            }
            else{
                echo '<li class="nav-item">';
                echo '    <a class="nav-link" href="index.php">';
                echo '        <span type="button" class="btn btn-primary" name="login"><i class="fa fa-power-off"></i>Zaloguj się</span>';
                echo '    </a>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</nav>
          
