<!--<?php @include_once('engine.php');?>-->

<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">lp</th>
        <th scope="col">Firma</th>
        <th scope="col">Nip</th>
        <th scope="col">Status</th>
        <th scope="col">Ostatnia Aktualizacja</th>
    </tr>
    </thead>
    <tbody>
    <?php

    // Nie chcemy zeby sprawdzal nipy za kazdym razem
    //$records_checked = load();
    $lp = 0;

    $stmt = $conn->query( $query );
    while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
        $lp++;
        echo '<th scope="row">'.$lp.'</th>';
        echo '<td>'.iconv("Windows-1250","UTF-8", $row['adr_Nazwa']).'</td>';
//        echo '<td>'.$row['adr_Nazwa'].'</td>';
        echo '<td>'.str_replace('-', '', $row['adr_NIP']).'</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
    }

    /*
    foreach($records_checked as $row){
        $lp++;
        echo '<th scope="row">'.$lp.'</th>';
        echo '<td>'.$row['nazwa'].'</td>';
        echo '<td>'.$row['nip'].'</td>';
        echo '<td>'.$row['komunikat'].'</td>';
        echo '<td>'.$row['data_utworzenia'].'</td>';
        echo '</tr>';
    }
    */
    ?>

    </tbody>
</table>