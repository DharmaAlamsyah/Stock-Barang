<?php
session_start();
//Koneksi ke Database
$conn = mysqli_connect("localhost","root","","stockbarang");

// Menambah Barang Baru
if(isset($_POST['addnewbarnag'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES('$namabarang','$deskripsi','$stock')");
    if ($addtotable) {
        header("Location:index.php");
    } else {
        echo 'gagal';
        header("Location:index.php");
    }    
}
?>
