# Podman + WSLでLAMP開発環境を立ち上げる手順書

---

## 前準備：WSLのインストールとUbuntuのセットアップ

### 1. WSLのインストール（未インストールの場合）
Windowsの **PowerShell（管理者権限）** を開き、以下を実行：
```powershell
wsl --install
```
- WSL2とUbuntuが同時にインストールされます（Windows 10/11の新しいバージョンの場合）。
- 再起動が必要になることがあります。

---

### 2. Ubuntuのインストール（WSLはあるがUbuntu未導入の場合）
1. **Microsoft Store** を開く
2. 検索欄に「Ubuntu」と入力
3. 「Ubuntu 22.04 LTS」などを選んでインストール

---

### 3. Ubuntuの初期設定
初めてUbuntuを起動すると、以下の設定が求められます：
1. **ユーザー名の入力**（英数字、小文字推奨）
2. **パスワードの設定**（sudoなど管理者権限操作に使用）

---

### 4. Ubuntuの動作確認
PowerShellで以下を実行：
```powershell
wsl -l -v
```
例：
```
  NAME      STATE    VERSION
* Ubuntu    Stopped  2
```
- **NAME** に `Ubuntu` があればインストール済み
- **VERSION** が `2` ならWSL2で動作
- `Stopped` は未起動状態ですが、起動すれば利用可能

Ubuntuを起動：
```powershell
wsl -d Ubuntu
```
Ubuntuターミナルが表示されればOKです。

---

## LAMP環境構築手順

### phpMyAdminサービス設定（docker-compose.yml）
```yaml
phpmyadmin:
  image: docker.io/phpmyadmin/phpmyadmin
  container_name: phpmyadmin
  ports:
    - "8081:80"
  environment:
    PMA_HOST: db
    PMA_USER: root
    PMA_PASSWORD: rootpass
  depends_on:
    - db
```

---

## 3. MySQL Dockerfileを修正（short-name対策）
Podmanは短い名前（`mysql:8.0`）だけでは取得先レジストリを判断できません。  
明示的に`docker.io/`を付けることで、Docker Hubから取得するようになります。

`./docker/mysql/Dockerfile`：
```Dockerfile
FROM docker.io/mysql:8.0
```

---

## 4. プロジェクトをWSLネイティブの場所に置く
Windows側のドライブをマウントして使用する方法だと、Linuxの所有権変更（`chown`）ができません。  
MySQLは起動時にデータディレクトリの所有権を変更するため、WSLの`/home/...`内に置く必要があります。

WSL内でホームディレクトリに移動：
```bash
cd ~
```
Windowsからプロジェクトをコピー：
```bash
cp -r /mnt/c/Users/<Windowsユーザー名>/Desktop/JTC/sample-LAMP_project ~/sample-LAMP_project
```
プロジェクトディレクトリに移動：
```bash
cd ~/sample-LAMP_project
```

---

## 5. データディレクトリの権限を確認
MySQLコンテナは起動時に `/var/lib/mysql` の所有者を変更しようとします。  
マウント元ディレクトリの所有者がPodman実行ユーザーでないと権限エラーになります。

所有者確認：
```bash
ls -ld ./docker/mysql/data
```
所有者が自分でない場合は変更：
```bash
sudo chown -R $(whoami):$(whoami) ./docker/mysql/data
```

---

## 6. ビルド＆起動
```bash
podman-compose up -d
```

---

## 7. 起動確認
```bash
podman ps
```
下記3つのコンテナが起動していればOK：
- `mysql-db`
- `sample-lamp_project_app_1`
- `phpmyadmin`
```
