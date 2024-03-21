# Dockerfile
FROM php:8.1-fpm

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# PHPの拡張機能をインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www

# Laravelプロジェクトのソースコードをコピー (この行はオプションで、ソースコードの永続化をdocker-compose.ymlで行うため不要になる場合があります)
# COPY . /var/www

# ポートの公開
EXPOSE 9000

# PHP-FPMの実行
CMD ["php-fpm"]
