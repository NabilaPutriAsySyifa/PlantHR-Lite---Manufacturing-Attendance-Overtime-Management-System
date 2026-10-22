<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->allow_roles(['HR_ADMIN']);
    }

    public function backup_db() {
        $this->load->dbutil();
        $this->load->helper('download');
        $backup = $this->dbutil->backup([
            'format'             => 'gzip',
            'filename'           => 'planthr_db_backup.sql',
            'add_drop'           => TRUE,
            'add_insert'         => TRUE,
            'newline'            => "\n",
            'foreign_key_checks' => FALSE
        ]);
        force_download('backup_planthr_' . date('Ymd_His') . '.sql.gz', $backup);
    }
}
