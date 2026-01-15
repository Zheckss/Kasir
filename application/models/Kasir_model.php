<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Kasir_model extends CI_Model {

    // 1. Ambil semua data barang
    public function get_all_barang() {
        return $this->db->get('barang')->result();
    }

    // 2. Ambil satu barang berdasarkan ID (INI YANG TADI DUPLIKAT)
    public function get_barang_by_id($id) {
        return $this->db->get_where('barang', array('id' => $id))->row();
    }

    // 3. Simpan Transaksi
    public function simpan_transaksi($data_input) {
        // Gunakan Transaction agar data konsisten (semua sukses atau semua gagal)
        $this->db->trans_start();

        // A. Simpan ke tabel penjualan (Header)
        $data_penjualan = array(
            'no_transaksi' => 'TRX-' . time(),
            'tanggal'      => date('Y-m-d H:i:s'),
            'total_bayar'  => $data_input['grand_total']
        );
        $this->db->insert('penjualan', $data_penjualan);
        
        // Ambil ID penjualan yang baru saja dibuat
        $id_penjualan = $this->db->insert_id();

        // B. Simpan ke detail_penjualan & Kurangi Stok (Looping)
        $jumlah_barang = count($data_input['barang_id']);
        
        for ($i = 0; $i < $jumlah_barang; $i++) {
            // Insert Detail
            $data_detail = array(
                'penjualan_id' => $id_penjualan,
                'barang_id'    => $data_input['barang_id'][$i],
                'jumlah'       => $data_input['qty'][$i],
                'subtotal'     => $data_input['subtotal'][$i]
            );
            $this->db->insert('detail_penjualan', $data_detail);

            // Kurangi Stok di tabel barang
            $this->db->set('stok', 'stok - ' . $data_input['qty'][$i], FALSE);
            $this->db->where('id', $data_input['barang_id'][$i]);
            $this->db->update('barang');
        }

        $this->db->trans_complete();
        return $this->db->trans_status(); // Return TRUE jika sukses
    }
}