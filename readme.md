###演示

- [Larry666.com](http://larry666.com/)

###注意

- 需要安装`Memcached`扩展
- `PHP`版本 ≥ `5.5.9`

### 安装
1. cd www
2. git clone https://github.com/larry-666/Laravel-5.1-Blog.git
3. composer install
4. 创建一个新的数据库
5. cp .env.example .env
6. 修改`.env`数据库配置
7. php artisan migrate
8. php artisan db:seed
9. sudo chmod -R 777 public/
10. sudo chmod -R 777 storage/

### 运行
```
php artisan serve
```

### 提示
- 后台默认账号:`admin@163.com`,密码:`admin`
- 后台地址`URI`是`/admin`
