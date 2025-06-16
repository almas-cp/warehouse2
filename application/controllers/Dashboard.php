<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('transaction_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Warehouse Inventory System';
        $data['products'] = $this->product_model->get_all_products();
        $data['recent_transactions'] = $this->transaction_model->get_recent_transactions(5);
        $data['stats'] = $this->get_dashboard_stats();
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }

    private function get_dashboard_stats() {
        $this->db->select('COUNT(*) as total_products, SUM(quantity) as total_items');
        $products_query = $this->db->get('products');
        $products_stats = $products_query->row_array();
        
        $this->db->where('quantity <= 5');
        $low_stock_count = $this->db->count_all_results('products');
        
        $this->db->select('COUNT(*) as total_transactions');
        $transactions_query = $this->db->get('transactions');
        $transactions_stats = $transactions_query->row_array();
        
        $this->db->where('transaction_type', 'check-in');
        $checkin_count = $this->db->count_all_results('transactions');
        
        $this->db->where('transaction_type', 'check-out');
        $checkout_count = $this->db->count_all_results('transactions');
        
        return [
            'total_products' => $products_stats['total_products'],
            'total_items' => $products_stats['total_items'],
            'low_stock_count' => $low_stock_count,
            'total_transactions' => $transactions_stats['total_transactions'],
            'checkin_count' => $checkin_count,
            'checkout_count' => $checkout_count
        ];
    }

    // Product CRUD Operations
    
    public function add_product() {
        $this->form_validation->set_rules('name', 'Product Name', 'required');
        $this->form_validation->set_rules('unit_price', 'Unit Price', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer');
        $this->form_validation->set_rules('category', 'Category', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }
        
        $product_data = [
            'name' => $this->input->post('name'),
            'unit_price' => $this->input->post('unit_price'),
            'quantity' => $this->input->post('quantity'),
            'category' => $this->input->post('category')
        ];
        
        if ($this->product_model->add_product($product_data)) {
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
        }
    }
    
    public function get_product($item_id) {
        $product = $this->product_model->get_product($item_id);
        
        if ($product) {
            echo json_encode(['status' => 'success', 'data' => $product]);
        } else {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        }
    }
    
    public function update_product() {
        $item_id = $this->input->post('item_id');
        
        $this->form_validation->set_rules('name', 'Product Name', 'required');
        $this->form_validation->set_rules('unit_price', 'Unit Price', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer');
        $this->form_validation->set_rules('category', 'Category', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }
        
        $product_data = [
            'name' => $this->input->post('name'),
            'unit_price' => $this->input->post('unit_price'),
            'quantity' => $this->input->post('quantity'),
            'category' => $this->input->post('category')
        ];
        
        if ($this->product_model->update_product($item_id, $product_data)) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
        }
    }
    
    public function delete_product($item_id) {
        if ($this->product_model->delete_product($item_id)) {
            echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
        }
    }
    
    // Get all products for AJAX request
    public function get_all_products() {
        $products = $this->product_model->get_all_products();
        echo json_encode(['status' => 'success', 'data' => $products]);
    }
    
    // Transaction Processing
    
    public function add_transaction() {
        $this->form_validation->set_rules('item_id', 'Product ID', 'required|integer');
        $this->form_validation->set_rules('transaction_type', 'Transaction Type', 'required|in_list[check-in,check-out]');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('benefactor', 'Benefactor', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }
        
        $item_id = $this->input->post('item_id');
        $transaction_type = $this->input->post('transaction_type');
        $quantity = $this->input->post('quantity');
        $benefactor = $this->input->post('benefactor');
        $notes = $this->input->post('notes');
        
        // Check if we have enough quantity for check-out
        if ($transaction_type === 'check-out') {
            if (!$this->product_model->has_enough_quantity($item_id, $quantity)) {
                $this->output->set_status_header(400);
                echo json_encode(['status' => 'error', 'message' => 'Not enough quantity available for check-out']);
                return;
            }
            // Negative quantity for check-out
            $quantity_change = -$quantity;
        } else {
            // Positive quantity for check-in
            $quantity_change = $quantity;
        }
        
        // Start transaction
        $this->db->trans_start();
        
        // Add transaction record
        $transaction_data = [
            'item_id' => $item_id,
            'transaction_type' => $transaction_type,
            'quantity' => $quantity,
            'benefactor' => $benefactor,
            'notes' => $notes
        ];
        
        $this->transaction_model->add_transaction($transaction_data);
        
        // Update product quantity
        $this->product_model->update_quantity($item_id, $quantity_change);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Transaction failed']);
            return;
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Transaction processed successfully']);
    }
    
    public function get_recent_transactions() {
        $limit = $this->input->get('limit') ? $this->input->get('limit') : 10;
        $transactions = $this->transaction_model->get_recent_transactions($limit);
        echo json_encode(['status' => 'success', 'data' => $transactions]);
    }
    
    public function get_product_transactions($item_id) {
        $transactions = $this->transaction_model->get_product_transactions($item_id);
        echo json_encode(['status' => 'success', 'data' => $transactions]);
    }
    
    // Barcode Generation
    
    public function generate_barcode($item_id) {
        $product = $this->product_model->get_product($item_id);
        
        if (!$product) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            return;
        }
        
        // Load the barcode URL
        $barcode_url = "https://barcodeapi.org/api/code128/{$item_id}";
        
        // Return the barcode data
        echo json_encode([
            'status' => 'success', 
            'data' => [
                'item_id' => $product['item_id'],
                'name' => $product['name'],
                'barcode_url' => $barcode_url
            ]
        ]);
    }
    
    public function print_labels() {
        $item_ids = $this->input->post('item_ids');
        
        if (empty($item_ids)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'No items selected for printing']);
            return;
        }
        
        $products = [];
        foreach ($item_ids as $item_id) {
            $product = $this->product_model->get_product($item_id);
            if ($product) {
                $product['barcode_url'] = "https://barcodeapi.org/api/code128/{$item_id}";
                $products[] = $product;
            }
        }
        
        echo json_encode(['status' => 'success', 'data' => $products]);
    }
} 