<?php
/*
 +----------------------------------------------------------------------
 + Title        : 迷你分词插件
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-08-20 12:32:00
 + Last-time    : 2017-08-21 15:32:00 + 小黄牛
 + Desc         : 
 +----------------------------------------------------------------------
*/

class Minppl{
	private $path_file = 'minppl/lexicon/'; // 词库存放目录
	private $key;             				// 需要被分词的目标字符串
	private $lexicon;         				// 需要用到的多个分词库，按key从小到大查询
	private $sort;            				// 分词结果字数排序，true|false，长|短
	private $num;             				// 匹配到的分词最大返回条数
	private $mode;            				// 分词库匹配不到关键词的情况下，是否启动解词算法
	private $words;           				// 启动解词算法下的关键词长度
	private $debug = false;   				// 是否开启调试模式
	
	/********************* 开启调试模式下的日志变量 ***********************/
	private $start_time;      				// 起始时间
	private $end_time;        				// 结束时间
	private $cpu;             				// 内存消耗
	private $log_data = [];   				// 普通日志
	
	/**
	 * 开启调试模式
	 */
	public function De_bug() {$this->debug = true;}
	
	/**
	 * 开启调试模式下-开始记录运行时间
	 */
	private function Start_time() {$this->start_time = microtime(true);}
	
	/**
	 * 开启调试模式下-终止记录运行时间
	 */
	private function End_time() {$this->end_time = microtime(true); $this->cpu = memory_get_usage();}
	
	/**
	 * 开启调试模式下-输出消耗记录
	 */
	public function Log_echo(){
		if (!$this->debug) {
			return false;
		}
		$this->End_time();
		echo '运行时间: '.($this->end_time - $this->start_time).'秒<br/>';
		echo '内存消耗: '.($this->cpu / 1024).'KB<br/>';
		foreach ($this->log_data as $v) {
			echo $v.'<br/>';
		}
	}
	
	/**
	 * 初始化参数
	 * @param string $key     需要被分词的目标字符串
	 * @param array  $lexicon 需要用到的分词库，一维数组
	 * @param bool   $sort    分词结果字数排序方式
	 * @param int    $num     匹配到的分词最大返回条数
	 * @param bool   $mode    分词库匹配不到关键词的情况下，是否启动解词算法
	 * @param int    $words   启动解词算法下的关键词长度
	 * @return array|bool     分词结果或false
	 */
	public function __Initialize($key, $lexicon, $sort = false, $num = 5, $mode = false, $words = 2){
		$this->key     = $key;
		$this->lexicon = $lexicon;
		$this->sort    = $sort;
		$this->num     = $num;
		$this->mode    = $mode;
		$this->words   = $words;
		# 1、调试模式-记录运行信息
		$this->Start_time();
		# 2、检测词库文件是否存在
		return $this->Vif_file();
	}
	
	/**
	 * 检测词库文件是否存在
	 */
	private function Vif_file(){
		$data = [];
		foreach ($this->lexicon as $v) {
			if (!is_file($this->path_file . $v)) {
				$this->log_data[] = $v.'：不存在';
			}else{
				if (is_readable($this->path_file . $v) == false) {
					$this->log_data[] = $v.'：不可读取';
				}else{
					$data[] = $v;
				}
			}
		}
		$this->lexicon = $data;
		if(count($this->lexicon) == 0){
			return false;
		}
		# 3、开始读取词库进行分词
		return $this->PPL();
	}
	
	/**
	 * 打开词库进行中文检测
	 */
	 private function PPL(){
		 $key = [];
		 foreach ($this->lexicon as $v) {
			$contents = file_get_contents($this->path_file . $v);
			$array    = explode('|', $contents);

			# 分词查询
			foreach ($array as $k) {
				if (strpos($this->key, $k) !== false) {
					$key[] = $k;
				}
			}
		}
		
		$count = count($key);
		if ($count == 0) {
			if ($this->mode) {
				# 4、词库检索不行，就启动分词算法
				return $this->Analysis();
			}
			return false;
		}
		
		# 排序
		if ($this->sort) {
			sort($key);
		}else{
			rsort($key);
		}
		
		# 按长度返回
		if ($count <= $this->num) {
			return $key;
		}else{
			$data = [];
			for($i = 0; $i < $this->num; $i++){
				$data[] = $key[$i];
			}
			return $data;
		}
	 }
	 
	 /**
	  * 词库检索失败之后，使用解词算法
	  */
	 private function Analysis(){
		$length = mb_strlen($this->key, 'utf-8');
		for ($i = 0; $i < $length; $i++) {
			if ($i != 0){
				$i = $i+($this->words-1);
			}
			$key = mb_substr($this->key, $i, $this->words, 'utf-8');
			if (!empty($key)) {
				$data[] = $key;
			}
		}
		return $data;
	 }
} 