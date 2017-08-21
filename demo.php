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

require 'minppl/Minppl.class.php';

# 实例化分词类
$obj  = new Minppl();
/**
 * 调用分词
 * @param string $key     需要被分词的目标字符串
 * @param array  $lexicon 需要用到的分词库，一维数组
 * @param bool   $sort    分词结果字数排序，true|false，长|短，默认为false
 * @param int    $num     匹配到的分词最大返回条数，默认5
 * @param bool   $mode    分词库匹配不到关键词的情况下，是否启动解词算法，默认true
 * @param int    $words   启动解词算法下的关键词长度，默认2
 * @return array|bool     分词结果或false
*/
$data = $obj->__Initialize('阿杜最爱快乐大本营：快乐家族', [
	'1-mingxing.txt',
	'2-mingxing.txt',
], false, 5, true, 2);
echo '<pre>';
var_dump($data);

# 开启调试模式
$obj->De_bug();
# 打印调试内容-错误信息与运行时间，内存消耗
$obj->Log_echo();