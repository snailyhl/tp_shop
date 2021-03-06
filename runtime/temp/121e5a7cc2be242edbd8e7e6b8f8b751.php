<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"F:\www\local.shop\public/../application/admin\view\goods\add.html";i:1533476717;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="<?php echo config('admin_static'); ?>/css/style.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="<?php echo config('admin_static'); ?>/js/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="/static/plugins/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/static/plugins/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加载语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="/static/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
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
            <li><a href="#">首页</a></li>
            <li><a href="#">表单</a></li>
        </ul>
    </div>
    <div class="formbody">
        <div class="formtitle">
            <span class="active">基本信息</span>
            <span>商品属性信息</span>
            <span>商品相册</span>
            <span>商品描述</span>

        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <ul class="forminfo">
                <li>
                    <label>商品名称</label>
                    <input name="goods_name" placeholder="请输入商品名称" type="text" class="dfinput" /><i>名称不能超过30个字符</i>
                </li>
                <li>
                    <label>商品价格</label>
                    <input name="goods_price" placeholder="请输入商品价格" type="text" class="dfinput" /><i></i>
                </li>
                <li>
                    <label>商品库存</label>
                    <input name="goods_number" placeholder="请输入商品数量" type="text" class="dfinput" />
                </li>
                <li>
                    <label>商品分类</label>
                    <select name="cat_id" class="dfinput" >
                        <?php if(is_array($categorys) || $categorys instanceof \think\Collection || $categorys instanceof \think\Paginator): if( count($categorys)==0 ) : echo "" ;else: foreach($categorys as $key=>$cat): ?>
                        <option value="<?php echo $cat['cat_id']; ?>"><?php echo str_repeat('&nbsp;', $cat['level']*3); ?><?php echo $cat['cat_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>
                <li>
                    <label>回收站</label>
                    <input name="is_delete" type="radio" value="1" />   是 &nbsp;&nbsp;&nbsp;
                    <input name="is_delete" type="radio" value="0" checked="checked" />   否
                </li>
                <li>
                    <label>是否上架</label>
                    <input name="is_sale" type="radio" value="1" checked="checked" />   是 &nbsp;&nbsp;&nbsp;
                    <input name="is_sale" type="radio" value="0" />   否
                </li>
                <li>
                    <label>是否新品</label>
                    <input name="is_new" type="radio" value="1" checked="checked" />   是 &nbsp;&nbsp;&nbsp;
                    <input name="is_new" type="radio" value="0" />   否
                </li>
                <li>
                    <label>是否热卖</label>
                    <input name="is_hot" type="radio" value="1" checked="checked" />   是 &nbsp;&nbsp;&nbsp;
                    <input name="is_hot" type="radio" value="0" />   否
                </li>
                <li>
                    <label>是否推荐</label>
                    <input name="is_best" type="radio" value="1" checked="checked" />   是 &nbsp;&nbsp;&nbsp;
                    <input name="is_best" type="radio" value="0" />   否
                </li>
                <li>
                    <label>商品描述</label>
                    <textarea name="" placeholder="请输入商品描述" cols="" rows="" class="textinput"></textarea>
                </li>
                
            </ul>
            <ul class="forminfo">
                <li>
                    <label>选择商品类型</label>
                    <select name="type_id" class="dfinput">
                        <option value="">请选择商品类型</option>
                        <?php if(is_array($types) || $types instanceof \think\Collection || $types instanceof \think\Paginator): if( count($types)==0 ) : echo "" ;else: foreach($types as $key=>$type): ?>
                            <option value="<?php echo $type['type_id']; ?>"><?php echo $type['type_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>
                <li id="attrContainer">
                    
                </li>
            </ul>
            <ul class="forminfo">
                <li>
                    <a href="javascript:;" onclick="cloneImg(this)">[+]</a>&nbsp;&nbsp;<input type="file" name="img[]" />
                </li>
            </ul>
            <ul class="forminfo">
                <li>
                    <label>商品详情</label>
                    <textarea name="goods_desc" id="goods_desc" ></textarea>
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
    // 切换类型,获取属性
    $("select[name='type_id']").change(function () {
        var type_id = $(this).val();
        if ( type_id == '' ) {
            $("#attrContainer").html('');
            return false;
        }
        var _html = '';
        // 发送ajax请求,获取指定类型的商品的所有的属性
        $.get("<?php echo url('/admin/goods/getTypeAttr'); ?>", {"type_id": type_id}, function (res) {
            _html += '<ul>';
            // 循环拼接li标签
            for (var i = 0, length = res.length; i < length; i++) {
                // 每条属性都是一个li标签
                _html += "<li>";

                // 1. 单选属性(attr_type=1)前面有符号[+]
                if ( (res[i].attr_type) == 1 ) {
                    _html += "<a href='javascript:;' onclick='cloneAttr(this)'>[+]</a>";
                    // console.log( _html );
                }
                // 拼接属性名称
                _html += res[i].attr_name + '&nbsp;&nbsp;';
                // 判断属性值的录入方式 0为手动录入 1为列表选择
                // 单选属性录入时需要录入多个值,所以单选属性表单name后面需要拼接[],唯一属性不需要
                var hasManyValue = res[i].attr_type == 1 ? "[]" : '';
                if ( res[i].attr_input_type == 0 ) {
                    _html += "<input type='text' name='goodsAttrValue[" + res[i].attr_id + "]" +  hasManyValue + "' class='dfinput' placeholder='请输入属性值'>";
                }else {  // 列表选择
                    // attr_values : "8G|16G|32G|64G"
                    var attr_values = res[i].attr_values;
                    var arr_values = attr_values.split('|'); //arr_values: [8G,16G,32G,64G]
                    _html += "<select class='dfinput'  name='goodsAttrValue[" + res[i].attr_id + "]" +  hasManyValue + "'>";
                    _html += "<option value=''>请选择</option>";
                    // 循环创建option标签
                    for ( var j = 0; j < arr_values.length; j++ ) {
                        _html += "<option value='" + arr_values[j] + "'>" + arr_values[j] + "</option>";
                    }
                    _html += "</select>";
                }
                // 4. 单选属性需要拼接价格的input框
                // 价格只针对单选属性,name后面必须加[]
                if ( res[i].attr_type == 1 ) {
                    _html += "&nbsp;&nbsp;属性价格: <input type='text' class='dfinput' name='goodsAttrPrice[" + res[i].attr_id + "][]' placeholder='请输入价格' />";
                }

                _html += '</li>';
            }
            _html += "</ul>";

            // 把最终拼接好的ul放到属性容器里
            $('#attrContainer').html(_html);
        }, 'json');
    });

    function cloneAttr(ele) {
        var text = $(ele).html();
        if ( text == '[+]' ) {
            var newLi = $(ele).parent().clone();
            newLi.children('a').html('[-]');
            newLi.children('input').val('');
            $(ele).parent().after(newLi);
        }else {
            $(ele).parent().remove();
        }
    }

    function cloneImg(ele){
        var text = $(ele).html();
        if ( text == '[+]' ) {
            var newLi = $(ele).parent().clone();
            newLi.children('a').html('[-]');
            newLi.children('input').val('');
            $(ele).parent().after(newLi);
        }else {
            $(ele).parent().remove();
        }
    }

    var ue = UE.getEditor('goods_desc');

    $(".formtitle span").click(function(event){
        $(this).addClass('active').siblings("span").removeClass('active');
        var index = $(this).index();
        $("ul.forminfo").eq(index).show().siblings(".forminfo").hide();
    });
     $(".formtitle span").eq(0).click();
</script>

</html>
