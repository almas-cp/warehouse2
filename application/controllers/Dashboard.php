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
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
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
} 