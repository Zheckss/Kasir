<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir Modern</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            min-height: 100vh;
            padding-bottom: 50px;
        }
        
        .card-kasir {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            background: white;
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(45deg, #2575fc, #6a11cb); 
            color: white;
            padding: 20px;
            border-bottom: none;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            background-color: #f8f9fa;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(37, 117, 252, 0.1);
            border-color: #2575fc;
        }

        .table-custom th {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: 600;
            border-top: none;
        }
        .table-custom td {
            vertical-align: middle;
        }

        .total-box {
            background-color: #e8f5e9; 
            color: #2e7d32;
            padding: 15px;
            border-radius: 15px;
            text-align: right;
        }
        .total-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
        }
        .total-amount {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .btn-custom-add {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-bayar {
            border-radius: 12px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: linear-gradient(45deg, #11998e, #38ef7d); 
            border: none;
            box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4);
            transition: transform 0.2s;
        }
        .btn-bayar:hover {
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(56, 239, 125, 0.6);
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="card card-kasir">
            
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold"><i class="fas fa-cash-register me-2"></i> Aplikasi Kasir</h4>
                    <small style="opacity: 0.8;">Kelola transaksi dengan mudah dan cepat</small>
                </div>
                <div>
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill">
                        <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y'); ?>
                    </span>
                </div>
            </div>

            <div class="card-body p-4">
                
                <div class="row g-3 mb-4 bg-light p-3 rounded-3 mx-1 border">
                    <div class="col-md-5">
                        <label class="form-label text-muted fw-bold small">Pilih Produk</label>
                        <select id="pilih_barang" class="form-select">
                            <option value="">-- Cari Nama Barang --</option>
                            <?php foreach($barang as $b): ?>
                                <option value="<?php echo $b->id; ?>">
                                    <?php echo $b->nama_barang; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted fw-bold small">Jumlah</label>
                        <input type="number" id="qty" class="form-control text-center" placeholder="1" min="1" value="1">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-custom-add w-100" id="tambah_barang">
                            <i class="fas fa-plus-circle me-1"></i> Tambah
                        </button>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                         <small class="text-muted fst-italic" id="info_stok"></small>
                    </div>
                </div>

                <form action="<?php echo base_url('index.php/kasir/proses_bayar'); ?>" method="post">
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-hover table-custom" id="keranjang">
                            <thead>
                                <tr>
                                    <th width="40%">Nama Barang</th>
                                    <th>Harga Satuan</th>
                                    <th width="10%" class="text-center">Qty</th>
                                    <th>Subtotal</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold mb-2 text-dark"><i class="fas fa-wallet me-1 text-primary"></i> Metode Pembayaran</label>
                                <select name="metode_bayar" class="form-select" required>
                                    <option value="Tunai">💵 Tunai (Cash)</option>
                                    <option value="QRIS">📱 QRIS / E-Wallet</option>
                                    <option value="Transfer">🏦 Transfer Bank</option>
                                    <option value="Debit">💳 Kartu Debit</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="total-box shadow-sm">
                                <div class="total-label">Total Yang Harus Dibayar</div>
                                <h1 class="total-amount" id="tampilan_total">Rp 0</h1>
                                <input type="hidden" name="grand_total" id="grand_total" value="0">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-bayar btn-success w-100 text-white">
                        <i class="fas fa-check-circle me-2"></i> PROSES PEMBAYARAN
                    </button>
                </form>
            </div>
        </div>
        
    </div>

<script>
    $(document).ready(function(){
        let total_belanja = 0;
        let barang_selected = { id: "", nama: "", harga: 0, stok: 0 };

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        $("#pilih_barang").change(function(){
            let id_barang = $(this).val();

            if(id_barang == "") {
                barang_selected = {id: "", nama: "", harga: 0, stok: 0};
                $("#info_stok").text("");
                return;
            }

            $.ajax({
                url: "<?php echo base_url('index.php/kasir/get_item_json'); ?>", 
                method: "POST",
                data: {id: id_barang},
                dataType: "json",
                success: function(response) {
                    if(response == null) {
                        alert("Data tidak ditemukan!");
                        return;
                    }
                    barang_selected.id = response.id;
                    barang_selected.nama = response.nama_barang;
                    barang_selected.harga = parseInt(response.harga); 
                    barang_selected.stok = parseInt(response.stok);

                    let harga_indo = formatRupiah(response.harga);
                    
                    $("#info_stok").html("Stok tersedia: <strong>" + response.stok + "</strong>");
                },
                error: function() { alert("Gagal mengambil data barang."); }
            });
        });

        $("#tambah_barang").click(function(){
            if(barang_selected.id == "") {
                alert("Silakan pilih barang terlebih dahulu!");
                return;
            }

            let qty = parseInt($("#qty").val());
            
            if(qty > barang_selected.stok) {
                alert("Stok tidak cukup! Sisa: " + barang_selected.stok);
                return; 
            }

            let subtotal = barang_selected.harga * qty;
            let harga_tampil = formatRupiah(barang_selected.harga);
            let subtotal_tampil = formatRupiah(subtotal);

            let html = `
                <tr>
                    <td>
                        <div class="fw-bold">${barang_selected.nama}</div>
                        <input type="hidden" name="barang_id[]" value="${barang_selected.id}">
                    </td>
                    <td>Rp ${harga_tampil}</td>
                    <td class="text-center">
                        ${qty}
                        <input type="hidden" name="qty[]" value="${qty}">
                    </td>
                    <td class="fw-bold text-primary">
                        Rp ${subtotal_tampil}
                        <input type="hidden" name="subtotal[]" value="${subtotal}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm hapus-baris rounded-circle shadow-sm" data-sub="${subtotal}" title="Hapus Item">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;

            $("#keranjang tbody").append(html);

            total_belanja += subtotal;
            $("#tampilan_total").text("Rp " + formatRupiah(total_belanja)); 
            $("#grand_total").val(total_belanja);

            $("#pilih_barang").val("");
            $("#qty").val(1);
            $("#info_stok").text("");
            barang_selected = {id: "", nama: "", harga: 0, stok: 0};
        });

        $(document).on('click', '.hapus-baris', function(){
            let sub = $(this).data('sub');
            total_belanja -= sub;
            $("#tampilan_total").text("Rp " + formatRupiah(total_belanja)); 
            $("#grand_total").val(total_belanja);
            $(this).closest('tr').remove();
        });

    });
</script>

<?php if($this->session->flashdata('last_trx_id')): ?>
    <script>
        $(document).ready(function(){
            let id_trx = "<?php echo $this->session->flashdata('last_trx_id'); ?>";
            let mau_cetak = confirm("✅ Transaksi Berhasil Disimpan!\n\nApakah Anda ingin mencetak struk belanja?");
            if(mau_cetak) {
                let url = "<?php echo base_url('index.php/kasir/cetak_struk/'); ?>" + id_trx;
                window.open(url, '_blank');
            }
        });
    </script>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <script>
        alert("❌ Error: <?php echo $this->session->flashdata('error'); ?>");
    </script>
<?php endif; ?>

</body>
</html>