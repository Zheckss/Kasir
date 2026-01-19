<!DOCTYPE html>
<html>
<head>
    <title>Struk Belanja</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; 
            font-size: 12px;
            width: 300px;
            margin: 0;
            padding: 10px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        table { width: 100%; }
        td { padding: 2px 0; vertical-align: top; }
        
        @media print {
            .btn-print { display: none; }
        }
        .btn-print {
            background: #333; color: #fff; border: none; 
            padding: 5px 10px; width: 100%; margin-top: 10px; cursor: pointer;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <strong>TOKO SERBA ADA</strong><br>
        Jl. Merdeka No. 45<br>
        Jakarta Barat
    </div>

    <div class="line"></div>

    <?php 
        $header = $transaksi[0]; 
    ?>

    <table>
        <tr>
            <td>No TRX</td>
            <td class="right"><?php echo $header->no_transaksi; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="right"><?php echo date('d/m/Y H:i', strtotime($header->tanggal)); ?></td>
        </tr>
        <tr>
            <td>Metode</td>
            <td class="right"><?php echo $header->metode_pembayaran; ?></td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <?php foreach($transaksi as $item): ?>
        <tr>
            <td colspan="2"><?php echo $item->nama_barang; ?></td>
        </tr>
        <tr>
            <td><?php echo $item->jumlah; ?> x <?php echo number_format($item->harga, 0, ',', '.'); ?></td>
            <td class="right"><?php echo number_format($item->subtotal, 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="right"><strong>Rp <?php echo number_format($header->total_bayar, 0, ',', '.'); ?></strong></td>
        </tr>
    </table>

    <div class="center">
        <br>
        Terima Kasih!<br>
        Barang yang dibeli tidak dapat ditukar.
    </div>

    <button class="btn-print" onclick="window.print()">Cetak Struk</button>

</body>
</html>