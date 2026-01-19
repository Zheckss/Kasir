<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Point of Sales</h4>
            </div>
            <div class="card-body">
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select id="pilih_barang" class="form-control">
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($barang as $b): ?>
                                <option value="<?php echo $b->id; ?>">
                                    <?php echo $b->nama_barang; ?>
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

                <form action="<?php echo base_url('index.php/kasir/proses_bayar'); ?>" method="post">
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
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="fw-bold">Metode Pembayaran:</label>
                            <select name="metode_bayar" class="form-control" required>
                                <option value="Tunai">Tunai (Cash)</option>
                                <option value="QRIS">QRIS (Scan Barcode)</option>
                                <option value="Transfer">Transfer Bank</option>
                                <option value="Debit">Kartu Debit</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Proses Pembayaran</button>
                </form>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function(){
        let total_belanja = 0;
        
        let barang_selected = {
            id: "",
            nama: "",
            harga: 0,
            stok: 0
        };

        $("#pilih_barang").change(function(){
            let id_barang = $(this).val();

            if(id_barang == "") {
                barang_selected = {id: "", nama: "", harga: 0, stok: 0};
                return;
            }

            console.log("Sedang mengambil data untuk ID: " + id_barang);

            $.ajax({
                url: "<?php echo base_url('index.php/kasir/get_item_json'); ?>", 
                method: "POST",
                data: {id: id_barang},
                dataType: "json",
                success: function(response) {
                    console.log("Data diterima:", response);
                    if(response == null) {
                        alert("Data Barang Kosong / Tidak Ditemukan di Database!");
                        return;
                    }
                    barang_selected.id = response.id;
                    barang_selected.nama = response.nama_barang;
                    barang_selected.harga = parseInt(response.harga); 
                    barang_selected.stok = parseInt(response.stok);
                    alert("Sukses! Barang dipilih: " + response.nama_barang); 
                },
                error: function(xhr, status, error) {
                    alert("TERJADI ERROR!\n\nStatus: " + status + "\nPesan: " + error + "\n\nRespon Server:\n" + xhr.responseText);
                }
            });
        });

        $("#tambah_barang").click(function(){
            if(barang_selected.id == "") {
                alert("Pilih barang dulu!");
                return;
            }

            let qty = parseInt($("#qty").val());
            
            if(qty > barang_selected.stok) {
                alert("Stok tidak cukup! Sisa: " + barang_selected.stok);
                return; 
            }

            let subtotal = barang_selected.harga * qty;

            let html = `
                <tr>
                    <td>
                        ${barang_selected.nama}
                        <input type="hidden" name="barang_id[]" value="${barang_selected.id}">
                    </td>
                    <td>Rp ${barang_selected.harga}</td>
                    <td>
                        ${qty}
                        <input type="hidden" name="qty[]" value="${qty}">
                    </td>
                    <td>
                        Rp ${subtotal}
                        <input type="hidden" name="subtotal[]" value="${subtotal}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm hapus-baris" data-sub="${subtotal}">Hapus</button>
                    </td>
                </tr>
            `;

            $("#keranjang tbody").append(html);

            total_belanja += subtotal;
            $("#tampilan_total").text("Rp " + total_belanja);
            $("#grand_total").val(total_belanja);

            $("#pilih_barang").val("");
            $("#qty").val(1);
            barang_selected = {id: "", nama: "", harga: 0, stok: 0};
        });

        $(document).on('click', '.hapus-baris', function(){
            let sub = $(this).data('sub');
            total_belanja -= sub;
            $("#tampilan_total").text("Rp " + total_belanja);
            $("#grand_total").val(total_belanja);
            $(this).closest('tr').remove();
        });
    });
</script>
<?php if($this->session->flashdata('last_trx_id')): ?>
    <script>
        $(document).ready(function(){
            let id_trx = "<?php echo $this->session->flashdata('last_trx_id'); ?>";
            
            let mau_cetak = confirm("Transaksi Berhasil! \nApakah ingin mencetak struk untuk TRX ID: " + id_trx + "?");
            
            if(mau_cetak) {
                let url = "<?php echo base_url('index.php/kasir/cetak_struk/'); ?>" + id_trx;
                window.open(url, '_blank');
            }
        });
    </script>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <script>
        alert("<?php echo $this->session->flashdata('error'); ?>");
    </script>
<?php endif; ?>
</body>
</html>