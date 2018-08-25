<?php /*a:3:{s:60:"/website/talk/App/HttpController/Views/Admin/role/index.html";i:1532154962;s:63:"/website/talk/App/HttpController/Views/Admin/public/header.html";i:1531102388;s:63:"/website/talk/App/HttpController/Views/Admin/public/footer.html";i:1531101896;}*/ ?>
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




</head>

<body class="gray-bg">

    <div class="wrapper wrapper-content animated fadeInUp">
        <div class="row">
            <div class="col-sm-12">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5><?php echo htmlentities($title); ?></h5>
<!--                         <div class="ibox-tools">
                            <a href="projects.html" class="btn btn-primary btn-xs">创建新项目</a>
                        </div> -->
                    </div>
                    <div class="ibox-content">
                        <form action="index" method="GET">
                           <div class="row">
                                <div class="col-sm-5 m-b-xs">
                                    <select class="input-sm form-control input-s-sm inline" name="status">
                                        <?php if(is_array($status) || $status instanceof \think\Collection || $status instanceof \think\Paginator): $i = 0; $__LIST__ = $status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo htmlentities($key); ?>" <?php if($key == $co): ?> selected="selected"<?php endif; ?>><?php echo htmlentities($vo); ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <button class="btn btn-primary " type="submit">
                                    <i class="fa fa-check"></i>&nbsp;提交
                                </button>                                
                        </form>

                        <div class="project-list">

                            <table class="table table-hover">
                                <thead>
                                    <th>ID</th>
                                    <th>角色名称</th>
                                    <th>所属权限</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </thead>
                                <tbody>
                                    <?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
                                    <tr><td colspan="5" align="center">没有数据</td></tr>
                                    <?php endif; if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td><?php echo htmlentities($vo['id']); ?></td>
                                        <td><?php echo htmlentities($vo['name']); ?></td>
                                        <td><?php echo htmlentities($vo['rules']); ?></td>
                                        <?php if($vo['status'] ==1): ?>
                                        <td class="project-status">
                                            <span class="label label-primary" x-id="<?php echo htmlentities($vo['id']); ?>" x-status="0" onclick="updateStatus(this);" style="cursor: pointer">启用</span>
                                        </td>
                                       <td>

                                            <a href="edit?id=<?php echo htmlentities($vo['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑 </a>
                                           <button class="btn btn-primary " x-id="<?php echo htmlentities($vo['id']); ?>" onclick="delData(this);"  type="button"><i class="fa fa-close"></i>&nbsp;删除
                                           </button>
                                        </td>
                                        <?php else: ?>
                                        <td class="project-status">
                                            <span class="label label-primary">审核中</span>
                                        </td>
                                        <td>

                                            <button  x-id="<?php echo htmlentities($vo['id']); ?>" x-status="1" onclick="updateStatus(this);" class="btn btn-primary " type="button"><i class="fa fa-check">
                                                
                                            </i>&nbsp;通过
                                            </button>
                                            <button class="btn btn-primary " x-id="<?php echo htmlentities($vo['id']); ?>" onclick="delData(this);"  type="button"><i class="fa fa-close"></i>&nbsp;删除
                                            </button>
                                       </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- 全局js -->
    
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


    <!-- 自定义js -->
    <script src="__STATIC__/js/content.js?v=1.0.0"></script>


    <script>
        $(document).ready(function(){

            $('#loading-example-btn').click(function () {
                btn = $(this);
                simpleLoad(btn, true)

                // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

                simpleLoad(btn, false)
            });
        });

        function simpleLoad(btn, state) {
            if (state) 
            {
                btn.children().addClass('fa-spin');
                btn.contents().last().replaceWith(" Loading");
            } else 
            {
                setTimeout(function () {
                    btn.children().removeClass('fa-spin');
                    btn.contents().last().replaceWith(" Refresh");
                }, 2000);
            }
        }
    </script>

    

    </body>
</html>
