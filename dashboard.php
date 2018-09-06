
Tabela

<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Lp</th>
        <th scope="col">Nazwa firmy</th>
        <th scope="col">NIP</th>
        <th scope="col">Status</th>
        <th scope="col">Ostatnia Aktualizacja</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $lp = 0;

    $sql = "SELECT * FROM kontrahent_status";
    $db->query($sql);

    $records = $db->resultset();

    foreach($records as $row){
        $lp++;
        echo '<th scope="row">'.$lp.'</th>';
        echo '<td>'.$row['nazwa'].'</td>';
        echo '<td>'.$row['nip'].'</td>';
        echo '<td>'.$row['komunikat'].'</td>';
        echo '<td>'.$row['data_utworzenia'].'</td>';
        echo '</tr>';
    }

    ?>

    </tbody>
</table>