# 業務計測ツール

## 前提条件

- DockerおよびDocker Composeがインストールされていることを確認してください。
- この手順を開始する前に、プロジェクトディレクトリにある`docker-compose.yml`ファイルがこのREADMEと同じ場所にあることを確認してください。

### データベースについて

**端末のmysqlを起動して以下の構成のデータベースをあらかじめ作成してください**。

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timecard
DB_USERNAME=root
DB_PASSWORD=
```

## 使用されるサービス

- **app**: Laravelベースのバックエンドアプリケーション
- **nginx**: アプリケーションのWebサーバー
- **vuejs**: Vue.jsベースのフロントエンドアプリケーション

## スタートガイド

1. プロジェクトディレクトリに移動します。

2. 以下のコマンドを実行して、サービスをビルドし、コンテナを起動します。

    ```bash
    docker-compose up -d --build
    ```

    `-d`オプションは、コンテナをバックグラウンドで実行します。

3. コンテナが正常に起動したことを確認します。

    ```bash
    docker-compose ps
    ```

    すべてのコンテナの状態が`Up`であることを確認してください。

4. アプリケーションへのアクセス：

    - Laravelアプリケーションは`http://localhost:8080`でアクセスできます。
    - Vue.jsフロントエンドは`http://localhost:8081`でアクセスできます。

## コンテナの停止と削除

コンテナを停止し、削除するには、以下のコマンドを実行してください。

```bash
docker-compose down
```
