# 初期設定
## Mac
### 補足
```
Dockerをインストールする前に、以下をインストールしておくこと。
・Xcode
AppleStoreからダウンロード
・Homebrew
https://brew.sh/index_ja.html
```
### 1.Dockerインストール
```
https://www.docker.com/get-started
```
### 2.docker-composeインストール
```
brew install docker-compose
```

### 3.docker起動
```
docker-compose up
```
### 4.環境設定
```
cp .env.dist .env

```
### 5.Dockerfileに記載されたコマンドの実行 ※docker build時に実行できれば良いのだがDB関連の処理なのでそもそも成功するのか疑問
```
1.dockerコンテナに接続
docker exec -it waiwaihiroba_ec-cube_1 /bin/bash
2.テーブル作成
composer run-script installer-scripts
3.Dockerfileに記載されていたコマンド、symfonyに関するコマンドを実行してるようだが詳細は不明
composer run-script auto-scripts
```
