# 選考課題

wikipedia

https://ja.wikipedia.org/wiki/%E3%82%BD%E3%83%BC%E3%83%88

今回はマージソートとクイックソートを選択しました。
それぞれのソートの良い点は以下ファイルに記載をしております。

`sort-algorithms/docs/report.md`

## 実行手順

local環境でPHP8.4が動作する場合は以下のコマンドを実行してください。

```
# マージソート
php src/mergeSort.php

# クイックソート
php src/quickSort.php
```

local環境で実行ができない場合はDockerの使用をお願いいたします。

## 実行方法

```bash
# イメージのビルド
docker compose build

# コンテナ起動
docker compose up -d

# 実行
## マージソート
docker compose exec app php src/mergeSort.php

## クイックソート
docker compose exec app php src/quickSort.php

# 終了
docker compose down
```

