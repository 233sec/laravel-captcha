# invisible captcha
一个很弱的现代化人机识别服务


## 截图

![截图1][1]


## 安装要求

 - PHP 7.0+
 - MySQL 5.6.2+
 - Redis ?


## 安装步骤
```shell
composer install
cp .env.example .env
(n)vim .env
#修改数据库配置

php artisan key:generate
php artisan migrate
php artisan db:seed

php artisan serve #启动本地 server

# 浏览器打开 `http://127.0.0.1:8000/my/app` 用 admin@admin.com | 1234 登录 创建一个验证码应用
# `http://127.0.0.1:8000/my/app` 获取刚刚创建的验证码应用的key和secret
# 填入.env
# XCAPTCHA_KEY=
# XCAPTCHA_SECRET=
# 访问 `http://127.0.0.1:8000/recaptcha/api2/demo` 体验
```

## 授权

 - 暂未开源


  [1]: /resources/docs/snapshot_1.png
