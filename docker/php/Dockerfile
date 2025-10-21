FROM php:8.2-apache

# MySQL接続用のmysqli拡張をインストール
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Apacheのドキュメントルートを /var/www/html に設定（デフォルト）
WORKDIR /var/www/html

EXPOSE 80