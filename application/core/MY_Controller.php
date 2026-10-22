<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    protected function check_auth() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Sesi Anda telah berakhir, silakan login kembali.');
            redirect('auth/login');
            exit;
        }
    }

    protected function allow_roles(array $allowed_roles) {
        $this->check_auth();
        $current_role = $this->session->userdata('role');
        if (!in_array($current_role, $allowed_roles)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            $this->redirect_by_role($current_role);
            exit;
        }
    }

    protected function redirect_by_role($role) {
        switch ($role) {
            case 'HR_ADMIN':
                redirect('dashboard/hr');
                break;
            case 'SUPERVISOR':
                redirect('dashboard/supervisor');
                break;
            case 'OPERATOR':
                redirect('dashboard/operator');
                break;
            default:
                redirect('auth/login');
                break;
        }
    }
}
