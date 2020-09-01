<?php
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Bible extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			$this->search();
		}

		public function search()
		{
			$this->load->view("search");
		}

	}
?>