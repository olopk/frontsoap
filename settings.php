<!--
<?php

session_start();

?>
-->
<div class="box">
    <span>Rodzaj sterownika</span>
    <select name="driver" form="db" id="select">
            <option value="MySQL">MySQL</option>
            <option value="PostgreSQL">PostgreSQL</option>
            <option value="Firebird">Firebird</option>
            <option value="MSSQL">MSSQL</option>
    </select>
    <form action="" method="post" id="db">
        <form-group>
            <label for="login">login</label>
            <input type="text" class="form-control" name="login" id="login" placeholder="Login" required>
        </form-group>
        <form-group>
            <label for="password">hasło</label>
            <input type="text" class="form-control" name="password" placeholder="Hasło" required>
        </form-group>
        <form-group>
            <label for="dbname">nazwa bazy danych</label>
            <input type="text" class="form-control" name="dbname" placeholder="DB name" required>
        </form-group>
        <form-group>
            <label for="tbname">nazwa tabeli z kontrahentami</label>
            <input type="text" class="form-control" name="tbname" required>
        </form-group>
        <form-group>
            <label for="col_nip">nazwa kolumny z polem nip</label>
            <input type="text" class="form-control" name="col_nip" required>
        </form-group>
        <form-group>
            <label for="col_contractor">nazwa kolumny z polem kontrahent</label>
            <input type="text" class="form-control" name="col_contractor" required>
        </form-group>
        <button type="submit" class="btn btn-primary" name="save" id="save">Zapisz</button>
    </form>
</div>