<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Add new transaction
    public function add_transaction($data) {
        return $this->db->insert('transactions', $data);
    }

    // Get transaction by ID
    public function get_transaction($transaction_id) {
        $this->db->where('transaction_id', $transaction_id);
        $query = $this->db->get('transactions');
        return $query->row_array();
    }

    // Get all transactions
    public function get_all_transactions() {
        $this->db->select('transactions.*, products.name as product_name');
        $this->db->from('transactions');
        $this->db->join('products', 'transactions.item_id = products.item_id');
        $this->db->order_by('transaction_time', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get transactions for a specific product
    public function get_product_transactions($item_id) {
        $this->db->where('item_id', $item_id);
        $this->db->order_by('transaction_time', 'DESC');
        $query = $this->db->get('transactions');
        return $query->result_array();
    }

    // Get recent transactions
    public function get_recent_transactions($limit = 10) {
        $this->db->select('transactions.*, products.name as product_name');
        $this->db->from('transactions');
        $this->db->join('products', 'transactions.item_id = products.item_id');
        $this->db->order_by('transaction_time', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
} 