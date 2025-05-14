<?php

$buktiTransaksi = "";
$produk = []; // Array untuk menyimpan data produk sementara

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

        // Menyimpan data produk dalam array
        $produk = [
            'nama_produk' => $nama_produk,
            'hargaasli' => $hargaasli,
            'diskon' => $diskon,
            'diskon_rp' => $diskon_rp,
            'jumlahbayar' => $jumlahbayar
        ];

        $buktiTransaksi = "
        <div class='bukti-box'>
            <div class='bukti-header'>
                <h3 id=hasil>Hasil:</h3>
                <div class='tools'>
                    <button onclick='window.print()' title='Cetak'><i class='fa fa-print'></i></button>
                    <button onclick='salinBukti()' title='Salin'><i class='fa fa-copy'></i></button>
                </div>
            </div>
            <p><strong>Diskon Anda</strong></p>
            <table class='bukti-table'>
                <tr>
                    <td>Nama Produk</td>
                    <td><strong> " . ($produk['nama_produk']) . "</strong></td>
                </tr>
                <tr>
                    <td>Harga Asli</td>
                    <td><strong>Rp " . number_format($produk['hargaasli'], 0, ',', '.') . "</strong></td>
                </tr>
                <tr>
                    <td>Diskon</td>
                    <td><strong>Rp " . number_format($produk['diskon_rp'], 0, ',', '.') . " ($produk[diskon]%)</strong></td>
                </tr>
                <tr>
                    <td>Harga Setelah Diskon</td>
                    <td><strong>Rp " . number_format($produk['jumlahbayar'], 0, ',', '.') . "</strong></td>
                </tr>
            </table>
        </div>
        ";
    }
}
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

        /* Reset default margin dan padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0fbf9;
    color: #333;
    line-height: 1.6;
    padding: 40px 20px;
}

h1, h2 {
    color: #2c3e50;
    text-align: center;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input[type="text"], input[type="number"], input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus, input[type="number"]:focus {
    border-color: #2d936c;
    outline: none;
}

input[type="submit"] {
    background-color: #2d936c;
    color: white;
    cursor: pointer;
    border: none;
    font-size: 16px;
    border-radius: 4px;
    padding: 12px 20px;
}

input[type="submit"]:hover {
    background-color: #238b5f;
}

input[type="reset"] {
    background-color: #b22222; /* warna merah tua */
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    width: 100%;
}

input[type="reset"]:hover {
    background-color: #8b0000;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #2c3e50;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tfoot {
    font-weight: bold;
    background-color: #f4f4f9;
}

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
        <h1>Aplikasi Perhitungan Diskon</h1>

        <!-- Form untuk menambah produk -->
        <br>
        <form action="#hasil" method="POST">
            <label for="nama_produk">Nama Produk:</label>
            <input type="text" name="nama_produk" id="nama_produk" required>

            <label for="hargaasli">Harga Asli (Rp):</label>
            <input type="number" name="hargaasli" id="hargaasli" required>

            <label for="diskon">Persentase Diskon (%):</label>
            <input type="number" name="diskon" id="diskon" required>

            <input type="submit" name="submit" value="Hitung Diskon">
            <input type="reset" value="Reset Form">

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
