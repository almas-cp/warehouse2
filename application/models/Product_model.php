<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all products with optimized query
    public function get_all_products() {
        $this->db->select('p.*, COALESCE(COUNT(t.transaction_id), 0) as transaction_count', FALSE);
        $this->db->from('products p');
        $this->db->join('transactions t', 'p.item_id = t.item_id', 'left');
        $this->db->group_by('p.item_id');
        $this->db->order_by('p.date_added', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get single product
    public function get_product($item_id) {
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('products');
        
        return $query->row_array();
    }

    // Add product
    public function add_product($data) {
        return $this->db->insert('products', $data);
    }

    // Update product
    public function update_product($item_id, $data) {
        $this->db->where('item_id', $item_id);
        return $this->db->update('products', $data);
    }

    // Delete product
    public function delete_product($item_id) {
        // First check if there are related transactions
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('transactions');
        
        if ($query->num_rows() > 0) {
            // Product has transactions, don't allow deletion
            return false;
        }
        
        $this->db->where('item_id', $item_id);
        return $this->db->delete('products');
    }

    // Update product quantity
    public function update_quantity($item_id, $quantity_change) {
        $this->db->set('quantity', 'quantity + ' . $quantity_change, FALSE);
        $this->db->where('item_id', $item_id);
        return $this->db->update('products');
    }

    // Check if product has enough quantity for check-out
    public function has_enough_quantity($item_id, $quantity) {
        $this->db->select('quantity');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('products');
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->quantity >= $quantity;
        }
        
        return false;
    }

    // Get low stock products
    public function get_low_stock_products($threshold = 5) {
        $this->db->where('quantity <=', $threshold);
        $this->db->order_by('quantity', 'ASC');
        $query = $this->db->get('products');
        
        return $query->result_array();
    }

    // Search products by name or category
    public function search_products($search_term) {
        $this->db->like('name', $search_term);
        $this->db->or_like('category', $search_term);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('products');
        
        return $query->result_array();
    }

    // Get product by barcode (using item_id as barcode)
    public function get_product_by_barcode($barcode) {
        $this->db->where('item_id', $barcode);
        $query = $this->db->get('products');
        
        return $query->row_array();
    }
} 