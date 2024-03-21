# Node.jsをベースイメージとして使用
FROM node:lts-alpine as build-stage

# 作業ディレクトリを設定
WORKDIR /app

# パッケージ定義ファイルをコピー
COPY package*.json ./

# npmパッケージをインストール
RUN npm install

# アプリケーションのソースコードをコピー
COPY . .

# Vue.jsアプリケーションのビルド
RUN npm run build

# Nginxをインストールし、ビルドされた静的ファイルを配信
FROM nginx:stable-alpine as production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
