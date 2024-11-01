<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zijkartPaypal extends zijkartPayment{
		function placeorder(){
			$cartid = $this->getCartid();
			if($cartid != null){
				$items = $this->getCartItems($cartid);
				$padata  =  '&METHOD=SetExpressCheckout';
				$returnurl = site_url("?page_id=".zijkart::getPageid()."&zkm=cart&zktask=notified&zijclass=zijkartpaypal&zijcartid=" . $cartid);
				$padata .=  '&RETURNURL='.urlencode($returnurl);
				$cancelurl = site_url();
				$padata .=  '&CANCELURL='.urlencode($cancelurl);
				$padata .=  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
				$i = 0;
				$paidamount = $items[0]->amount;
				$total = 0;
				foreach ($items as $item):
				    $padata .=  '&L_PAYMENTREQUEST_0_NAME'.$i.'='.urlencode($item->name);
				    $padata .=  '&L_PAYMENTREQUEST_0_NUMBER'.$i.'='.urlencode('1');
				    $padata .=  '&L_PAYMENTREQUEST_0_DESC'.$i.'='.urlencode($item->name);
				    $padata .=  '&L_PAYMENTREQUEST_0_AMT'.$i.'='.urlencode($item->price);
				    $padata .=  '&L_PAYMENTREQUEST_0_QTY'.$i.'='. urlencode($item->quantity);
				    $total += ($item->price * $item->quantity);
				    $i++;
				endforeach;
				$padata .=  '&NOSHIPPING=0'; //set 1 to hide buyer's shipping address, in-case products that does not require shipping
				$padata .=  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($total);
				$padata .=  '&PAYMENTREQUEST_0_TAXAMT='.urlencode('0');
				$padata .=  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode('0');
				$padata .=  '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode('0');
				$padata .=  '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode('0');
				$padata .=  '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode('0');
				$padata .=  '&PAYMENTREQUEST_0_AMT='.urlencode($total);
				$padata .=  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode('USD');
				$padata .=  '&LOCALECODE=EN'; //PayPal pages to match the language on your website;
				$padata .=  '&LOGOIMG=http://zijsoft.com/zijsoft/images/logo/logo.png'; //site logo
				$padata .=  '&CARTBORDERCOLOR=FFFFFF'; //border color of cart
				$padata .=  '&ALLOWNOTE=1';
				$httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata);
				//Respond according to message we receive from Paypal
				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
				    $paypalmode = (1 == 1) ? '.sandbox' : '';
				    //Redirect user to PayPal store with Token received.
				    $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
	                wp_redirect($paypalurl);
				    die();
				}else{
				    //Show error message
				    echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				    echo '<pre>';
				        print_r($httpParsedResponseAr);
				    echo '</pre>';
				    wp_die('Error found contact system Administrator');
				}   
			}else{
				wp_die('You don\'t have any item in cart','zijkart');
			}
		}
		function getnotification(){
			$cartid = zijkartRequest::getValue('zijcartid');
	        $data = $this->getData($cartid);
	        if ($data != false) {
	            $phone = isset($data['PHONENUM']) ? urldecode($data['PHONENUM']) : '';
	            $this->payer_email = urldecode($data['EMAIL']);
               	$this->payer_firstname = urldecode($data['FIRSTNAME']);
               	$this->payer_lastname = urldecode($data['LASTNAME']);
               	$this->amountpaid = urldecode($data['AMT']);
               	$this->token = urldecode($data['PAYERID']);
               	$this->payer_address = urldecode($data['SHIPTOSTREET']).", ".urldecode($data['SHIPTOCITY']).", ".urldecode($data['SHIPTOSTATE']).", ".urldecode($data['SHIPTOCOUNTRYNAME']);
           		$this->payer_contact = urldecode($phone);
               	$this->paymentverified = 1;
               	$this->payment_method = 'Paypal Express checkout';

				$this->updateNotified($cartid);
	        }else{
	        	$this->setRedirect();
	        }
		}

	    public function getData($cartid){
	        if(is_numeric($cartid)){
	            $padata  =  '&TOKEN='.urlencode($_GET['token']);
	            $padata .=  '&PAYERID='.urlencode($_GET['PayerID']);
	            $padata .=  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");

	            // get the cart data 
				$items = $this->getCartItems($cartid);
				$i = 0;
				$paidamount = $items[0]->amount;
				$total = 0;
				foreach ($items as $item):
				    $padata .=  '&L_PAYMENTREQUEST_0_NAME'.$i.'='.urlencode($item->name);
				    $padata .=  '&L_PAYMENTREQUEST_0_NUMBER'.$i.'='.urlencode('1');
				    $padata .=  '&L_PAYMENTREQUEST_0_DESC'.$i.'='.urlencode($item->name);
				    $padata .=  '&L_PAYMENTREQUEST_0_AMT'.$i.'='.urlencode($item->price);
				    $padata .=  '&L_PAYMENTREQUEST_0_QTY'.$i.'='. urlencode($item->quantity);
				    $total += ($item->price * $item->quantity);
				    $i++;
				endforeach;
	            $padata .=  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($total);
	            $padata .=  '&PAYMENTREQUEST_0_TAXAMT='.urlencode('0');
	            $padata .=  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode('0');
	            $padata .=  '&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode('0');
	            $padata .=  '&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode('0');
	            $padata .=  '&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode('0');
	            $padata .=  '&PAYMENTREQUEST_0_AMT='.urlencode($total);
	            $padata .=  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode('USD');

	            $httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $padata);
	            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
	                // echo '<h2>Success</h2>';
	                // echo 'Your Transaction ID : '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
	                /*
	                //Sometimes Payment are kept pending even when transaction is complete. 
	                //hence we need to notify user about it and ask him manually approve the transiction
	                */
	                if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
	                    // echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
	                }elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
	                    // echo '<div style="color:red">Transaction Complete, but payment may still be pending! '.
	                    // 'If that\'s the case, You can manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
	                }
	                $padata =   '&TOKEN='.urlencode($_GET['token']);
	                
	                $httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata);

	                if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
	                    return $httpParsedResponseAr;
	                }else{
	                    return null;
	                }                
	            }else{
	                return null;
	            }
	        }
	        return null;
	    }

		function PPHttpPost($methodName_, $nvpStr_) {

		    // Set up your API credentials, PayPal end point, and API version.
		    $config = zijkart::getModel('option')->getOptionsByFor('paypal');
		    // $API_UserName = urlencode('shoaibrehmatali-facilitator_api1.gmail.com');
		    // $API_Password = urlencode('SZCYECGVZ9WF45VG');
		    // $API_Signature = urlencode('AFcWxV21C7fd0v3bYYYRCpSSRl31Aanr3YUQHExenEcs8ond-BnI-URP');
		    $API_UserName = urlencode($config['paypal_username']);
		    $API_Password = urlencode($config['paypal_password']);
		    $API_Signature = urlencode($config['paypal_signature']);
		    
		    $paypalmode = (1 == 1) ? '.sandbox' : '';

		    $API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
		    $version = urlencode('109.0');
		
		    // Set the curl parameters.
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		    curl_setopt($ch, CURLOPT_VERBOSE, 1);
		    //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
		    
		    // Turn off the server and peer verification (TrustManager Concept).
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		    
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_POST, 1);
		
		    // Set the API operation, version, and API signature in the request.
		    $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

		    // Set the request as a POST FIELD for curl.
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
		    // Get response from the server.
		    $httpResponse = curl_exec($ch);
		
		    if(!$httpResponse) {
		        wp_die("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).') contact your system Administrator');
		    }
		
		    // Extract the response details.
		    $httpResponseAr = explode("&", $httpResponse);
		
		    $httpParsedResponseAr = array();
		    foreach ($httpResponseAr as $i => $value) {
		        
		        $tmpAr = explode("=", $value);
		        
		        if(sizeof($tmpAr) > 1) {
		            
		            $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		        }
		    }
		
		    if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		        
		        wp_die("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		    }
		
		    return $httpParsedResponseAr;
		}

	}
	$config = zijkart::getModel('option')->getOptionsByFor('paypal');
	if(isset($config['paypal_enable']) && $config['paypal_enable'] == 1){
		add_filter('zijkart_payment_methods','zijkart_paypal_class');
		function zijkart_paypal_class($classes){
			$classes[] = array('class' => 'zijkartPaypal', 'title' => 'PayPal Express Checkout');
			return $classes;
		}
	}

?>
