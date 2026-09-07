<?php
declare(strict_types = 1);

/**
 * クイックソート
 *
 * @param  list<int> $list ソート対象の数値配列
 * @return list<int>       ソート後の数値配列
 */
function quickSort(array $list): array
{
    divide($list, 0, count($list) - 1);
    return $list;
}

/**
 * ピボットを基準に配列を再起的に分割
 *
 * @param list<int> $list ソート対象の数値配列
 * @param int       $from 走査開始位置
 * @param int       $to   走査終了位置
 */
function divide(array &$list, int $from, int $to): void
{
    // 要素が1つ以下なら再帰終了
    if ($from >= $to) {
        return;
    }

    // 要素が2つの場合は1回
    if (($to - $from) === 1) {
        if ($list[$from] > $list[$to]) {
            [$list[$from], $list[$to]] = [$list[$to], $list[$from]];
        }
        return;
    }

    $pivot = $list[getMedianKey($list, $from, $to)];

    $left = $from;
    $right = $to;
    while (true) {
        // 左側にあるピボット以上の値を特定
        while ($list[$left] < $pivot) {
            $left++;
        }
        // 右側にあるピボット未満の値を特定
        while ($pivot < $list[$right]) {
            $right--;
        }
        if ($right <= $left) {
            break;
        }
        [$list[$left], $list[$right]] = [$list[$right], $list[$left]];
        $left++;
        $right--;
    }

    divide($list, $from, $left - 1);
    divide($list, $right + 1, $to);
}

/**
 * 数値配列のうち3つの値による中央値を取得
 *
 * @param  list<int> $list 数値配列
 * @param  int       $from 走査開始位置
 * @param  int       $to   走査終了位置
 * @return int       中央値の値を持つ位置
 */
function getMedianKey(array $list, int $from, int $to): int
{
    // 走査開始位置、中央、走査終了位置の3つから中央値を取得
    $keyMiddle = $from + intdiv($to - $from, 2);
    $valueMiddle = max(min($list[$from], $list[$keyMiddle]), min(max($list[$from], $list[$keyMiddle]), $list[$to]));

    if ($valueMiddle === $list[$from]) {
        return $from;
    } elseif ($valueMiddle === $list[$keyMiddle]) {
        return $keyMiddle;
    } else {
        return $to;
    }
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
        $result = quickSort($list);
        $endTime = hrtime(true);
        $endMemory = memory_get_peak_usage();

        $executionTime = ($endTime - $startTime) / 1e6;
        $time[] = $executionTime;
        $usedMemory = $endMemory - $startMemory;

        $i++;
    }

    $avgTime = array_sum($time) / count($time);

    echo "--- クイックソート実行結果【" . $label ."】 ---\n";
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