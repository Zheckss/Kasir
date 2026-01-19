<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Kasir extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kasir_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }

    public function index() {
        $data['barang'] = $this->Kasir_model->get_all_barang();
        $this->load->view('kasir_view', $data);
    }

    public function get_item_json() {
        error_reporting(0);
        ini_set('display_errors', 0);
        header('Content-Type: application/json');

        $id_barang = $this->input->post('id');
        $data_barang = $this->Kasir_model->get_barang_by_id($id_barang);

        echo json_encode($data_barang);
    }

    public function proses_bayar() {
        $input = $this->input->post();

        if (empty($input['barang_id'])) {
             $this->session->set_flashdata('error', 'Keranjang belanja kosong!');
             redirect('kasir');
             return; 
        }

        $id_transaksi = $this->Kasir_model->simpan_transaksi($input);

        if ($id_transaksi) {
            $this->session->set_flashdata('last_trx_id', $id_transaksi);
            redirect('kasir');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan transaksi ke database!');
            redirect('kasir');
        }
    }

    public function cetak_struk($id) {
        $data['transaksi'] = $this->Kasir_model->get_data_struk($id);
        
        if(empty($data['transaksi'])) {
            die("Data transaksi tidak ditemukan.");
        }

        $this->load->view('struk_view', $data);
    }

}