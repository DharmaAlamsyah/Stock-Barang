<?php
session_start();
//Koneksi ke Database
$conn = mysqli_connect("localhost","root","","stockbarang");

// Menambah Barang Baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    if ($addtotable) {
        header("Location:index.php");
    } else {
        echo "gagal";
        header("Location:index.php");
    }    
}

// Menambah Barang Masuk
if(isset($_POST['barangmasuk'])){
    $namabarang = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    //cek stock tersedia
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$namabarang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya["stock"];
    $stocktambahqty = $stocksekarang+$qty; 

    // menambahkan ke database
    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES ('$namabarang', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$stocktambahqty' WHERE idbarang='$namabarang'");
    if ($addtomasuk&&$updatestockmasuk) {
        header("Location: masuk.php");
    } else {
        echo "gagal";
        header("Location: masuk.php");
    }    
}

// Menambah Barang Keluar
if(isset($_POST['addbarangkeluar'])){
    $namabarang = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    //cek stock tersedia
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$namabarang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya["stock"];
    $stocktambahqty = $stocksekarang-$qty; 

    // menambahkan ke database
    $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) VALUES ('$namabarang', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$stocktambahqty' WHERE idbarang='$namabarang'");
    if ($addtomasuk&&$updatestockmasuk) {
        header("Location: keluar.php");
    } else {
        echo "gagal";
        header("Location: keluar.php");
    }    
}

// Update Info Barang 
if (isset($_POST['updatebarang'])){
    $idbarang = $_POST['idbarang'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    // Perbaikan variabel $idb menjadi $idbarang
    $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi' WHERE idbarang='$idbarang'");
    if ($update) {
        header("Location: index.php");
    } else {
        echo "gagal";
        header("Location: index.php");
    } 
}

// Hapus Barang dari Stock
if (isset($_POST['hapusbarang'])){
    $idbarang = $_POST['idbarang'];

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idbarang'");
    if ($hapus) {
        header("Location: index.php");
    } else {
        echo "gagal";
        header("Location: index.php");
    } 
}

// Edit Barang Masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idk = $_POST['idk'];
    $idbarang = $_POST['idbarang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Ambil data stok barang
    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idbarang'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    // Ambil data jumlah barang masuk
    $qtysekarang = mysqli_query($conn, "SELECT * FROM masuk WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    // Perhitungan dan update stok barang
    if ($qty > $qtysekarang) {
        $selisih = $qty - $qtysekarang;
        $kurangin = $stocksekarang + $selisih;

        // Update stok barang
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idbarang'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");

        if ($kurangistocknya && $updatenya) {
            header("Location: masuk.php");
        } else {
            echo "gagal";
            header("Location: index.php");
        }
    } else {
        $selisih = $qtysekarang - $qty;
        $kurangin = $stocksekarang - $selisih;

        // Update stok barang
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idbarang'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");

        if ($kurangistocknya && $updatenya) {
            header("Location: masuk.php");
        } else {
            echo "gagal";
            header("Location: index.php");
        }
    }
}


// Hapus Barang Masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idm = $_POST['idmasuk'];
    $idbarang = $_POST['idbarang'];
    $qty = $_POST['kty'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idbarang'");
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idmasuk'");
    
    if ($update && $hapusdata) {
        header("Location: masuk.php");
    } else {
        echo "gagal";
        header("Location: masuk.php");
    } 
}

// Edit Barang Keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idk = $_POST['idkeluar'];
    $idbarang = $_POST['idbarang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Ambil data stok barang
    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idbarang'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    // Ambil data jumlah barang masuk
    $qtysekarang = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    // Perhitungan dan update stok barang
    if ($qty > $qtysekarang) {
        $selisih = $qty - $qtysekarang;
        $kurangin = $stocksekarang - $selisih;

        // Update stok barang
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idbarang'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");

        if ($kurangistocknya && $updatenya) {
            header("Location: keluar.php");
        } else {
            echo "gagal";
            header("Location: keluar.php");
        }
    } else {
        $selisih = $qtysekarang - $qty;
        $kurangin = $stocksekarang + $selisih;

        // Update stok barang
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idbarang'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");

        if ($kurangistocknya && $updatenya) {
            header("Location: keluar.php");
        } else {
            echo "gagal";
            header("Location: keluar.php");
        }
    }
}


// Hapus Barang Keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idk = $_POST['idkeluar'];
    $idbarang = $_POST['idbarang'];
    $qty = $_POST['kty'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock + $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idbarang'");
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");
    
    if ($update && $hapusdata) {
        header("Location: keluar.php");
    } else {
        echo "gagal";
        header("Location: keluar.php");
    } 
}
?>
