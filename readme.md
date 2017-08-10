Yii2 Application Skeleton
===================================

目录结构
------------
```
├── commands
│   └── HelloController.php
├── common             自定义核心文件
│   ├── Files          单文件
│   └── Libraries      类库
├── composer.json
├── composer.lock
├── config   	       配置文件
│   ├── console.php
│   ├── db.php
│   ├── params.php
│   └── web.php
├── controllers        控制器目录
│   ├── BaseController.php
│   └── SiteController.php
├── models             模型目录
│   └── Posts.php
├── readme.md
├── runtime            runtime目录      
│   ├── logs
│   └── views
├── vendor             composer目录
│   ├── autoload.php
│   ├── bin
│   ├── bower-asset
│   ├── cebe
│   ├── composer
│   ├── ezyang
│   ├── leeoniya
│   ├── symfony
│   ├── twig
│   └── yiisoft
├── views              视图目录
│   └── index.html
└── web                Web根目录
    ├── assets
    ├── favicon.ico
    ├── index.php
    └── robots.txt
```


视图
-------------------

使用Twig