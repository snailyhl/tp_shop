<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091800541976",

		//商户私钥
		'merchant_private_key' => "MIIEogIBAAKCAQEA3/AF+ir02CsCD4eiBRmOk6U13etGgAqbgWwEj3+PtOUon1goaJHHgjEkdZM6cDTVhQjEnfdEHGIXyJ4v18VK0kxOaFBR9OKhacog4IyA5uQbGDGxFFTB120yBreYqhsCMf8al6xqB24lhPBA/GxjDVqR7HQY0IlFyKOIP532Eak6Tli7FgA15nvyLIOLGuKRPyg5jahYcpgAuNj9lnB/lIXBAp3rMu42UaAtTM9vfSobnv6wz5LyopqiGLAwA/kfQUfApv0x5zeA3HMWm2qikZ+ZEWuuNJ2sgWIhMHX7uIxszEwTxt3b6y5z/iPXtgMeExo+Fk6Pb9qsqvCY2GZ7SQIDAQABAoIBACeepMAVF4qjWVL8ETtLwUD4Cua/eJKwzcCBJvFohTlhDeXbSBLwL9cl8Khoqv8tKdXGrlqwY7mN1V1mN1q6ijlYzMqB424rJpm77EMuOKQhGXYXXJgXQtBlfmNX2kfGTYYg90h8dano3/R8OVMvrfqv2n92Lum+T71myXVP8jg/XtgDEjKNHC0GHmZVCTb9QoHOPS5LwjzqVli3eReG2lh2tiZBoRtYR4ww7YAlaZMFEdbQu5OlyU8RL5MzoGkwKTjneKvfaLLLMLVmEzBbvdpia9QTvcU2lhJYerNuwyAPQhqkjnBFIlfLinBu6ViSnZliK0YjUStHDWyPBtgLRrECgYEA85qKT3cVkUtMqU04rj1bODkwNqG2WeEZLeTCOhYoL9dK3ja68ZfBWM95jOpgRNeg048ldKyC3nD5lWP6JhFZub5dzn92RaisrLi627itWveUouGo1LtTs7gjhLaZxUvyLP7EelF81LZhfFWpvdhwCju+csQTTnTIFVKCkM0WnU0CgYEA61VKTFJiwYaq05TVjDZLuZIx4SE41Ry1qSSinO4UeVmh+pkFzo2+NdWCPKeFDXMHTv9BSDpCX+d6h9Q8FfwPQlbDfpwwHqUfExgnwe4iJs/5eIbkbyq50KvHm3C1oRE2E1bG6zJm/3VRCSUUhdc3ff6eheCrNQBHO54wF6bwx+0CgYA8Ox2vgDYo8oFF6v30tt7zC9DL8TfxCSL8oe8UBZ5yFb1BLC8UxPzdRI89NEYRP29YX7BXJMBG15AeS9Xxy6BvuI3hHMrVdlsbaaFp6kOSllx92WWxE5Aig4jkq3FhwuFiFqvlwngm7+EyqMuLURSCszrnjWtonp9KBliaDNtmkQKBgFpRIXKpK72aMXWCaXKmY+mUchA079raad3AcahItxLbk47Zq6DaRWXjy1f78tR2kReAX02ZDmBANoZqraTCWBjds6mjx9P7CzII0zlaa0D6EIRxQLppa2bqhnDhkNOAB4DkjPM3JNBl9fPMh1PWhTWVwsEeuJZexPfcfhiVuVMBAoGAOEdZNb4Yr7X84OWmKjR/I060DmTavlEsupi0kzdSJx5z/ndg1mixe0XjUoGaXIZExJTS2tqUjtl+odCpwyMkS0XBBXQ55YBrxxztAXlwNxz1I8BLiy/ysc5EJtZOWK5LXzMTSpaQ9pt+/F9Ld2tbjUU4Qk1H+5c1C+BIGqEs4oU=",
		
		//异步通知地址(post方式)
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转 (get方式)
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do", // 沙箱环境
		// 'gatewayUrl' => "https://openapi.alipay.com/gateway.do",  // 正式环境

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3/AF+ir02CsCD4eiBRmOk6U13etGgAqbgWwEj3+PtOUon1goaJHHgjEkdZM6cDTVhQjEnfdEHGIXyJ4v18VK0kxOaFBR9OKhacog4IyA5uQbGDGxFFTB120yBreYqhsCMf8al6xqB24lhPBA/GxjDVqR7HQY0IlFyKOIP532Eak6Tli7FgA15nvyLIOLGuKRPyg5jahYcpgAuNj9lnB/lIXBAp3rMu42UaAtTM9vfSobnv6wz5LyopqiGLAwA/kfQUfApv0x5zeA3HMWm2qikZ+ZEWuuNJ2sgWIhMHX7uIxszEwTxt3b6y5z/iPXtgMeExo+Fk6Pb9qsqvCY2GZ7SQIDAQAB",
);