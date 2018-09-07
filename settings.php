<?php

if ($_POST['save'] == 'save') {
    $db->query("UPDATE settings SET sync_every=:sync_time");
    $db->execute(array(':sync_time' => $_POST['sync_time']));
}

$db->query("SELECT sync_every FROM settings");
$row = $db->single();

?>
<form action="" method="post" id="db">

    <div class="card text-left">
        <div class="card-header">
            Ustawienia ogólne
        </div>
        <div class="card-body">

            <div class="row">
            <label class="control-label col-md-4">Sprawdzaj status podatnika VAT co</label>
            <div class="col-md-4">
                <input class="form-control" type="number" name="sync_time" value="<?= $row['sync_every'] ?>"
                       data-toggle="tooltip"
                       data-placement="top" required
                       title="Okresl w minutach jak często system powienien sprawdzać status podatnika VAT">
            </div>
            </div>

            <div class="row">
            <label class="control-label col-md-4">włącz sprawdzanie statusu
                podatnika spoza źródła danych</label>
            <div class="col-md-4">

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" aria-label="Checkbox for following text input">
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- TODO: Check if there should be possibility to check company status right away -->

            <button type="submit" class="btn btn-primary" name="save" value="save" id="save">Zapisz</button>
        </div>
    </div>

</form>
