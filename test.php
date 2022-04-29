<?php
ini_set('memory_limit', '1M'); // 内存限制1M

/**
 * 生成随机数填充文件
 * Author: ClassmateLin
 * Email: classmatelin.site@gmail.com
 * Site: https://www.classmatelin.top
 * @param string $filename 输出文件名
 * @param int $batch 按多少批次生成数据
 * @param int $batchSize 每批数据的大小
 */
function generate(string $filename, int $batch=1000, int $batchSize=10000)
{
    for ($i=0; $i<$batch; $i++) {
        $str = '';
        for ($j=0; $j<$batchSize; $j++) {
            $str .= rand($batch, $batchSize) . PHP_EOL; // 生成随机数
        }
        file_put_contents($filename, $str, FILE_APPEND);  // 追加模式写入文件
    }
}




/**
 * 用hash取模方式将文件分散到n个文件中
 * Author: ClassmateLin
 * Email: classmatelin.site@gmail.com
 * Site: https://www.classmatelin.top
 * @param string $filename 输入文件名
 * @param int $mod 按mod取模
 * @param string $dir 文件输出目录
 */
function spiltFile(string $filename, int $mod=20, string $dir='files')
{
    if (!is_dir($dir)){
        mkdir($dir);
    }

    $fp = fopen($filename, 'r');

    while (!feof($fp)){
        $line = fgets($fp);
        $n = crc32(hash('md5', $line)) % $mod; // hash取模
        $filepath = $dir . '/' . $n . '.txt';  // 文件输出路径
        file_put_contents($filepath, $line, FILE_APPEND); // 追加模式写入文件
    }

    fclose($fp);
}




/**
 * 查找一个文件中相同的记录输出到指定文件中
 * Author: ClassmateLin
 * Email: classmatelin.site@gmail.com
 * Site: https://www.classmatelin.top
 * @param string $inputFilename 输入文件路径
 * @param string $outputFilename 输出文件路径
 */
function search(string $inputFilename, $outputFilename='output.txt')
{
    $table = [];
    $fp = fopen($inputFilename, 'r');

    while (!feof($fp))
    {
        $line = fgets($fp);
        !isset($table[$line]) ? $table[$line] = 1 : $table[$line]++; // 未设置的值设1，否则自增
    }

    fclose($fp);

    foreach ($table as $line => $count)
    {
        if ($count >= 2){ // 出现大于2次的则是相同的记录，输出到指定文件中
            file_put_contents($outputFilename, $line, FILE_APPEND);
        }
    }
}

/**
 * 从给定目录下文件中分别找出相同记录输出到指定文件中
 * Author: ClassmateLin
 * Email: classmatelin.site@gmail.com
 * Site: https://www.classmatelin.top
 * @param string $dirs 指定目录
 * @param string $outputFilename 输出文件路径
 */
function searchAll($dirs='files', $outputFilename='output.txt')
{
    $files = scandir($dirs);

    foreach ($files as $file)
    {
        $filepath = $dirs . '/' . $file;
        if (is_file($filepath)){
            search($filepath, $outputFilename);
        }
    }
}

// // 生成文件
// generate('a.txt', 10);
// generate('b.txt', 10);

// 分割文件
// spiltFile('a.txt');
// spiltFile('b.txt');

// // 查找记录
searchAll('files', 'output.txt');