<?php
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Query_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model("log_model");
		}

		/**
		 * @author 배진모
		 * @see 목록 리턴의 기본적인 쿼리
		 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
		 * @return array $result_object
		 */
		public function dbList($data)
		{
			// 유동적인 값에 대한 null 선언 먼저
			$db_where = null;
			$db_like = null;
			$db_limit = 0;
			$db_offset = 0;
			$db_order = null;

			// 유동변수 작업
			foreach($data as $no => $val) {
				${$no} = $val;
			}

			foreach($db_select as $no => $val) {
				$this->db->select($val[0], $val[1]);
			}

			if($db_where != null) {
				foreach($db_where as $no => $val) {
					$this->db->where($val[0], $val[1], $val[2]);
				}
			}

			if($db_like != null) {
				foreach($db_like as $no => $val) {
					$this->db->like($val[0], $val[1], $val[2]);
				}
			}

			$this->db->order_by($db_order);

			$db_get_result = $this->db->get($db_table, $db_limit, $db_offset);
			$result_object = $db_get_result->result_object();
			// $this->log_model->logLastQuery(); // 현재 쿼리 로그 남기기

			// 현재 쿼리의 limit 와 offset이 없을 경우 result_id 의 num_rows를 반환하고
			// 그렇지 않은경우 count(*)을 해서 전체 반환될 쿼리의 숫자를 구한다
			if($db_limit == 0 && $db_offset == 0) {
				$cnt = $db_get_result->result_id->num_rows;
			} else {
				$this->db->flush_cache();
				$this->db->select("count(*) as cnt", TRUE);
				if($db_where != null) {
					foreach($db_where as $no => $val) {
						$this->db->where($val[0], $val[1], $val[2]);
					}
				}

				if($db_like != null) {
					foreach($db_like as $no => $val) {
						$this->db->like($val[0], $val[1], $val[2]);
					}
				}
				$db_get_result = $this->db->get($db_table);
				$count_result = $db_get_result->result_object();
				$cnt = $count_result[0]->cnt;
			}

			$proc_result = array();
			$proc_result["result"] = true;
			$proc_result["messsage"] = "쿼리가 실행되었습니다";
			$proc_result["cnt"] = $cnt;
			$proc_result["db_list"] = $result_object;

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 한줄 반환의 기본적인 쿼리
		 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
		 * @return array $result_object
		 */
		public function dbView($data)
		{
			$data["db_limit"] = 1; // 1건만 불러올 것이므로
			$data["db_offset"] = 0; // 1건만 불러올 것이므로 오프셋을 0으로 고정
			$db_result = $this->dbList($data);
			$db_view = $db_result["db_list"][0];
			$cnt = $db_result["cnt"];

			$proc_result = array();
			$proc_result["result"] = true;
			$proc_result["messsage"] = "쿼리가 실행되었습니다";
			$proc_result["cnt"] = $cnt;
			$proc_result["db_view"] = $db_view;
			// $this->log_model->logLastQuery(); // 현재 쿼리 로그 남기기
 
			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 쿼리 전체 문장으로 실행하여 목록으로 반환
		 * @param char $sql - 실행해야할 쿼리
		 * @return array $query_result
		 */
		public function dbListQuery($sql)
		{
			$db_result = $this->db->query($sql);
			$query_result = $db_result->result_object();

			return $query_result;
		}

		/**
		 * @author 배진모
		 * @see 쿼리 전체 문장으로 실행하여 한줄 반환
		 * @param char $sql - 실행해야할 쿼리
		 * @return array $query_result
		 */
		public function dbViewQuery($sql)
		{
			$query_result = $this->dbListQuery($sql);
			$query_result = $query_result[0];
			// $this->log_model->logMessage($query_result); // 현재 쿼리 로그 남기기

			return $query_result;
		}

		/**
		 * @author 배진모
		 * @see 데이터 입력(insert)
		 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
		 * @return array $proc_result - DB입력 결과과 insert_id만 반환
		 */
		public function dbInsert($data)
		{
			$db_table = $data["db_table"];
			$db_column = $data["db_column"];
			foreach($db_column as $no => $val) {
				$this->db->set($val[0], $val[1], $val[2]);
			}
			$db_result = $this->db->insert($db_table);
			$insert_id = $this->db->insert_id();
			$this->log_model->logLastQuery(); // 현재 쿼리 로그 남기기

			$proc_result = array();
			$proc_result["db_result"] = $db_result;
			$proc_result["insert_id"] = $insert_id;

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 데이터 수정(update)
		 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
		 * @return array $proc_result - DB입력 결과과 영향받은 줄 수(affected_rows) 반환
		 */
		public function dbUpdate($data)
		{
			$db_table = $data["db_table"];
			$db_column = $data["db_column"];
			$db_where = $data["db_where"];
			foreach($db_column as $no => $val) {
				$this->db->set($val[0], $val[1], $val[2]);
			}
			foreach($db_where as $no => $val) {
				$this->db->where($val[0], $val[1], $val[2]);
			}
			$db_result = $this->db->update($db_table);
			$affected_rows = $this->db->affected_rows();

			$this->log_model->logLastQuery(); // 현재 쿼리 로그 남기기

			$proc_result = array();
			$proc_result["result"] = $db_result;
			$proc_result["affected_rows"] = $affected_rows;

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 데이터 삭제(delete), 실제 데이터의 삭제 이므로 사용에 주의한다
		 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
		 * @return array $db_result - DB입력 결과
		 */
		public function dbDelete($data)
		{
			$db_table = $data["db_table"];
			$db_where = $data["db_where"];
			foreach($db_where as $no => $val) {
				$this->db->where($val[0], $val[1], $val[2]);
			}
			$db_result = $this->db->delete($db_table);
			$this->log_model->logLastQuery(); // 현재 쿼리 로그 남기기

			return $db_result;
		}

	}
?>