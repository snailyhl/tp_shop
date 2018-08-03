<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"F:\www\local.shop\public/../application/admin\view\category\upd.html";i:1533218482;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="<?php echo config('admin_static'); ?>/css/style.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="<?php echo config('admin_static'); ?>/js/jquery.js"></script>
    <style>
        .active{
            border-bottom: solid 3px #66c9f3;
        }
    </style>
</head>

<body>
    <div class="place">
        <span>位置：</span>
        <ul class="placeul">
            <li><a href="#">用户管理</a></li>
            <li><a href="#">新增用户</a></li>
        </ul>
    </div>
    <div class="formbody">
        <div class="formtitle">
            <span class="active">基本信息</span>

        </div>
        <form action="" method="post">
            <input type="hidden" name="cat_id" value="<?php echo $cat['cat_id']; ?>" />
            <ul class="forminfo">
                <li>
                    <label>分类名称</label>
                    <input name="cat_name" value="<?php echo $cat['cat_name']; ?>" placeholder="请输入分类名" type="text" class="dfinput" /><i></i>
                </li>
                <li>
                    <label>所属分类</label>
                    <select name="pid" class="dfinput">
                        <option value="">父分类</option>
                        <option value="0">顶级分类</option>
                        <?php if(is_array($categorys) || $categorys instanceof \think\Collection || $categorys instanceof \think\Paginator): if( count($categorys)==0 ) : echo "" ;else: foreach($categorys as $key=>$category): ?>
                            <option value="<?php echo $category['cat_id']; ?>"><?php echo str_repeat('&nbsp;', $category['level']*3); ?><?php echo $category['cat_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>
                <li>
                    <label>显示导航栏</label>
                    <input name="is_show" type="radio" value="1" /> 是
                    <input name="is_show" type="radio" value="0" /> 否
                </li>           
            </ul>
			<li>
                <label>&nbsp;</label>
                <input name="" id="btnSubmit" type="submit" class="btn" value="确认保存" />
             </li>
        </form>
    </div>
</body>
<script>
    // 默认选中当前父分类
    $("select[name='pid']").val("<?php echo $cat['pid']; ?>");
    // 默认选中 是否显示导航栏
    var is_show = "<?php echo $cat['is_show']; ?>";
    $("input[name='is_show'][value=" + is_show + "]").prop('checked', true);
    
</script>

</html>
