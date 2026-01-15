<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Kasir extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kasir_model');
        $this->load->helper(array('url', 'form'));
    }

    public function index() {
        $data['barang'] = $this->Kasir_model->get_all_barang();
        $this->load->view('kasir_view', $data);
    }

    // --- INI FUNGSI YANG KITA GANTI ---
    // Sekarang isinya sudah mengambil data asli dari Database via Model
    public function get_item_json() {
        // Matikan error reporting biar JSON bersih
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // Header JSON
        header('Content-Type: application/json');

        // 1. Ambil ID yang dikirim dari View
        $id_barang = $this->input->post('id');

        // 2. Panggil Model untuk ambil data ASLI dari Database
        $data_barang = $this->Kasir_model->get_barang_by_id($id_barang);

        // 3. Kirim balik ke View
        echo json_encode($data_barang);
    }
    // ----------------------------------

    public function proses_bayar() {
        $input = $this->input->post();

        if (empty($input['barang_id'])) {
             echo "<script>
                    alert('Keranjang belanja kosong!'); 
                    window.history.back();
                  </script>";
             return; 
        }

        $simpan = $this->Kasir_model->simpan_transaksi($input);

        if ($simpan) {
            echo "<script>
                    alert('Transaksi Berhasil!'); 
                    window.location='".base_url('kasir')."';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal!'); 
                    window.history.back();
                  </script>";
        }
    }

} // Penutup Class