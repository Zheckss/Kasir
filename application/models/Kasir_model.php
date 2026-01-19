<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Kasir_model extends CI_Model {

    public function get_all_barang() {
        return $this->db->get('barang')->result();
    }

    public function get_barang_by_id($id) {
        return $this->db->get_where('barang', array('id' => $id))->row();
    }

    public function simpan_transaksi($data_input) {
        $this->db->trans_start();

        $data_penjualan = array(
            'no_transaksi'      => 'TRX-' . time(),
            'tanggal'           => date('Y-m-d H:i:s'),
            'total_bayar'       => $data_input['grand_total'],
            'metode_pembayaran' => $data_input['metode_bayar']
        );
        $this->db->insert('penjualan', $data_penjualan);
        
        $id_penjualan = $this->db->insert_id();

        $jumlah_barang = count($data_input['barang_id']);
        for ($i = 0; $i < $jumlah_barang; $i++) {
            $data_detail = array(
                'penjualan_id' => $id_penjualan,
                'barang_id'    => $data_input['barang_id'][$i],
                'jumlah'       => $data_input['qty'][$i],
                'subtotal'     => $data_input['subtotal'][$i]
            );
            $this->db->insert('detail_penjualan', $data_detail);

            $this->db->set('stok', 'stok - ' . $data_input['qty'][$i], FALSE);
            $this->db->where('id', $data_input['barang_id'][$i]);
            $this->db->update('barang');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $id_penjualan; 
        }
    }

    public function get_data_struk($id) {
        $this->db->select('penjualan.*, detail_penjualan.jumlah, detail_penjualan.subtotal, barang.nama_barang, barang.harga');
        $this->db->from('penjualan');
        $this->db->join('detail_penjualan', 'detail_penjualan.penjualan_id = penjualan.id');
        $this->db->join('barang', 'barang.id = detail_penjualan.barang_id');
        $this->db->where('penjualan.id', $id);
        return $this->db->get()->result();
    }
}