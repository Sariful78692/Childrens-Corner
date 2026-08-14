<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Migrate_model");
    }

    public function index()
    {
        echo "Migration controller is running.";
    }

    public function expenses()
    {
        //URL: http://localhost/malancha/migrate/expenses
        $results = $this->Migrate_model->migrate_expenses();
        echo "Expenses migration completed.";
        echo "<pre>";
        print_r($results);
        die;
    }

    public function income()
    {
        //URL: http://localhost/malancha/migrate/income
        $results = $this->Migrate_model->migrate_income();
        echo "Income migration completed.";
        echo "<pre>";
        print_r($results);
        die;
    }

    public function fund_transfers()
    {
        //URL: http://localhost/malancha/migrate/fund_transfers
        $results = $this->Migrate_model->migrate_fund_transfers();
        echo "fund_transfers migration completed.";
        echo "<pre>";
        print_r($results);
        die;
    }

    public function staff_payroll()
    {
        //URL: http://localhost/malancha/migrate/staff_payroll
        $results = $this->Migrate_model->migrate_staff_payroll();
        echo "Staff Payroll migration completed.";
        echo "<pre>";
        print_r($results);
        die;
    }
}
