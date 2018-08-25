<?php /*a:5:{s:61:"/website/talk/App/HttpController/Views/Admin/index/index.html";i:1531102222;s:63:"/website/talk/App/HttpController/Views/Admin/public/header.html";i:1531102388;s:61:"/website/talk/App/HttpController/Views/Admin/public/menu.html";i:1532144743;s:60:"/website/talk/App/HttpController/Views/Admin/public/nav.html";i:1531101874;s:63:"/website/talk/App/HttpController/Views/Admin/public/footer.html";i:1531101896;}*/ ?>
<!-- 引入头文件 -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- login -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <!-- endlogin -->
    <meta name="renderer" content="webkit">

    <title> <?php echo htmlentities($title); ?></title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> 
    <link href="/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/admin/css/animate.css" rel="stylesheet">
    <link href="/admin/css/style.css?v=4.1.0" rel="stylesheet">
    
    <script src="/admin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/admin/js/bootstrap.min.js?v=3.3.6"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>
    <script>
        var SCOPE = {
            'add_url':'add',
            'edit_url':'edit',
            'del_url':'del',
            'image_url':'Api/Tool/upload',
            'status_url':'status',
            'login_url':'/Login/index',
            'index_url':'/Index/index"',
            'register_url':'/Register/index',
        };
    </script>



<!-- 可以扩展自定义引入文件 -->
<link rel="shortcut icon" href="favicon.ico">
<link href="css/plugins/iCheck/custom.css" rel="stylesheet">
</head>
<!-- 引入菜单 -->
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <i class="glyphicon glyphicon-leaf"></i>
                                        <strong class="font-bold"> WEBSOCKET</strong>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="logo-element">WEBSOCKET
                        </div>
                    </li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">控制台</span>
                    </li>

                    <li>
                        <a class="J_menuItem" href="admin/index/main">
                            <i class="fa fa-home"></i>
                            <span class="nav-label">主页</span>
                        </a>
                    </li>

<!-- 菜单栏 -->
<!-- 一级菜单 -->
                    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li class="line dk"></li>
                            <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                                <span class="ng-scope"><?php echo htmlentities($vo['title']); ?></span>
                            </li>
<!-- 二级菜单 -->
                                <?php if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection || $vo['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$slist): $mod = ($i % 2 );++$i;?>
                                    <li>
                                        <a class="J_menuItem" <?php if(empty($slist['list']) || (($slist['list'] instanceof \think\Collection || $slist['list'] instanceof \think\Paginator ) && $slist['list']->isEmpty())): ?>href="<?php echo htmlentities($slist['href']); ?>"<?php endif; ?>>
                                         <!-- <span class="<?php echo htmlentities($slist['icon']); ?>"></span> -->
                                         <i class="<?php echo htmlentities($slist['icon']); ?>"></i>
                                         <span class="nav-label"><?php echo htmlentities($slist['title']); ?></span>
                                         <?php if(!(empty($slist['list']) || (($slist['list'] instanceof \think\Collection || $slist['list'] instanceof \think\Paginator ) && $slist['list']->isEmpty()))): ?>
                                          <span class="fa arrow"></span>
                                         <?php endif; ?>
                                        </a>
                                        <?php if(!(empty($slist['list']) || (($slist['list'] instanceof \think\Collection || $slist['list'] instanceof \think\Paginator ) && $slist['list']->isEmpty()))): ?>
<!-- 三级菜单 -->
                                        <ul class="nav nav-second-level">
                                            <?php if(is_array($slist['list']) || $slist['list'] instanceof \think\Collection || $slist['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $slist['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tlist): $mod = ($i % 2 );++$i;?>
                                            <li>
                                                <a class="J_menuItem" href="<?php echo htmlentities($tlist['href']); ?>"><?php echo htmlentities($tlist['name']); ?></a>
                                            </li>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>



                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
<!-- 引入导航 -->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" method="post" action="Index/index">
                            <div class="form-group">
                                <input type="text" name="keyword" placeholder="请输入您需要查找的内容 …" class="form-control" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li class="m-t-xs">
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="/admin/img/a7.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46小时前</small>
                                            <strong>小四</strong> 是不是只有我死了,你们才不骂爵迹
                                            <br>
                                            <small class="text-muted">3天前 2014.11.8</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="/admin/img/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">25小时前</small>
                                            <strong>二愣子</strong> 呵呵
                                            <br>
                                            <small class="text-muted">昨天</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a class="J_menuItem" href="mailbox.html">
                                            <i class="fa fa-envelope"></i> <strong> 查看所有消息</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息
                                            <span class="pull-right text-muted small">4分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="/logOut">
                                        <div>
                                            <i class="fa fa-qq fa-fw"></i> 点击退出后台
                                            <span class="pull-right text-muted small">退出</span>
                                        </div>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

        <div class="row J_mainContent" id="content-main">
                <iframe id="J_iframe" width="100%" height="100%" src="/admin/index/main" frameborder="0" data-id="/admin/index/main" seamless>
                </iframe>
        </div>
 
    </div>
</div>



<!-- 引入底部js文件 -->

    <!-- 全局js -->
    <script src="/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/admin/js/plugins/layer/layer.min.js"></script>
    <!-- <script type="text/javascript" src="/js/jquery.min.js"></script> -->
    <!-- 自定义js -->
    <script src="/admin/js/hAdmin.js?v=4.1.0"></script>
    <script type="text/javascript" src="/admin/js/index.js"></script>
    <script type="text/javascript" src="/admin/js/common.js"></script>
    <!--&lt;!&ndash; 第三方插件 &ndash;&gt;-->
    <!--<script src="/js/plugins/pace/pace.min.js"></script>-->
    <!--&lt;!&ndash; Flot &ndash;&gt;-->
    <script src="/admin/js/plugins/flot/jquery.flot.js"></script>
    <script src="/admin/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/admin/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/admin/js/plugins/flot/jquery.flot.pie.js"></script>

<div style="text-align:center;">
</div>
</body>

</html>