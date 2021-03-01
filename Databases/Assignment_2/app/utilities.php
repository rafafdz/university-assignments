<?php
/**
* Recibe un objeto de query, lo procesa y genera una tabla html
*
*/
function generate_table_from_query(){
    $args = func_get_args();
    $num_args = func_num_args();
    $query = $args[0];

    $query -> execute();
    $result = $query -> fetchAll();

    echo "<table>";

    $head_names = array_slice($args, 1, $num_args - 1);
    $head = "<tr>";
    foreach ($head_names as $name) {
        $head .= "<th>$name</th>";
    }
    echo $head . "</tr>";

    $tup_length = $num_args - 1;
    foreach ($result as $table_tuple){
        $row = "<tr>";
        for ($i = 0; $i < $tup_length; $i++) {
            $data = $table_tuple[$i];
            $row .= "<td>$data</td>";
        }
        echo $row . "</tr>";
    }

    echo "</table>";
}
?>
