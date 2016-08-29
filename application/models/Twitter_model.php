<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Twitter_model extends CI_Model {

	private $table = 'berita_3';

	public function __construct(){
		parent::__construct();
	}

	public function insert($datas) {
		$this->db->insert($this->table, $datas);
	}
	
	public function checked($user_id, $date) {
		$this->db->where('user_id', $user_id);
		$this->db->where('date_tweet', $date);
		
		$result = $this->db->get($this->table);
		$result = $result->result_object();
		
		return $result;
	}
}