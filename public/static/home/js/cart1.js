/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){
	//公用函数
	function changCartNum(goods_id,goods_attr_ids,goods_number,callback){
		var param = {"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":goods_number};
		$.get('/home/cart/changeCartNum',param,callback,'json');
	}
	//减少
	$(".reduce_num").click(function(){
		var _self = $(this);
		var goods_id = _self.parent().attr('goods_id');
		var goods_attr_ids = _self.parent().attr('goods_attr_ids');
		var amountEle = $(this).parent().find(".amount");//当前的数量的对象
		var amount = parseInt( $(this).parent().find(".amount").val() );//当前数量 转化为整形
		if (amount <= 1){
			layer.msg("商品数量最少为1",{icon:0});
			return;
		}
		var reduce = amount - 1;
		//发送封装的ajax请求函数
		changCartNum(goods_id,goods_attr_ids,reduce,function(json){
			if(json.code == 200){
				//小计	
				amountEle.val(reduce);
				var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(reduce);
				_self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
				//总计金额
				var total = 0;
				$(".col5 span").each(function(){
					total += parseFloat($(this).text());
				});

				$("#total").text(total.toFixed(2));
			}
		});
		
	});

	//增加
	$(".add_num").click(function(){
		var _self = $(this);
		var goods_id = _self.parent().attr('goods_id');
		var goods_attr_ids = _self.parent().attr('goods_attr_ids');
		//获取文本框的对象 然后把值转为为int类型做加1处理
		var amountEle = $(this).parent().find(".amount");
		var amount = parseInt($(this).parent().find(".amount").val());
		var add = amount + 1;
		changCartNum(goods_id,goods_attr_ids,add,function(json){
			if(json.code == 200){
				//小计
				amountEle.val(add);
				var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(add);
				_self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
				//总计金额
				var total = 0;
				$(".col5 span").each(function(){
					total += parseFloat($(this).text());
				});

				$("#total").text(total.toFixed(2));
			}
		})
	});

	//直接输入
	$(".amount").blur(function(){
		var _self = $(this);
		var goods_id = _self.parent().attr('goods_id');
		var goods_attr_ids = _self.parent().attr('goods_attr_ids');
		var amount = _self.val();
		var reg = /^\d+$/;
		if(!reg.test(amount)){
			// alert('数量必须是数字');
			layer.msg("必须是数字",{icon:0});
			_self.val(_self.attr('ori_amount'));
			return;
		}
		if (amount < 1){
			layer.msg("商品数量最少为1",{icon:0});
			_self.val("1");
			return;
		}
		changCartNum(goods_id,goods_attr_ids,amount,function(json){
			if(json.code == 200){
				//小计
				var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(amount);
				_self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
				//总计金额
				var total = 0;
				$(".col5 span").each(function(){
					total += parseFloat($(this).text());
				});

				$("#total").text(total.toFixed(2));
			}
		})
	});

	//获取焦点时记录原来的数量
	$('.amount').focus(function(){
		$(this).attr('ori_amount',$(this).val());
	});
});