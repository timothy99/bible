<?php
	defined("BASEPATH") OR exit("No direct script access allowed");

	/**
	 * @author 배진모
	 * @see 파일을 처리하기 위한 기본 모델
	 */
	class File_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		/**
		 * @author 배진모
		 * @see 현재디렉토리 기준 하위 디렉토리 목록 가져오기
		 * @param $base_directory - 기준이 되는 디렉토리
		 * @param $search_mode - 무엇을 찾을지 기능, directory - 디렉토리만 검색, file - 파일만 검색, both - 디렉토리와 파일 검색
		 * @return array $proc_result - 각각의 배열에 디렉토리와 파일 목록을 생성하여 반환
		 */
		public function getDirectoryList($base_directory, $search_mode)
		{
			$is_directory = is_dir($base_directory);
			if($is_directory == true) {
				$result = $is_directory;
				$message = "디렉토리가 맞습니다";
			} else {
				$result = $is_directory;
				$message = "디렉토리가 아닙니다";
			}

			$directory_list = array();
			$file_list = array();
			if($result == true) { // 디렉토리이면 해당 디렉토리의 서브디렉토리 리스트를 가지고 온다
				$handle = opendir($base_directory);
				while ($filename = readdir($handle)) {
					if($filename == "." || $filename == "..") continue; // .과 ..인 시스템 폴더인 경우 제외
					$filepath = $base_directory."/".$filename;
					if(is_dir($filepath)){ // 디렉토리인 경우에만
						array_push($directory_list, $filename); // 파일이름만 선택하여 배열에 넣는다.
					}
					if(is_file($filepath)){ // 파일인 경우에만
						array_push($file_list, $filename); // 파일이름만 선택하여 배열에 넣는다.
					}
				}
				closedir($handle);
			}

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;

			if($search_mode == "both" || $search_mode == "directory") { // 디렉토리를 찾도록 했을때
				$proc_result["directory_list"] = $directory_list;
			}

			if($search_mode == "both" || $search_mode == "file") { // 파일을 찾도록 했을때
				$proc_result["file_list"] = $file_list;
			}

			return $proc_result;
		}

	}
?>