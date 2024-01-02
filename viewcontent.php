<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Infos@Infosec-Startpage.de</title>
  <style>
    body {
      background-color: #000;
      color: #fff;
      margin: 0;
      padding: 20px;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #fff;
      padding: 10px;
      text-align: left;
    }
  </style>
</head>
<body>

<?php
// Funktion zum Lesen der CSV-Datei und Konvertieren in ein Array
function readCSV($csvFile) {
  $file_handle = fopen($csvFile, 'r');
  $header = [];
  $data = [];

  // Erste Zeile als Header lesen
  if (!feof($file_handle)) {
    $header = fgetcsv($file_handle);
  }

  // Daten lesen
  while (!feof($file_handle)) {
    $data[] = fgetcsv($file_handle);
  }

  fclose($file_handle);

  return ['header' => $header, 'data' => $data];
}

// CSV-Datei einlesen
$csvFile = 'input.csv'; // Hier den Pfad zu deiner CSV-Datei angeben
$csvData = readCSV($csvFile);

?>
<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search table">
<?php
// Ausgabe der Tabelle
if (!empty($csvData['data'])) {
  echo '<table id="myTable">';
  echo '<thead><tr>';
  $sortOrder = 0;
  foreach ($csvData['header'] as $headerItem) {
    echo '<th onclick="sortTable('.$sortOrder.')">' . htmlspecialchars($headerItem) . '</th>';
    $sortOrder++;
  }
  echo '</tr></thead>';
  echo '<tbody>';
  foreach ($csvData['data'] as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
      echo '<td>' . htmlspecialchars($cell) . '</td>';
    }
    echo '</tr>';
  }
  echo '</tbody>';
  echo '</table>';
} else {
  echo '<p>Die CSV-Datei enthält keine Daten.</p>';
}
?>


<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    for (j=0; j < 4; j++) {
    td = tr[i].getElementsByTagName("td")[j];

    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
    }
  }
}


function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("td")[n];
      y = rows[i + 1].getElementsByTagName("td")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (!x || !y) {
        continue; // Skip this iteration if x or y is undefined
      }


      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}




</script>

</body>
</html>
