<?php

    $hostname = "localhost";
    $username = "u906128965_admin";
    $password = "R*$1E=fr8~";
    $database = "u906128965_db_graffiti";

    $connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

    $getStatement = "SELECT * FROM alerts";
    try {
        $queryObj = $connection->prepare($getStatement);
        $queryObj->execute([]);
        $allAlerts = $queryObj->fetchAll();
    } catch (PDOException $pe) {
        return "database error";
    }

    ?> <table> <?php

    foreach ($allAlerts as $alertRow) {
        ?>
            <tr> 
                <td>
                    <?php echo $alertRow["id"] ?>
                </td>
                <td>
                    <?php echo $alertRow["timestamp"] ?>
                </td>
                <td>
                    <?php echo $alertRow["kind"] ?>
                </td>
                <td>
                    <?php echo $alertRow["value"] ?>
                </td>
                <td>
                    <?php echo unserialize($alertRow["data"]) ?>
                </td>
            </tr>
        <?php
    }

    ?> </table> <?php

?>