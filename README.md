PHP5.4 实现迷你分词插件
===============================================
小黄牛
-----------------------------------------------

### 1731223728@qq.com 

+ 作者 - 小黄牛

+ 邮箱 - 1731223728@qq.com     


## 环境要求

+ 只测试了Apache2.4、PHP5.4

+ 读取词库依赖函数：file_get_contents();建议每一个词库大小不要超过500KB，这样效率性能可以达到最大化。


### 分词插件详细说明

+ 1、本分词插件主要依赖与词库词典检索，可进行多个词典的配置，词库文件主要存放在【minppl/lexicon/】文件夹下，用【.txt】文本存放，每一个词之间用【|】符合分割，并且要求【无bom】文件头。

+ 2、插件在检索不到任何关键词时，可进行按位截取

+ 3、使用Demo如下：

``` 
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
```