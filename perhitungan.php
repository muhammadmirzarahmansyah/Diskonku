<?php
// Menyertakan koneksi database
include('kon.php');

$buktiTransaksi = "";

// Menambahkan data produk baru jika form disubmit
if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $hargaasli = $_POST['hargaasli'];
    $diskon = $_POST['diskon'];

    // Validasi diskon
    if ($diskon <= 0 || $diskon >= 100) {
        $buktiTransaksi = "
        <div class='bukti-box'>
            <div class='bukti-header'>
                <h3 id=hasil>Peringatan:</h3>
            </div>
            <p style='color:red;'><strong>Diskon harus lebih dari 0 dan kurang dari 100!</strong></p>
        </div>
        ";
    } else {
        $diskon_rp = ($hargaasli * $diskon) / 100;
        $jumlahbayar = $hargaasli - $diskon_rp;

        $query = "INSERT INTO diskon (nama_produk, hargaasli, diskon, jumlahbayar) 
                  VALUES ('$nama_produk', '$hargaasli', '$diskon','$jumlahbayar')";

        if ($conn->query($query) === TRUE) {
            $buktiTransaksi = "
            <div class='bukti-box'>
                <div class='bukti-header'>
                    <h3 id=hasil    >Hasil:</h3>
                    <div class='tools'>
                        <button onclick='window.print()' title='Cetak'><i class='fa fa-print'></i></button>
                        <button onclick='salinBukti()' title='Salin'><i class='fa fa-copy'></i></button>
                    </div>
                </div>
                <p><strong>Diskon Anda</strong></p>
                <table class='bukti-table'>
                    <tr>
                        <td>Nama Produk</td>
                        <td><strong> " . ($nama_produk) . "</strong></td>
                    </tr>
                    <tr>
                        <td>Harga Asli</td>
                        <td><strong>Rp " . number_format($hargaasli, 0, ',', '.') . "</strong></td>
                    </tr>
                    <tr>
                        <td>Diskon</td>
                        <td><strong>Rp " . number_format($diskon_rp, 0, ',', '.') . " ($diskon%)</strong></td>
                    </tr>
                    <tr>
                        <td>Harga Setelah Diskon</td>
                        <td><strong>Rp " . number_format($jumlahbayar, 0, ',', '.') . "</strong></td>
                    </tr>
                </table>
            </div>
            ";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}


// Menampilkan daftar produk dan diskon
$query = "SELECT * FROM diskon";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Diskon</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .bukti-box {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .bukti-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bukti-header h3 {
            margin: 0;
            color: #2c3e50;
        }

        .tools button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            color: #2c3e50;
        }

        .tools button:hover {
            color: #3498db;
        }

        .bukti-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .bukti-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }

        .bukti-table td:last-child {
            text-align: left;
        }

        .reset-btn {
        background-color: #b22222; /* warna merah tua */
        border: none;
        color: white;
        font-size: 16px;
        padding: 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
        margin-top: 10px;
        width: 100%;
        }

        .reset-btn:hover {
        background-color: #8b0000;
        }

        .back-home {
      background-color: #eaf2f1;
      border-radius: 10px;
      padding: 12px 20px;
      box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s, transform 0.3s;
      display: inline-block;
      margin-bottom: 40px;
    }

    .back-home a {
      color: #2d936c;
      font-weight: bold;
      font-size: 16px;
      text-decoration: none;
    }

    .back-home i {
      margin-right: 10px;
    }

    .back-home:hover {
      background-color: #2d936c;
      transform: translateY(-3px);
    }

    .back-home:hover a {
      color: white;
    }

    .back-home:hover i {
      color: white;
    }

        @media print {
    body * {
        visibility: hidden;
    }
    .bukti-box, .bukti-box * {
        visibility: visible;
    }
    .bukti-box {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
}

    </style>
</head>
<body>


    <div class="container">
        <h1>Aplikasi Diskon</h1>

        <div class="back-home">
            <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
        <!-- Form untuk menambah produk -->
        <h2>Tambah Produk Baru dan Hitung Diskon</h2>
        <form action="#hasil" method="POST">
            <label for="nama_produk">Nama Produk:</label>
            <input type="text" name="nama_produk" id="nama_produk" required>

            <label for="hargaasli">Harga Asli (Rp):</label>
            <input type="number" name="hargaasli" id="hargaasli" required>

            <label for="diskon">Persentase Diskon (%):</label>
            <input type="number" name="diskon" id="diskon" required>

            <input type="submit" name="submit" value="Hitung Diskon">
            <input type="reset" value="Reset Form" class="reset-btn">

        </form>

        <!-- Bukti Transaksi -->
        <?php echo $buktiTransaksi; ?>

        
    </div>

    <!-- Script untuk salin bukti -->
    <script>
        function salinBukti() {
            const text = document.querySelector('.bukti-box').innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Bukti transaksi berhasil disalin!');
            });
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
