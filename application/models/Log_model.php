<?php
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Log_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->library("user_agent");
		}

		//헤더 정보 만들기
		public function headerInfo()
		{
			$device = $this->agent->mobile() == null ? "PC" : $this->agent->mobile();
			$browser = $this->agent->browser();
			$version = $this->agent->version();
			$referrer = $this->agent->referrer();
			$platform = $this->agent->platform();
			$ip = $this->input->ip_address();
			$user_agent = $this->input->user_agent();
			$uri = $this->uri->uri_string();

			// $header_string = "device : ".$device. " | browser : ".$browser. " | version : ".$version. " | referrer : ".$referrer. " | platform : ".$platform. " | ip : ".$ip. " | member_idx : ".$member_idx;
			$header_string = "$device|$browser|$version|$platform|$ip|$uri";
			// $header_string = null;

			return $header_string;
		}

		// 로그 남기기
		public function logMessage($data)
		{
			$header_string = $this->headerInfo();
			ob_start();
			print_r($header_string." ------- ");
			print_r($data);
			$data_log = ob_get_clean();
			log_message("error",$data_log);

			return true;
		}

		// var_dump로 로그 남기기
		public function logMessageDump($data)
		{
			$header_string = $this->headerInfo();
			ob_start();
			print_r($header_string." ------- ");
			var_dump($data);
			$data_log = ob_get_clean();
			log_message("error",$data_log);

			return true;
		}

		// 쿼리 남기기
		public function logQuery($data)
		{
			$header_string = $this->headerInfo();
			ob_start();
			print_r($header_string." ------- ");
			print_r($data);
			$data_log = ob_get_clean();
			$data_log = str_replace("`","",str_replace("\n"," ",$data_log));
			log_message("error",$data_log);

			return true;
		}

		// 가장 마지막 쿼리 로그
		public function logLastQuery()
		{
			$last_query = $this->db->last_query();
			$this->logQuery($last_query);

			return true;
		}

	}
?>