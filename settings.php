<!--<?php @include_once('engine.php'); ?>-->

<div class="container">
    <span>Rodzaj sterownika</span>
    <select name="driver" form="db" id="select">
        <option value="mysql">MySQL</option>
        <option value="PostgreSQL">PostgreSQL</option>
        <option value="Firebird">Firebird</option>
        <option value="MSSQL">MSSQL</option>
    </select>
    <form action="" method="post" id="db">
        <div class="col-lg-3">
            <label for="servername">adres serwera</label>
            <input type="text" class="form-control" name="servername" id="servername" placeholder="adres serwera"
                   required>
        </div>
        <div class=" col-lg-3">
            <label for="login">login</label>
            <input type="text" class="form-control" name="login" id="login" placeholder="Login" required>
        </div>
        <div class="col-lg-3">
            <label for="password">hasło</label>
            <input type="text" class="form-control" name="password" placeholder="Hasło">
        </div>
        <div class="col-lg-3">
            <label for="dbname">nazwa bazy danych</label>
            <input type="text" class="form-control" name="dbname" placeholder="DB name" required>
        </div>
        <div class="col-lg-3">
            <label for="tbname">nazwa tabeli z kontrahentami</label>
            <input type="text" class="form-control" name="tbname" required>
        </div>
        <div class="col-lg-3">
            <label for="col_nip">nazwa kolumny z polem nip</label>
            <input type="text" class="form-control" name="col_nip" required>
        </div>
        <div class="form-group col-lg-3">
            <label for="col_contractor">nazwa kolumny z polem kontrahent</label>
            <input type="text" class="form-control" name="col_contractor" required>
        </div>

        <p><input type="checkbox"> Sprawdzenie statusu podatnika spoza źródła danych </p>
        <!-- TODO: Check if there should be possibility to check company status right away -->


        <button type="submit" class="btn btn-primary" name="save" value="save" id="save">Zapisz</button>
    </form>


</div>

