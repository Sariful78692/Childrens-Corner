<?php

use Mpdf\Tag\Em;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feesmaster extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        $current_user_id = $this->session->userdata['admin']['id'];

        $current_user_name = $this->session->userdata['admin']['username'];

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feesmaster');

        $data['title']        = $this->lang->line('fees_master_list');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $feetype              = $this->feetype_model->get();
        $data['feetypeList']  = $feetype;

        $data['sessionList'] = $this->session_model->getAllSession();
        $selected_session_id = $this->input->get('session_id') ? $this->input->get('session_id') : $this->setting_model->getCurrentSession();
        $data['selected_session_id'] = $selected_session_id;

        // Get the name of the selected session
        $selected_session_name = '';
        foreach ($data['sessionList'] as $session) {
            if ($session['id'] == $selected_session_id) {
                $selected_session_name = $session['session'];
                break;
            }
        }
        $data['selected_session_name'] = $selected_session_name;

        $this->form_validation->set_rules('class_id', $this->lang->line('class_id'), 'required');
        //$this->form_validation->set_rules('feetype_id', $this->lang->line('fee_type'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');

        $this->db->select('fees_master_class_wise.*, classes.class, sessions.session, feetype.type');
        $this->db->from('fees_master_class_wise');
        $this->db->join('classes', 'classes.id = fees_master_class_wise.class_id');
        $this->db->join('feetype', 'feetype.id = fees_master_class_wise.feetype_id');
        $this->db->join('sessions', 'sessions.id = fees_master_class_wise.session_id');
        $this->db->where('fees_master_class_wise.session_id', $selected_session_id);
		$this->db->order_by('classes.class_order', 'ASC');
        $this->db->order_by('fees_master_class_wise.fees_order', 'ASC');
        $query = $this->db->get();
        $result = $query->result();

        // Organize the data by class name
        $feemasterList = [];
        foreach ($result as $row) {
            $feemasterList[$row->class][] = array(
                'id' => $row->id,
                'type' => $row->type,
                'session' => $row->session,
                'class_id' => $row->class_id,
                'fees_amount' => $row->fees_amount,
                'tuition_fees' => $row->tuition_fees, // Add tuition_fees
                'meal_charges' => $row->meal_charges, // Add meal_charges
                'is_monthly' => $row->is_monthly
            );
        }

        $data['feemasterList'] = $feemasterList;
        // Now $result contains the list of entries from fees_master_class_wise table joined with classes and feetype tables

        $data['sessionList'] = $this->session_model->getAllSession();

        /* echo "<pre>";
        print_r($result);
        die; */


        if ($this->form_validation->run() == false) {

            if (isset($_GET['action']) && $_GET['action'] == 'edit') {

                $select_class_fee_id = $_GET['id'];

                $paid_amount_rows = $this->db->select('id, class_id, session_id, feetype_id, tuition_fees, meal_charges, fees_amount, is_monthly')
                    ->where('id', $select_class_fee_id)
                    ->get('fees_master_class_wise')
                    ->row();

                $data['selected_data'] = $paid_amount_rows;
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/feesmaster/feemasterList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $is_monthly = $this->input->post('is_monthly');
            $tuition_fees = 0;
            $meal_charges = 0;
            $fees_amount = $this->input->post('amount');

            if ($is_monthly == '1') { // If 'MONTHLY' is chosen
                $this->form_validation->set_rules('tuition_fees', $this->lang->line('tuition_fees'), 'required|numeric');
                /* $this->form_validation->set_rules('meal_charges', $this->lang->line('meal_charges'), 'required|numeric'); */

                if ($this->form_validation->run() == false) {
                    // If validation fails for tuition_fees/meal_charges, re-render the form
                    $this->load->view('layout/header', $data);
                    $this->load->view('admin/feesmaster/feemasterList', $data);
                    $this->load->view('layout/footer', $data);
                    return; // Stop execution
                }

                $tuition_fees = $this->input->post('tuition_fees');
                $meal_charges = $this->input->post('meal_charges');
                $fees_amount = (float) $tuition_fees + (float) $meal_charges; // Sum for fees_amount
            }

            $this->db->select('fees_order');
            $this->db->from('fees_master_class_wise');
            $this->db->where('class_id', $this->input->post('class_id'));
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get();

            $result = $query->row(); // Fetch the single result

            $last_order = 0;
            if (!empty($result) && $result->fees_order != "") {
                $last_order = $result->fees_order;
            }

            // Assuming 'feetype_id' is received as an array from the form
            $feetype_ids = $this->input->post('feetype_id');

            if (isset($_POST['class_fees_id']) && $_POST['class_fees_id'] != '') { // Update existing record

                $select_class_fee_id = $_POST['class_fees_id'];

                $update_array = array(
                    'class_id' => $this->input->post('class_id'),
                    'session_id' => $this->input->post('session_id'),
                    'feetype_id' => $feetype_ids[0],
                    'fees_amount' => $fees_amount, // Use calculated or direct amount
                    'tuition_fees' => $tuition_fees, // New field
                    'meal_charges' => $meal_charges, // New field
                    'is_monthly' => $is_monthly,
                    'created_by' => $current_user_id
                );

                $this->db->where('id', $select_class_fee_id);
                $this->db->update('fees_master_class_wise', $update_array);

                $log_data = array_merge(array('id' => $select_class_fee_id), $update_array);

                $action = 2;
                $user_id = $current_user_id;
                $user_name = $current_user_name;
                log_fees_setup($log_data, $action, $user_id, $user_name);
                /**inserted data to log table */
            } else { // Insert new record
                // Iterate over each feetype_id
                foreach ($feetype_ids as $feetype_id) {
                    $last_order++;
                    // Check if the combination of class_id and feetype_id already exists
                    $existing_entry = $this->db->get_where('fees_master_class_wise', array('class_id' => $this->input->post('class_id'), 'session_id' => $this->input->post('session_id'), 'feetype_id', $feetype_id))->row();


                    // If the combination does not exist, insert a new row
                    if (!$existing_entry) {
                        $insert_array = array(
                            'session_id' => $this->input->post('session_id'),
                            'class_id' => $this->input->post('class_id'),
                            'feetype_id' => $feetype_id,
                            'fees_amount' => $fees_amount, // Use calculated or direct amount
                            'tuition_fees' => $tuition_fees, // New field
                            'meal_charges' => $meal_charges, // New field
                            'is_monthly' => $is_monthly,
                            'fees_order' => $last_order,
                            'created_by' => $current_user_id
                            // Add other fields as needed
                        );

                        // Insert data into the 'fees_master_class_wise' table
                        $this->db->insert('fees_master_class_wise', $insert_array);
                        $insert_id = $this->db->insert_id();

                        if ($insert_id) {

                            $log_data = array_merge(array('id' => $insert_id), $insert_array);

                            $action = 1;
                            $user_id = $current_user_id;
                            $user_name = $current_user_name;
                            log_fees_setup($log_data, $action, $user_id, $user_name);
                            /**inserted data to log table */
                        }
                    } else {
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">You have already added this data, now you can only update fees.</div>');
                        redirect('admin/feesmaster/index');
                    }
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feesmaster/index');
        }
    }

    public function update_order()
    {
        $classOrders = $this->input->post('classOrders');

        if ($classOrders) {
            foreach ($classOrders as $class_id => $orders) {
                foreach ($orders as $order) {
                    $this->db->where('id', $order['id']);
                    $this->db->where('class_id', $class_id);
                    $this->db->update('fees_master_class_wise', [
                        'fees_order' => $order['position']
                    ]);
                }
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }



    public function delete($id)
    {

        if (!$this->rbac->hasPrivilege('fees_master', 'can_delete')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('fees_master_list');
        //$this->feegrouptype_model->remove($id);

        $this->db->where("id", $id)->delete("fees_master_class_wise");

        redirect('admin/feesmaster/index');
    }

    public function deletegrp($id)
    {
        $data['title'] = $this->lang->line('fees_master_list');
        $this->feesessiongroup_model->remove($id);
        redirect('admin/feemaster');
    }

    public function edit($id)
    {


        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']            = $id;
        $feegroup_type         = $this->feegrouptype_model->get($id);
        $data['feegroup_type'] = $feegroup_type;
        $feegroup              = $this->feegroup_model->get();
        $data['feegroupList']  = $feegroup;
        $feetype               = $this->feetype_model->get();
        $data['feetypeList']   = $feetype;
        $feegroup_result       = $this->feesessiongroup_model->getFeesByGroup(null, 0);
        $data['feemasterList'] = $feegroup_result;
        $this->form_validation->set_rules('feetype_id', $this->lang->line('fee_type'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');
        $this->form_validation->set_rules(
            'fee_groups_id',
            $this->lang->line('fee_group'),
            array(
                'required',
                array('check_exists', array($this->feesessiongroup_model, 'valid_check_exists')),
            )
        );

        if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
            $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feesmaster/feemasterEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {

            if ($this->input->post('fine_amount')) {
                $fine_amount    =   convertCurrencyFormatToBaseAmount($this->input->post('fine_amount'));
            } else {
                $fine_amount    = '';
            }

            $insert_array = array(
                'id'              => $this->input->post('id'),
                'feetype_id'      => $this->input->post('feetype_id'),
                'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                'amount'          => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'fine_type'       => $this->input->post('account_type'),
                'fine_percentage' => $this->input->post('fine_percentage'),
                'fine_amount'     => $fine_amount,
            );

            $feegroup_result = $this->feegrouptype_model->add($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feesmaster/index');
        }
    }

    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']              = $id;
        $data['title']           = $this->lang->line('student_fees');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $feegroup_result         = $this->feesessiongroup_model->getFeesByGroup($id);
        $data['feegroupList']    = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $genderList            = $this->customlib->getGender();
        $data['genderList']    = $genderList;
        $RTEstatusList         = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;

        $category             = $this->category_model->get();
        $data['categorylist'] = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender']      = $this->input->post('gender');
            $data['rte_status']  = $this->input->post('rte');
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');

            $resultlist         = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/assign', $data);
        $this->load->view('layout/footer', $data);
    }
}
