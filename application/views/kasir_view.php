<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Kasir Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Point of Sales (Kasir)</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select id="pilih_barang" class="form-control">
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($barang as $b): ?>
                                <option value="<?php echo $b->id; ?>" data-harga="<?php echo $b->harga; ?>" data-nama="<?php echo $b->nama_barang; ?>">
                                    <?php echo $b->nama_barang; ?> - Rp <?php echo number_format($b->harga); ?> (Stok: <?php echo $b->stok; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" id="qty" class="form-control" placeholder="Qty" min="1" value="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success" id="tambah_barang">Tambah</button>
                    </div>
                </div>

                <form action="<?php echo base_url('kasir/proses_bayar'); ?>" method="post">
                    <table class="table table-bordered" id="keranjang">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" align="right"><strong>Total Bayar:</strong></td>
                                <td>
                                    <strong id="tampilan_total">Rp 0</strong>
                                    <input type="hidden" name="grand_total" id="grand_total" value="0">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <button type="submit" class="btn btn-primary w-100">Proses Pembayaran</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            let total_belanja = 0;

            $("#tambah_barang").click(function(){
                // Ambil data dari inputan
                let id_barang = $("#pilih_barang").val();
                let nama_barang = $("#pilih_barang option:selected").data('nama');
                let harga = $("#pilih_barang option:selected").data('harga');
                let qty = $("#qty").val();

                if(id_barang == "" || qty <= 0) {
                    alert("Pilih barang dan jumlah yang benar!");
                    return;
                }

                let subtotal = harga * qty;

                // Buat baris HTML baru untuk tabel
                // Perhatikan name="barang_id[]" menggunakan kurung siku [] agar bisa dikirim sebagai array ke controller
                let html = `
                    <tr>
                        <td>
                            ${nama_barang}
                            <input type="hidden" name="barang_id[]" value="${id_barang}">
                        </td>
                        <td>Rp ${harga}</td>
                        <td>
                            ${qty}
                            <input type="hidden" name="qty[]" value="${qty}">
                        </td>
                        <td>
                            Rp ${subtotal}
                            <input type="hidden" name="subtotal[]" value="${subtotal}">
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm hapus-baris" data-sub="${subtotal}">Hapus</button></td>
                    </tr>
                `;

                // Masukkan ke tbody
                $("#keranjang tbody").append(html);

                // Update Total Belanja
                total_belanja += subtotal;
                $("#tampilan_total").text("Rp " + total_belanja);
                $("#grand_total").val(total_belanja);

                // Reset input
                $("#pilih_barang").val("");
                $("#qty").val(1);
            });

            // Fitur Hapus Baris
            $(document).on('click', '.hapus-baris', function(){
                let sub = $(this).data('sub');
                total_belanja -= sub;
                
                $("#tampilan_total").text("Rp " + total_belanja);
                $("#grand_total").val(total_belanja);
                
                $(this).closest('tr').remove();
            });
        });
    </script>
</body>
</html>