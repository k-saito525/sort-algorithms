<?php
declare(strict_types = 1);

/**
 * マージソート
 *
 * @param  list<int> $list ソート前の数値配列
 * @return list<int>       ソート後の数値配列
 */
function mergeSort(array $list): array
{
    $count = count($list);

    // 要素数が1つの場合は再帰を止める
    if ($count < 2) {
        return $list;
    }

    // 分割後の要素数が1以上の整数になるようにする
    $divPoint = intdiv($count, 2);
    $left = array_slice($list, 0, $divPoint);
    $right = array_slice($list, $divPoint);

    // 再帰的に分割
    $left = mergeSort($left);
    $right = mergeSort($right);

    return merge($left, $right);
}

/**
 * マージ処理
 *
 * @param  list<int> $left  分割後の数値配列（左）
 * @param  list<int> $right 分割後の数値配列（右）
 * @return list<int>        マージ後の数値配列
 */
function merge(array $left, array $right): array
{
    $sorted = [];
    $countLeft = count($left);
    $countRight = count($right);
    $iLeft = 0;
    $iRight = 0;

    while (true) {
        if ($left[$iLeft] <= $right[$iRight]) {
            $sorted[] = $left[$iLeft];
            $iLeft++;
            if ($iLeft >= $countLeft) {
                break;
            }
        } else {
            $sorted[] = $right[$iRight];
            $iRight++;
            if ($iRight >= $countRight) {
                break;
            }
        }
    }

    return array_merge($sorted, array_slice($left, $iLeft), array_slice($right, $iRight));
}

/* ------------------------------------------------------------------------------- */

/**
 * ソート実行・計測
 */
function execute(array $list, string $label)
{
    $time = [];
    $result = [];
    $usedMemory = 0;
    // 処理時間は5回実行した結果の平均を取る
    $i = 1;
    while ($i <= 5) {
        memory_reset_peak_usage();
        $startMemory = memory_get_peak_usage();
        $startTime = hrtime(true);
        $result = mergeSort($list);
        $endTime = hrtime(true);
        $endMemory = memory_get_peak_usage();

        $executionTime = ($endTime - $startTime) / 1e6;
        $time[] = $executionTime;
        $usedMemory = $endMemory - $startMemory;

        $i++;
    }

    $avgTime = array_sum($time) / count($time);

    echo "--- マージソート実行結果【" . $label ."】 ---\n";
    echo "5回実行平均処理時間: " . number_format($avgTime / 1000, 8) . " 秒\n";
    echo "メモリ使用量: " . number_format($usedMemory / 1024, 2) . " KB\n";
    $expected = $list;
    sort($expected);
    if ($result === $expected) {
        echo "標準関数(sort())のソート結果との比較：一致\n";
    } else {
        echo "標準関数(sort())のソート結果との比較：不一致\n";
    }
    echo "----------------\n";
}

/* 数値配列を作成 */
$list = range(1, 10000);

// ランダム（重複なし）
$randomList = $list;
shuffle($randomList);
execute($randomList, "ランダム（重複なし）");

// ランダム（重複あり）
$randomDupList = [];
for ($i = 0; $i < 10000; $i++) {
    $randomDupList[] = mt_rand(1, 10000);
}
execute($randomDupList, "ランダム（重複あり）");

// ソート済み（昇順）
$sortedAscList = $list;
sort($sortedAscList);
execute($sortedAscList, "昇順ソート済み");

// ソート済み（降順）
$sortedDescList = $list;
rsort($sortedDescList);
execute($sortedDescList, "降順ソート済み");

// データ量 少
execute([10, 5], "データ量 少");

// データ量 中
$list = range(1, 100);
shuffle($list);
execute($list, "データ量 中");