<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function get_user_by_email($email) {
        return $this->db->select('e.id, e.nip, e.full_name, e.email, e.password, e.role, e.department_id, e.shift_id, e.is_active, d.dept_name, d.dept_code, s.shift_name, s.start_time, s.end_time, s.is_cross_day')
            ->from('employees e')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('shifts s', 's.id = e.shift_id', 'left')
            ->where('e.email', $email)
            ->where('e.is_active', 1)
            ->get()
            ->row();
    }
}
