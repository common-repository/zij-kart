jQuery(document).ready(function($){
	$('img.zk_plus').click(function(e){
		var span = $(this).parent().find('span.zk_qty');
		var total = $(span).attr('data-qty');
		var current = parseInt($(span).html());
		if(total > current){
			current += 1;
		}
		$(span).html(current);
	});
	$('img.zk_minus').click(function(e){
		var span = $(this).parent().find('span.zk_qty');
		var current = parseInt($(span).html());
		if(current > 0){
			current -= 1;
		}
		$(span).html(current);
	});
	//Shopping cart javascript
	$('img.zk_addkart').click(function(e){
		var quantity = $(this).parent().find('div.zk_pro_qty').find('span.zk_qty').html();
		quantity = parseInt(quantity);
		var productid = $(this).attr('data-zij-proid');
		productid = parseInt(productid);
		if(quantity == 0){
			var msg = zijkart.qtyerror;
			alert(msg);
		}else{
	        jQuery.post(zijkart.ajaxurl, {action: 'zijkart_ajax', zkm: 'cart', zkajaxtask: 'addtocart', quantity: quantity, productid: productid},function(data){
	            if (data){
	            	jQuery('div#zk_popup').html(data).slideDown('slow');
	            }
	        });
		}
	});
	//Cart functions
	$('img.zk_update').click(function(e){
		var itemid = $(this).attr('data-id');
		var value = $('input#qty_'+itemid).val();
		if(value < 1){
			alert(zijkart.qtyerror);
			return;
		}
		jQuery.post(zijkart.ajaxurl,{action: 'zijkart_ajax', zkm: 'cart', zkajaxtask: 'updatecart', quantity: value, itemid: itemid},function(data){
			if(data){
				location.reload();
			}
		});
	});
	$('img.zk_delete').click(function(e){
		var itemid = $(this).attr('data-id');
		var result = confirm(zijkart.areyousure);
		if(result == true){
			jQuery.post(zijkart.ajaxurl,{action: 'zijkart_ajax', zkm: 'cart', zkajaxtask: 'deletecartitem', itemid: itemid},function(data){
				if(data){
					location.reload();
				}
			});
		}
	});
});

// Functions
function zk_cancelcart(){
	zk_closePopup();
}

function zk_submitcart(){
	var productid = jQuery('input#zk_productid').val();
	var quantity = jQuery('input#zk_quantity').val();
	var name = jQuery('input#zk_name').val();
	var email = jQuery('input#zk_emailaddress').val();
	jQuery.post(zijkart.ajaxurl,{action: 'zijkart_ajax', zkm: 'cart', zkajaxtask: 'savecart',name: name, email: email,productid: productid,quantity: quantity},function(data){
		alert(data);
		zk_closePopup();
	});
}

function zk_closePopup(){
	jQuery('div#zk_popup').slideUp('slow',function(){
		jQuery(this).html('');
	});
}
