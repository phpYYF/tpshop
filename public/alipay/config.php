<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091300503456",

		//商户私钥
		'merchant_private_key' => "MIIEpgIBAAKCAQEAzYKPJb1uBSiRv3GsrzMTK/rZFmvQhaHc3y04h6eDCJlqmAV8x5wLF8FBXmHxhu7Flx5V83Ka2MUu8Lz8hPvcdPIeUfyesRNtC0Ec5/iakqOJ8nOWhNIPjKKC99ETwKuErtYrLazxm0YTU6WJGtBzRlPTYMqZwk8J5kaq2jvUZwCcC2A9QVyYNkbfhE0e2He9x6Hm/taaZmTyJm4FFcUVuy5z236OPy2ODeP1lJBD3YrYTL3bPVelP1SwKMsjXzDj3eaGvsthIjXQTWzf01SPsPk0/AaOJbJ3WqV6ijwZXOcqKtJkJZ80rJpXx7bf1IZgEXhppp3U43PZHK/0WzioPwIDAQABAoIBAQCnQN9xjpaw2hCsJz6sI7wxqejtx3cgmtggRjRgquMIp5tJ+AVSitu4TI7/2mRwNXlYANdg11/QlcIbKSO2syf7gWVNwnQAl1fCtg5peDmMqE5HpOzWUgNXqJdASsdxoeXNSP5BISRNA54NEIbh7M40aVp5xsmWiy76W38HN3QpEq2DyoVn0dGBvMElCV5ndM9zAc3JMIV8D2e+tFJqTXa04AxUQDke69hKWVFKRpz3hGIPUHNurE9DY/K3IP70ARSrUUng9kibWxJELIbE2DWKsmPUkcWaVTYiCajMw5XKiL1FPd+Qb45blMbT2KHMeWvdJ2H3pqptU6+PPqY5nA6xAoGBAP7FbTtZuB7hgoVCcG85psXjxJT6I+w7YiE9fR0g76OTlxhUsBjXxxaLUYlmbWr66iPOBWp2nDPxiFheuHLHRijwQdWLi1AJ5FXva8jtFkxCexhyVJFs5KYAiIRtVIw8ejpktux/wFWWAQWjwyybZVvfNcPYuc0ufMPHDt94nykHAoGBAM6ATupWOwiB5aTF7WQ80z+lYtHEMtLQxKReU4T/o27tel8QAJ0GRP4wF22PRvCHHtBJWvQqqth73Ut7jUIzdawvxMIB53FkeyGf+0SJpK6EevDwF+SZJqrC7yKJYAyTl1BX4xFAEuVju9fJ/8cbkRZJz6bcKq5NsKw3naqw81EJAoGBANr9vufONfqtYvDC7Dxe9OCS6O4ZmXk8jhv+a6X3IqWbfrwWR4wDJglV1c9iMNlNNqdyNquNqrTnf4heWvOmIMOdySkSmrxA1HmdpCuAx7LjTkX0OIcMb/nU6YPTKmKktXeuDKJ7KUsn9lbvrhuQs25mDiP7DbK1q97pIvqqBz1PAoGBALGCelPWbEiT4OQTgE16O92qPZg0H7w42dCxhPn/Bm6ElXzCNMTGbuhSTtFPKJv6ivJhHF5njxUo5MHOI0+VDMJHDcv39wvyZCYzb7sq1vVtzIDw4pkPTb3cc9QsYvRsqtXVtstNWPD3GrIUYlq1x7JxIJ8/AzFydzyTUmU4VvCBAoGBAJtQXdbURPsuWy4ilfNl5Q6KccZ/mcPdDVTgIcP0B3oH2/1axMaxlD+H/Q+7F7jig1rHQVRjPz39JU4CFAax9g5c4NlfDS9iEEuZYsjOjH/JR/YqnyIsOp+R8pK/t4UJX7a4FTIELfRuB7qHOoj1jOSO94Kokhmxc5P3wgxyUDGp",
		
		//异步通知地址
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuc2L2aE5OYG8ZVxIRJoYF9dEBdJ6R/L5MQDGwwDCpu2hzm4AGIfUVp6J7MOFZ0s5uHjhq7R4zhaL2k4ZDrhxIMkx3FX2ptwfW5Xoh5lJ8w+aIEPMOrtTnirQaFsed7RNObKJPQ5+ZbFUY5EAxKZW7rvjyd1hPEaQ0Cps2kVDP81EOCcJqvy8WTY3xsgFgkyZ3pjW/LsXt3p7vBOpS+DY8PsivSHzcD6i0z9KSZNFqpii69Qzg4ortDk+vp+2j+UxnB79uyihiozHUDduvbHcsdBwqJ206xdaouN2lMi0yve39lUpxFDmwaSlqX/EJHZZWc2AP6L/0UC5n3NpdFvW2wIDAQAB",
);