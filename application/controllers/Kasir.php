<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Kasir_model $Kasir_model
 * @property CI_Input $input
 * @property CI_Loader $load
 */

#[AllowDynamicProperties] // Fix untuk PHP 8.2+
class Kasir extends CI_Controller {

    // 1. CONSTRUCTOR
    public function __construct() {
        parent::__construct();
        // Load Model & Helper
        $this->load->model('Kasir_model');
        $this->load->helper(array('url', 'form'));
    }

    // 2. INDEX: Menampilkan Halaman Kasir
    public function index() {
        // Ambil data barang dari Model
        $data['barang'] = $this->Kasir_model->get_all_barang();
        
        // Tampilkan View
        $this->load->view('kasir_view', $data);
    }

    // 3. PROSES BAYAR: Menangani Logika Transaksi
    public function proses_bayar() {
        // Ambil semua data inputan
        $input = $this->input->post();

        // Validasi: Cek apakah ada barang yang dipilih?
        if (empty($input['barang_id'])) {
             echo "<script>
                    alert('Keranjang belanja kosong! Mohon pilih barang terlebih dahulu.'); 
                    window.history.back();
                  </script>";
             return; 
        }

        // Panggil Model untuk simpan transaksi
        $simpan = $this->Kasir_model->simpan_transaksi($input);

        // Cek hasil penyimpanan
        if ($simpan) {
            echo "<script>
                    alert('Transaksi Berhasil Disimpan!'); 
                    window.location='".base_url('kasir')."';
                  </script>";
        } else {
            echo "<script>
                    alert('Transaksi Gagal! Cek stok atau database.'); 
                    window.history.back();
                  </script>";
        }
    }

} // <--- PENUTUP CLASS HANYA SATU DI SINI (PALING BAWAH)