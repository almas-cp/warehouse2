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

    // Get recent transactions with product details
    public function get_recent_transactions($limit = 10) {
        $this->db->select('t.*, p.name as product_name, p.category');
        $this->db->from('transactions t');
        $this->db->join('products p', 't.item_id = p.item_id');
        $this->db->order_by('t.transaction_time', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get transaction statistics
    public function get_transaction_stats() {
        // Total transactions
        $total_query = $this->db->count_all_results('transactions');
        
        // Check-in count
        $this->db->where('transaction_type', 'check-in');
        $checkin_count = $this->db->count_all_results('transactions');
        
        // Check-out count
        $this->db->where('transaction_type', 'check-out');
        $checkout_count = $this->db->count_all_results('transactions');
        
        // Today's transactions
        $today = date('Y-m-d');
        $this->db->where('DATE(transaction_time)', $today);
        $today_count = $this->db->count_all_results('transactions');
        
        return [
            'total' => $total_query,
            'checkin_count' => $checkin_count,
            'checkout_count' => $checkout_count,
            'today_count' => $today_count
        ];
    }

    // Get transactions by date range
    public function get_transactions_by_date_range($start_date, $end_date) {
        $this->db->select('t.*, p.name as product_name, p.category');
        $this->db->from('transactions t');
        $this->db->join('products p', 't.item_id = p.item_id');
        $this->db->where('DATE(t.transaction_time) >=', $start_date);
        $this->db->where('DATE(t.transaction_time) <=', $end_date);
        $this->db->order_by('t.transaction_time', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get transactions by benefactor
    public function get_transactions_by_benefactor($benefactor) {
        $this->db->select('t.*, p.name as product_name, p.category');
        $this->db->from('transactions t');
        $this->db->join('products p', 't.item_id = p.item_id');
        $this->db->like('t.benefactor', $benefactor);
        $this->db->order_by('t.transaction_time', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get transactions by type
    public function get_transactions_by_type($type) {
        $this->db->select('t.*, p.name as product_name, p.category');
        $this->db->from('transactions t');
        $this->db->join('products p', 't.item_id = p.item_id');
        $this->db->where('t.transaction_type', $type);
        $this->db->order_by('t.transaction_time', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
} 