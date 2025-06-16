<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all products
    public function get_all_products() {
        $query = $this->db->get('products');
        return $query->result_array();
    }

    // Get product by ID
    public function get_product($item_id) {
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('products');
        return $query->row_array();
    }

    // Add new product
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
        $this->db->where('item_id', $item_id);
        return $this->db->delete('products');
    }

    // Update product quantity
    public function update_quantity($item_id, $quantity_change) {
        $this->db->set('quantity', 'quantity + ' . $quantity_change, FALSE);
        $this->db->where('item_id', $item_id);
        return $this->db->update('products');
    }

    // Check if product has enough quantity
    public function has_enough_quantity($item_id, $quantity_needed) {
        $this->db->select('quantity');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('products');
        $product = $query->row_array();
        
        return ($product['quantity'] >= $quantity_needed);
    }
} 