<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"F:\www\local.shop\public/../application/admin\view\role\add.html";i:1533111964;}*/ ?>
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
         .box th, .box td{border: 1px solid #ccc;}
        .box b{color:blue;}
        li{list-style: none;}
        .box .ul_f{float:left;} 
        .box .son{padding-left: 10px;}
    </style>

</head>

<body>
    <div class="place">
        <span>位置：</span>
        <ul class="placeul">
            <li><a href="#">首页</a></li>
            <li><a href="#">表单</a></li>
        </ul>
    </div>
    <div class="formbody">
        <div class="formtitle">
            <span class="active">基本信息</span>
        </div>
        <form action="" method="post">
            <ul class="forminfo">
                <li>
                    <label>角色名称</label>
                    <input name="role_name" placeholder="请输入角色名称" type="text" class="dfinput" /><i></i>
                </li>
                <li>
                    <label>分配权限</label>
                    <table width="600px" border="1px" rules="all" class="box">
                        <!-- 循环遍历出1级(顶级)权限的id -->
                        <?php foreach ( $children[0] as $one_auth_id ) : ?>
                        <tr>
                            <th><input onclick="all_select(this)" type="checkbox" value="<?php echo $one_auth_id; ?>" name="auth_ids_list[]"><?php echo $auths[$one_auth_id]['auth_name']; ?></th>
                            <td>
                                <!-- 循环遍历出2级权限的id -->
                                <?php foreach ( $children[$one_auth_id] as $two_auth_id ) : ?>
                                <ul class="ul_f">
                                    <b><input onclick="all_select(this);up_select(this, '<?php echo $one_auth_id; ?>')" value="<?php echo $two_auth_id; ?>" type="checkbox" name="auth_ids_list[]"><?php echo $auths[$two_auth_id]['auth_name']; ?></b>
                                    <ul>
                                        <!-- 循环遍历出3级权限的id -->
                                        <!-- 2级权限下面可能没有三级权限,所以需要判断$children[$two_auth_id]的值是否存在 -->
                                        <?php foreach ( isset($children[$two_auth_id]) ? $children[$two_auth_id] : [] as $three_auth_id ) : ?>
                                        <li class="son"><input onclick="up_select(this,'<?php echo $two_auth_id; ?>,<?php echo $one_auth_id; ?>');" value="<?php echo $three_auth_id; ?>" type="checkbox"  name="auth_ids_list[]"><?php echo $auths[$three_auth_id]['auth_name']; ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </ul>
                                <?php endforeach ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </table>
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
//   
/**
 * 选中父分类时自动选中其所有子分类
 * @param  DOM对象 ele 当前元素的DOM对象
 */
function all_select(ele) {
    // console.log( ele.checked ); // ele.checked: input元素的选中状态值 true-选中  false-未选中
    $(ele).parent().next().find('input').prop('checked', ele.checked);
}


/**
 * 选中子分类时自动选中所有上级分类,子分类全部取消选中时,父分类取消选中
 * @param  DOM对象  ele    当前元素的DOM对象
 * @param  string  parents  以逗号分隔的上级分类的value值组成的字符串 例: 1,2,5
 */
function up_select(ele,parents) {
    // 将parent转换成数组
    var ids = '[' + parents + ']';
    // console.dir( $ids );

    // 选中所有上级分类
    $.each(ids, function (k,v) {
        $("input[value='" + v + "']").prop('checked', true);
    });

    // --------------- 子分类全部取消选中时,父分类取消选中 ---------------------

    // 三级分类没有选中的时候,取消当前二级分类的选中
    // 获取 当前三级分类的父分类 的 所有选中状态的子分类 的集合
    var sib_checked = $(ele).parent().parent().find('input:checked');
    // console.log( sib_checked );
    if ( sib_checked.length == 0 ) {
        $(ele).parent().parent().prev('b').children('input').prop('checked', false);
    }


    // 二级和三级分类都没有选中的时候,取消一级分类的选中
    // 思路: 1. 找到当前二级元素和三级元素的共同的祖先td
    //       2. 在获取到td下选中的input的个数,如果为0,说明没有子分类被选中
    // 获取当前的二级元素和三级元素所在td 下的 所有选中状态下的input对象 的集合
    var all_checked = $(ele).parents('td').find('input:checked');
    if ( all_checked.length == 0 ) {
        $(ele).parents('tr').children('th').find('input').prop('checked', false);
    }

}
</script>

</html>
