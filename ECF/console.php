<?php
if ($message!="") {
    echo "<div id='console' class='console'></div>";
    echo '<script type="text/javascript">';
    echo "document.getElementById('console').innerHTML = '".$message."';";
    echo "setTimeout(function() {";
    echo "document.getElementById('console').innerHTML = ''";
    echo "},3000);</script>";
}
?>