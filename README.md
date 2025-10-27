# PHP + MySQL + phpMyAdmin 開発環境 (Podman対応)

この構成は、Podman 上で **PHP (Apache)** + **MySQL** + **phpMyAdmin** のローカル開発環境を構築します。  
HTML / CSS / JavaScript / PHP のファイルは `src` ディレクトリに配置してください。  
MySQL のデータは `docker¥mysql¥data` に永続化されます。

---

## ディレクトリ構成

```
project/
├─ docker-compose.yml      # コンテナ構成ファイル
├─ src/                    # Webサイトのソースコード
│   └─ index.php           # PHP + MySQL接続テスト用サンプル
└─ docker
    ├─ php
    │   └─  Dockerfile
    └─ mysql
        ├─ Dockerfile
        └─ data/            # MySQLデータ永続化用
```

※ 各ディレクトリは存在しない場合、起動時に自動作成されます。

---

## 事前準備（macOS）

1. **Homebrewの確認**
   ```bash
   brew -v
   ```

2. **Homebrew がない場合**  
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

3. **Podman のインストール**  
   ```bash
   brew install podman
   ```

4. **Podman VM 初期化（初回のみ）**  
   ```bash
   podman machine init
   ```

5. **podman-compose のインストール**  
   ```bash
   brew install podman-compose
   ```

## 事前準備（Windows）

Windows 環境ではチーム方針に従い Podman を推奨します（Docker は許可されていない想定）。Podman Desktop（GUI）または WSL2 上の Podman を使ってください。

1. WSL2 の確認（推奨）

    - WSL2 を利用することを推奨します（ファイル共有とパフォーマンスの観点から）。WSL がインストール済みかを確認:

       ```powershell
       wsl -l -v
       ```

    - WSL2 が無い場合は Microsoft のドキュメントに従って有効化してください。

2. Podman Desktop の利用（簡単）

    - Windows 用の Podman Desktop をインストールすると GUI で管理できて簡単です: https://podman-desktop.io/

3. WSL2（Ubuntu など）へ Podman をインストールする方法

    - WSL のディストリ内で次を実行します（Ubuntu の例）:

       ```bash
       sudo apt update
       sudo apt install -y podman podman-compose
       podman --version
       podman-compose --version
       ```

4. Podman Machine の初期化（必要な場合）

    - 初回のみ Podman Machine を初期化します（Podman Desktop を使う場合は不要なことが多いです）:

       ```bash
       podman machine init
       podman machine start
       ```

5. リポジトリを起動する

    - WSL ターミナル（または Podman Desktop の統合ターミナル）で:

       ```bash
       podman-compose up -d
       ```

    - 起動確認や接続に問題がある場合は次を試してください:

       ```bash
       podman ps
       podman machine inspect
       ```
---
## 起動方法

1. Podman VM を起動
   ```bash
   podman machine start
   ```

2. コンテナ群を起動
   ```bash
   podman-compose up -d
   ```

3. アクセス
   - テストページ: [http://localhost:8080/test.php](http://localhost:8080/test.php)
   - Webサイト: [http://localhost:8080](http://localhost:8080)
   - phpMyAdmin: [http://localhost:8081](http://localhost:8081)  
     ユーザー名: `root`  
     パスワード: `rootpass`  
     （または `myuser` / `mypass`）

---

## 終了方法

1. コンテナ群を停止・削除
   ```bash
   podman-compose down
   ```

2. Podman VM を停止（必要に応じて）
   ```bash
   podman machine stop
   ```

---

## 補足

- PHPからMySQLへ接続する際のホスト名は `db` を使用します（docker-compose のサービス名）。
- MySQLのデータは `db_data` に保存され、コンテナを削除しても残ります。
- ポート番号は必要に応じて `docker-compose.yml` 内で変更可能です。

---

## ライセンス
この構成は自由に改変・利用可能です。
```