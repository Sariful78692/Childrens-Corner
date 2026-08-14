<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Internalcode extends Admin_Controller
{


    public function __construct()
    {
        parent::__construct();

        $this->time               = strtotime(date('d-m-Y H:i:s'));
        $this->payment_mode       = $this->customlib->payment_mode();
        $this->search_type        = $this->customlib->get_searchtype();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
        $this->load->model("module_model");
        $this->load->model("accounts_model");
    }

    public function index()
    {

        // Total Fees Collection
        $this->db->select_sum('paid_amount');
        $this->db->where('status', 1);

        $query = $this->db->get('student_fees_collections');
        $total_fees_collection = $query->row()->paid_amount;

        echo $total_fees_collection;
    }

    public function getPaidLogStaffPayslip()
    {
        // Query to select rows where payslip_data contains "status":"paid"
        $query = $this->db->query("SELECT * FROM log_staff_payslip WHERE payslip_data LIKE '%\"status\":\"paid\"%'");

        // Fetch the result as an array of objects
        $data = $query->result();
        $count = 0;
        // Loop through the result to process each entry
        foreach ($data as $row) {
            // Decode the JSON data from the payslip_data column
            $payslipData = json_decode($row->payslip_data, true); // Decoding as an associative array

            // Check if we successfully decoded the data and if 'id' exists
            if (isset($payslipData['id'])) {
                // Extract the id from the JSON data
                $payslipId = $payslipData['id'];
                $madeById = $row->made_by_id;
                $made_by_name = $row->made_by_name;

                // Update the staff_payslip table with made_by_id for the matching id
                $this->db->where('id', $payslipId);
                $this->db->update('staff_payslip', ['paid_by' => $madeById]);
                echo "<pre>";
                print_r($payslipData);
                echo "madeById: $madeById <br>";
                echo "made_by_name: $made_by_name <br>";
                echo "Updated staff_payslip id: $payslipId with paid_by: $madeById <br>";
                $count++;
            } else {
                echo "ID not found in JSON data for log_staff_payslip id: {$row->id} <br>";
            }
        }
        echo "Total updated: $count";
    }
}
