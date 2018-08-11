<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"F:\www\local.shop\public/../application/home\view\order\orderdone.html";i:1533975487;s:58:"F:\www\local.shop\application\home\view\public\topNav.html";i:1533975102;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>成功提交订单</title>
	<link rel="stylesheet" href="<?php echo config('home_static'); ?>/style/base.css" type="text/css">
	<link rel="stylesheet" href="<?php echo config('home_static'); ?>/style/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo config('home_static'); ?>/style/header.css" type="text/css">
	<link rel="stylesheet" href="<?php echo config('home_static'); ?>/style/success.css" type="text/css">
	<link rel="stylesheet" href="<?php echo config('home_static'); ?>/style/footer.css" type="text/css">
</head>
<body>
	<!-- 顶部导航 start -->
		<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<li>您好，欢迎来到京西！
						<?php if ( session('member_id') ) : ?>
							<a href="javascript:;" style="color:red;"><?php echo session('member_username') ?></a>
							[<a href="<?php echo url('/home/public/logout'); ?>">退出</a>]
						<?php else : ?>
							[<a href="<?php echo url('/home/public/login'); ?>">登录</a>]
							[<a href="<?php echo url('/home/public/register'); ?>">免费注册</a>]
						<?php endif; ?>
					</li>
					<li class="line">|</li>
					<li><a href="<?php echo url('/home/order/selfOrder'); ?>">我的订单</a></li>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>
	
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="<?php echo config('home_static'); ?>/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr flow3">
				<ul>
					<li>1.我的购物车</li>
					<li>2.填写核对订单信息</li>
					<li class="cur">3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
	<div class="success w990 bc mt15">
		<div class="success_hd">
			<h2>订单提交成功</h2>
		</div>
		<div class="success_bd">
			<p><span></span>订单提交成功，我们将及时为您处理</p>
			
			<p class="message">完成支付后，你可以 <a href="">查看订单状态</a>  <a href="">继续购物</a> <a href="">问题反馈</a></p>
		</div>
	</div>
	<!-- 主体部分 end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt15">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="<?php echo config('home_static'); ?>/images/xin.png" alt="" /></a>
			<a href=""><img src="<?php echo config('home_static'); ?>/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="<?php echo config('home_static'); ?>/images/police.jpg" alt="" /></a>
			<a href=""><img src="<?php echo config('home_static'); ?>/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->
</body>
</html>
