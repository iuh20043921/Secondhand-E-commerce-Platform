
<?php
    include_once("controller/cLoaiSP.php");
    $p = new controllerThuongHieu();
    $kq = $p->getAllThuongHieu();
    
    echo "<ul>";
    while ($r =  $kq->fetch_assoc()) {
        echo "<li><a href='?ml=".$r['MaLoaiSP']."'>".$r['TenLoaiSP']."</a></li>";
    }
    echo "</ul>";


?>
