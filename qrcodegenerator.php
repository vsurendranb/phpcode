<?php ob_start(); ?> 
<script type="text/javascript">
function addDegree(){
	document.getElementById("store").value = document.getElementById("store").value + '&deg;' ;}
</script>
<?php

include("include/session.php");
include("class/qrcode.class.php");

if(!$session->logged_in){
   header("Location: ". SITE_URL .'login.php'); }
if($_SESSION['cnt_cc'] == "") {
	$session->logout();
   header("Location: ". SITE_URL .'login.php'); }    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Bal Pharma Limited</title>
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<script src="js/global.js?v=1" type="text/javascript"></script>
	<script src="js/gen_validatorv4.js?v=3" type="text/javascript"></script></head>	
<body>
<div id="Template">
	<div id="Wrapper">
	  <div id="Header">
	  		<?php  require("include/header.php");?>
		</div>
	  <div id="MainBody" border="1px solid red;"><?php 
	  	error_reporting(0);
		//define('PREFIX_ZERO', '0');
		//define('PREFIX_ONE', '1');
		define('PREFIX_FIVE', '5');?>

 	<form name="barcodegenform" action="" method="post" >	  
		<?php 
			if(isset($_POST['getbarcode'])) {?>
		<table width="888" border="0" cellpadding="3" align="center" style="border: 1px solid #BFCDDB;margin-top:30px;font-size:13px;">
		<td width="100" bgcolor="#A3D3EF"><b>Name and Address of Manufacturer</b></td>
		<td width="320" bgcolor="#F0F0F0"></td>
		<tr>
			<td style="padding:0 0 0 8px;" colspan="2"> 
			<table width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;">
			<tr><td align="Left" ><?php echo $session->userinfo['cnt_name'] ?>
			<?php echo $session->userinfo['add1'] ?>
			<?php echo $session->userinfo['add2'] ?>
			<?php echo $session->userinfo['add3'] ?>
			<?php echo $session->username . "-&nbsp;". date("Y-m-d H:i:s") ?></td></tr>
			</table>
			</td>
		</tr>
		<tr>
			<td width="200" bgcolor="#A3D3EF"><b>Product ID</b></td>
			<td width="380" bgcolor="#F0F0F0"><?php echo $_POST['product_id']?></td>
		</tr>
		<tr>
			<td width="200" bgcolor="#A3D3EF"><b>Product Name</b></td>
			<td width="380" bgcolor="#F0F0F0"><?php echo $_POST['product_name']." ". $_POST['ph']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>GTIN Code</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['gtin_code']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Batch ID</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['batch_id']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Batch Size</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['batch_size']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Mfg Date</b> </td>
	 		<td width="75" bgcolor="#F0F0F0"> <?php echo $_POST['mfgmonth']?> / <?php echo $_POST['mfgyear']?> </td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Exp/Retest Date</b> </td>
	 		<td width="75" bgcolor="#F0F0F0"> <?php echo $_POST['expmonth']?> / <?php echo $_POST['expyear']?> </td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Quantity</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['quantity']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>storage</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['storage']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Mfg Licence No</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['mfg_lic_no']?></td>
		</tr>
		<tr>
			<td width="50" bgcolor="#A3D3EF"><b>No of Shipper Cartons</b></td>
			<td width="15" bgcolor="#F0F0F0"> <?php echo $_POST['ssccode']?>  <b>Starting No</b>  <?php echo $_POST['startno']?>  <b>Ending No</b> <?php echo $_POST['endno']?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Shipping Mark</b></td>
			<td width="75" bgcolor="#F0F0F0"><?php echo $_POST['shpadd']?></td>
		</tr>
		  
		<tr style="display:none;">
			<td width="100" bgcolor="#A3D3EF">QR CODE</td>
			<td width="75" bgcolor="#fff">
			<div id="print_div" style="height: 159px;overflow: scroll;width: 497px;display:none;" > 
			<?php 
			$intCntCode			= $_SESSION['cnt_code'];
			$TotalCartonsCount 	= (int)$intSSCCCounter + (int)$intendno; 
			 $intProductId 		= $intCntCode . trim($_POST['product_id']) ;
			 $intProductId 		= (strlen($intProductId)<=12 ? PREFIX_FIVE.$intProductId : $intProductId);
			
				if($_SESSION['recordAdded'] == "") {				 	
			 	//Updating Counter
				//echo $session->userinfo['username'];
				$updatequery = "UPDATE ". TBL_CNTL." SET counter = ".$TotalCartonsCount." WHERE cnt_cc =" .$session->userinfo['cnt_cc'];
				$updateresult = mysql_query($updatequery);
				//Insert Data into Tbl_Cnt_log
				$query = "INSERT INTO ".TBL_COUNTER_LOG." (counter, added_by) VALUES ($TotalCartonsCount, '$session->username')" ;
				$result = mysql_query($query);
				}
				
			// Calulating Checksum
				 $ChecksumProdID 	= $intProductId;
				 $odd 				= true;
				 $checksumValue 		= 0;
				 $c = strlen($ChecksumProdID);
				 for($i = $c; $i > 0; $i--) {
					 if($odd === true) {
						 $multiplier = 3;
						 $odd = false;
					 } else {
						 $multiplier = 1;
						 $odd = true;
					 }
					 $checksumValue += ($ChecksumProdID[$i - 1] * $multiplier);
				}
				 $checksumValue =  (10 - $checksumValue % 10) % 10;						
			// Calulating Checksum				

			//Inserting tbl_prdapitrn
				 if($_SESSION['recordAdded'] == "") {	
			
					 $mfg_dt = $_POST['mfgmonth'].'/'.$_POST['mfgyear'];
					 $exp_dt = $_POST['expmonth'].'/'. $_POST['expyear'];
 
					 $intDefSSCC = 17;
					 // $intcompcode = $session->userinfo['cnt_cc']. $session->userinfo['cnt_code'];
					 $intcompcode = PREFIX_FIVE.$session->userinfo['cnt_code'];
					 $intDefSSCC = $intDefSSCC - strlen($intcompcode);
					 //Start 
					 $intCartons = $intstartno ;
					 $sscc_stcode= $intcompcode. sprintf('%0'.($intDefSSCC - strlen($intCartons) + strlen($intCartons)).'d' , $intCartons) ;
					 //End
					 $intCartons1  = $intendno;
					 $sscc_edcode = $intcompcode. sprintf('%0'.($intDefSSCC - strlen($intCartons1) + strlen($intCartons1)).'d' , $intCartons1) ;
					 // cnt_cc,cnt_code,cnt_name,add1,add2,add3,product_id,gtin_code,product_name,prod_uom,prod_size,batch_id,invoice_id,mfg_dt,exp_dt,grs_wgt,storage,mfg_lic_no,case_no,start_no,end_no,shp_add,sscc_stcode,sscc_edcode,cur_timestamp
					 $query = "INSERT INTO ".TBL_PRODAPI_TRANSACTION." 
					 (cnt_cc,cnt_code,cnt_name,add1,add2,add3,product_id,gtin_code,product_name,ph,prod_uom,prod_size,batch_id,batch_size,mfg_dt,exp_dt,storage,mfg_lic_no,case_no,start_no,end_no,shp_add,sscc_stcode,sscc_edcode) VALUES
					 ('".$session->userinfo['cnt_cc']."', '".$session->userinfo['cnt_code']."', '".$session->userinfo['cnt_name']."', '".$session->userinfo['add1']."', '".$session->userinfo['add2']."', '".$session->userinfo['add3']."','".$_POST['product_id']."', '".$_POST['gtin_code']."', '".$_POST['product_name']."','".$_POST['ph']."', '".$_POST['prod_uom']."', '".$_POST['prod_size']."','".$_POST['batch_id']."','".$_POST['batch_size']."','".$mfg_dt."','".$exp_dt."','".$_POST['storage']."','".$_POST['mfg_lic_no']."','".$_POST['case_no']."','".$_POST['startno']."','".$_POST['endno']."','".$_POST['shpadd']."','".$sscc_stcode."','".$sscc_edcode."')" ;
					 $result = mysql_query($query);
				  }
				$_SESSION['recordAdded'] = "Added";
			//Inserting tbl_prdtrn


				 //Checking Year 
				 if(strlen($_POST['expyear']) > 2) {
					 $intYear = substr($_POST['expyear'], -2);
				 }else{
					  $intYear = $_POST['expyear'];
				 }
				 //Barcode 
				 $strBarCode = '(01)'.$intProductId. $checksumValue.'(17)'. $intYear. $_POST['expmonth'].$_POST['expday'].'(10)'.$_POST['batch_id'];  
				 ?>
				 <table width="650" border="0" cellpadding="1"  cellspacing="1" >
				 <?php
				 $intTDCount = 1;
				  for($intCartonCnt= 0; $intCartonCnt <= ($intendno - $intstartno) ; $intCartonCnt++) {
					$intDefSSCC = 17;		
					//$intcompcode = $session->userinfo['cnt_cc']. $session->userinfo['cnt_code'];
					$intcompcode = PREFIX_FIVE.$session->userinfo['cnt_code'];
					$intDefSSCC = $intDefSSCC - strlen($intcompcode);
					//Displaying SSCC Barcode
						$intCartons = $intstartno  +  $intCartonCnt;
						$intTotalCartons= $intcompcode. sprintf('%0'.($intDefSSCC - strlen($intCartons) + strlen($intCartons)).'d' , $intCartons) ;
						if(strlen($intTotalCartons) > 17) {
						$len = strlen($intTotalCartons) - 17 ;
						$intTotalCartons = substr($intTotalCartons, $len);
					   }else{
						$intTotalCartons = $intTotalCartons;
					   }
						$intSSCCBarCode ='(00)'.$intTotalCartons ;
					   if ($intTDCount == 1) echo "<tr>"; 
					   echo "<td>";
					//Displaying new QrCode  
			        $qr = new QrCode();
					$intSSCCode             = $_POST['ssccode'];
					$intProduct_Id 		= trim($_POST['product_id']);
					$intproduct_name	= trim($_POST['product_name']);
					$intph				= trim($_POST['ph']);
					$intcnt_cc			=	$session->userinfo['cnt_cc'];
					$intcnt_name		=	$session->userinfo['cnt_name'];
					$intadd1			=	$session->userinfo['add1'];
					$intadd2			=	$session->userinfo['add2'];
					$intadd3			=	$session->userinfo['add3'];
					$intbatch_id		=	$_POST['batch_id'];
					$intbatch_size		= 	$_POST['batch_size'];
					$intmfg_dt			=	$_POST['mfgmonth'].'/'.$_POST['mfgyear'];
					$intexp_dt			=	$_POST['expmonth'] .'/'. $_POST['expyear'];
					$intstorage		=	$_POST['storage'];
					$intmfg_lic_no		=	$_POST['mfg_lic_no'];
					$intshpadd		=	$_POST['shpadd'];
					$intsscc_stcode	=	$_POST['ssccode'];
					
					$strBarCodeData = "";
					$cols	= 5;
					$intTDCount	= 1;
					$string = $intSerialCode;
						for ($index=0;$index<strlen($string);$index++) {
						if(isNumber($string[$index]))
                            $intSerial .= $string[$index];
                        else    
                        	$strSerial .= $string[$index];
						}                                           
						for($intCartonCnt= 1; $intCartonCnt <= $intSSCCode ; $intCartonCnt++) {
					//QR Code Generator
					$intSerialCode = $strSerial . ($intSerial + $intCartonCnt) ;
					// $strBarCode = $intProductId. $intproduct_name. $intSerialCode;
					$strBarCode = $intProductId. $intproduct_name.$intph. $intcnt_name. $intadd1. $intadd2. $intadd3. $intbatch_id. $intbatch_size. $intmfg_dt. $intexp_dt. $intsscc_stcode. $intstorage. $intmfg_lic_no. $intshpadd. $intSerialCode;
					// $strBarCode = $intProductId. $intproduct_name. $intcnt_name. $intadd1. $intadd2. $intadd3. $intbatch_id. $intbatch_size. $intinvoice_id. $intmfg_dt. $intexp_dt. $intsscc_stcode. $intstorage. $intmfg_lic_no. $intshpadd. $intSerialCode;
					//QR Code Generator
					$qr->text($strBarCode);
					$strBarCodeData .= "<td>";
					// Footer print
					// <p style="page-break-after: always;">&nbsp;</p>
					?>					
					<table width="800" height="450"  border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000; #BFCDDB;margin: top 1px;font-family:Arial, Helvetica, sans-serif; font-size:11px;" >
					<td width="100" bgcolor="#A3D3EF"><b>Name and Address of Manufacturer</b></td>
					<td><?php echo $session->userinfo['cnt_name'] ?><?php echo $session->userinfo['add1'] ?>
					<?php echo $session->userinfo['add2'] ?><?php echo $session->userinfo['add3'] ?></td>
					<p style="page-break-after: always;"></p>
					<tr>
						<td colspan="2" height="4px"></td>                       
					</tr>
						<tr>
							<td width="75" style="padding:0px 8px 0 8px;margin-top:3px;" valign="top" style="font-size:12px;">Product Name</td>
							<td width="205" valign="top" style="margin-top:3px;font-size:12px;"><b><?php echo $_POST['product_name'] . " " . $_POST['ph'] ?></b></td>
						</tr>
					   <tr>
							<td  style="padding:0 0 0 8px;">Batch ID</td>
							<td> <?php echo $_POST['batch_id']?></td>
					   </tr>
					   <tr>
							<td  style="padding:0 0 0 8px;">Batch Size</td>
							<td> <?php echo $_POST['batch_size']?></td>
					   </tr>
					   <tr>
						 	<td style="padding:0 0 0 8px;">Mfg Date</td>
						 	<td> <?php echo $_POST['mfgmonth'] . '/'.$_POST['mfgyear']?> </td>
					   </tr>
					   <tr>
						 	<td style="padding:0 0 0 8px;">Exp/Retest Date</td>
						 	<td><?php echo $_POST['expmonth'] .'/'. $_POST['expyear']?></td>
					   </tr>
					   <tr>
							 <td style="padding:0 0 0 8px;">Quantity</td>
						  	<td><?php echo $_POST['quantity']?></td>
					   </tr>
					   <tr>
						 	<td style="padding:0 0 0 8px;" valign="top">Storage</td>
						  	<td><?php echo $_POST['storage']?></td>
					   </tr>
					   <tr>
							<td  style="padding:0 0 0 8px;">Mfg Licence No</td>
						  	<td><?php echo $_POST['mfg_lic_no']?></td>
					   </tr>
						 <tr>
						 	<td style="padding:0 0 0 8px">Total Case</td>
						 	<td valign="top"><?php echo $_POST['ssccode'] . ' ' . 'Case No' . $intCartons ?></td>
					   </tr>
						<tr>
						 	<td style="padding:0 0 0 8px;" valign="top">Shipping Mark</td>
						  	<td valign="top"><?php echo $_POST['shpadd']?></td>
					   </tr>
					   <tr>					  
						 	<td colspan="2" style="backgroud-color:#f00">
						<?php
						$strBarCodeData ='<html><body>';
						$strBarCodeData .= "<table width=\"200\" border=\"0\" cellpadding=\"1\"  cellspacing=\"1\" ><tr>";
                        // $strBarCodeData .= "<center><img src='".$qr->get_link(75)."' border='0'/></center>";
						$strBarCodeData .= "<center><img src='".$qr->get_link(100)."' border='0'/></center>";
						// To Print Human Readable Format
                        $strBarCodeData .= "<center><span style='font-size:11px'>$strBarCode</span></center>";
						// To Print Human Readable Format
						$strBarCodeData .= ";
						</tr>
					</table>";
					$strBarCodeData .='</html></body>';	echo $strBarCodeData ;
				?>   
				 <br/>
				 </td>
				</tr>
					<tr>
						<td style="padding:0 0 0 8px;" colspan="2"> 
						<table width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;">
						<tr><td colspan="2"><?php echo $session->userinfo['cnt_name'] ?></td></tr>
						<tr><td colspan="2"><?php echo $session->userinfo['add1'] ?></td></tr>
						<tr><td colspan="2"><?php echo $session->userinfo['add2'] ?></td></tr>
						<tr><td><?php echo $session->userinfo['add3'] ?></td><td align="right"><?php echo $session->username . "-&nbsp;". date("Y-m-d H:i:s") ?></td></tr>
						</table> 
						</td>						  
					</tr>
				</table>

					<?php
						$strBarCodeData .= "</td>";
						if ($intTDCount == $cols) {
						$strBarCodeData .= "</tr><tr>";
						$intTDCount = 1;
						} else { 
						$intTDCount++; 
						}
					}
					}
					?>
					</tr>
					</table>                
				</div>
				<br/></td>
			</tr>
		</table><br />
		<table width="100%">
		<tr>
		   <td align="right" >
		 		<a  class="printbutton"  href="javascript:void(0);" onclick="printSelection('print_div');return false">Print Barcode</a>
			</td>
			<td>
		      <a  class="printbutton"  href="" >Back</a>
		   </td>		 
		</tr>
		</table>
  	<?php 
	}else{
	// $results = mysql_query("SELECT * FROM ". tbl_product);
	$results = mysql_query("SELECT * FROM tbl_product order by product_name");
	$query1 = mysql_query('SELECT counter FROM '.TBL_CNTL.' WHERE cnt_cc = '.$_SESSION['cnt_cc']);
	$result1 = mysql_fetch_row($query1);
	$_SESSION['recordAdded'] = '';
	
	if (count($results) > 0) {
	?>
	<table width="888" border="0" cellpadding="3" align="center" style="border: 1px solid #BFCDDB;margin-top:30px;font-size:13px;">
		<tr>
		<td width="100" bgcolor="#A3D3EF"><b>Name and Address of Manufacturer</b></td>
		<td width="320" bgcolor="#F0F0F0"></td>
			<?php 
			?>   
			</td>
			</tr>
			<tr>
				<td style="padding:0 0 0 8px;" colspan="2"> 
				<table width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;">
				<colspan="2"><?php echo $session->userinfo['cnt_cc'] ?>
				<colspan="2"><?php echo $session->userinfo['cnt_name'] ?>
				<colspan="2"><?php echo $session->userinfo['add1'] ?>
				<colspan="2"><?php echo $session->userinfo['add2'] ?>
				<colspan="2"><?php echo $session->userinfo['add3'] ?>
				</table> 
				</td>						  
			</tr>
	</table>

	<table width="888" border="0" cellpadding="3" align="center" style="border: 1px solid #BFCDDB;margin-top:30px;font-size:13px;">
		<tr>
			<td width="200" bgcolor="#A3D3EF"><b>Select Product</b></td>
			 <td width="320" bgcolor="#F0F0F0">			 
				<?php 
				$sqloption= "<select name='prodname' id='prodname' onchange='javascript:fnAssignProdId(this.value);' style='width:300px;'>
					<option value='0'>Select</option>";
					//foreach($results AS $results ){	
					//$row = mysql_fetch_array($results, MYSQL_ASSOC)	
					while ($result = mysql_fetch_array($results, MYSQL_ASSOC)) {
						if ($_REQUEST['prodname']==$result['product_id']) { $sel="selected=selected";}
						$sqloption=$sqloption .  "<option ".$sel." value='".$result['product_id'].'|'
						. str_replace("'",'',($result['product_name'])).'|'
						.$result['gtin_code'].'|'.$result['primary_code'].'|'
						.$result['iprimary_code'].'|'.$result['secondary_code'].'|'
						.$result['tertiary_code'].'|'.$result['prod_uom'].'|'
						.$result['prod_size'].'|'.$result['product_id']. "'>" 
						.$result['product_name']. "</option>";$sel="";	}
				$sqloption=$sqloption . "</select>";
				echo $sqloption;
				?><br>
			</td>
		</tr>

		<tr>						
			<td width="200" bgcolor="#A3D3EF"><b>Auto Input</b></td>
			<td width="320" bgcolor="#F0F0F0">
			Product Code &nbsp&nbsp&nbsp <input type="text" readonly name="product_id" id="product_id" size="3" value="<?php echo $_POST['product_id']?> ">
			&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp GTIN Code <input type="text" readonly name="gtin_code" id="gtin_code" size="14" value="<?php echo $_POST['gtin_code']?> "><br>
			Primary Code &nbsp&nbsp&nbsp <input type="text" readonly name="primary_code" id="primary_code" size="14" value="<?php echo $_POST['primary_code']?> ">
			&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Inner Code <input type="text" readonly name="iprimary_code" id="iprimary_code" size="14" value="<?php echo $_POST['iprimary_code']?> "><br>
			Secondary Code <input type="text" readonly name="secondary_code" id="secondary_code" size="14" value="<?php echo $_POST['secondary_code']?> ">
			&nbsp&nbsp Tertiary Code <input type="text" readonly name="tertiary_code" id="tertiary_code" size="14" value="<?php echo $_POST['tertiary_code']?> "><br>
			Product Name &nbsp&nbsp <input type="text" readonly name="product_name" id="product_name" size="75" value="<?php echo $_POST['product_name']?>"><br>
		 	Product UOM &nbsp&nbsp&nbsp  <input type="text" readonly name="prod_uom" id="prod_uom" size="3" value="<?php echo $_POST['prod_uom']?>">
		<!--Product Size <input type="text" readonly name="prod_size" id="prod_size" size="13" value="<?php echo $_POST['prod_size']?>">
		-->	
			Counter <input type="text" readonly name="counter" id="counter"  size="12" value="<?php echo $result1[0]?>">
		
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Pharmacopoeia</b></td>
			<td width="100" bgcolor="#F0F0F0">
			<input type="text" name="ph"  id="ph" maxlength="20" size="25" value="<?php echo $_POST['ph']?>" />
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="#A3D3EF"><b>Batch ID</b></td>
			<td width="100" bgcolor="#F0F0F0">
			<input type="text" name="batch_id"  id="batch_id" maxlength="20" size="25" value="<?php echo $_POST['batch_id']?>" />
			</td>
		</tr>
			<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Batch Size</b></td>
				<td width="100" bgcolor="#F0F0F0">
				<input type="text" name="batch_size"  id="batch_size" maxlength="20" size="25" value="<?php echo $_POST['batch_size']?>" />
			</td>
			</tr>
			<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Mfg Date</b>(mm/yyyy)</td>
				<td width="100" bgcolor="#F0F0F0">
				<input type="text" name="mfgmonth" id="mfgmonth" maxlength="2" size="2" value="<?php echo $_POST['mfgmonth']?>">/
			 	<input type="text" name="mfgyear" id="mfgyear" maxlength="4" size="4" value="<?php echo $_POST['mfgyear']?>">
		 		</td>
			</tr>
			<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Exp/Retest Date</b> (mm/yyyy)</td>
				<td width="75" bgcolor="#F0F0F0">
				<input type="text" name="expmonth" id="expmonth" maxlength="2" size="2" value="<?php echo $_POST['expmonth']?>">/
				<input type="text" name="expyear" id="expyear" maxlength="4" size="4" value="<?php echo $_POST['expyear']?>">
				</td>
			</tr>
			<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Quantity</b></td>
				<td width="75" bgcolor="#F0F0F0">
				<input type="text" name="quantity"  id="quantity"  size="25"  maxlength="20"  value="<?php echo $_POST['quantity']?>" />
				</td>
		  	</tr>
		 	<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Storage</b></td>
				<td width="75" bgcolor="#F0F0F0">
				<input type="text" name="storage"  id="storage" size="46"  value="<?php echo $_POST['storage']?>" /><span style="nowrap;font-size:10px;"> <a href="javascript:void(0);" onclick="addDegree();"  title="Click to add degree"  style="text-decoration:none;font-size:12px;font-weight:bold;">&deg; degree</a>
				</td>
		  	</tr>
			<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Mfg Licence No</b></td>
				<td width="75" bgcolor="#F0F0F0">
				<input type="text" name="mfg_lic_no"  id="mfg_lic_no"  size="25"  maxlength="20" value="<?php echo $_POST['mfg_lic_no']?>" />
				</td>
		  	</tr>
			<tr>
				<td width="50" bgcolor="#A3D3EF"><b>No of Shipping Cartons</b>
				<td width="15" bgcolor="#F0F0F0">
			 	<input type="text" name=ssccode  id="ssccode"  size="15" maxlength="15"  value="<?php echo $_POST['ssccode']?>"><b>Starting No</b>
			 	<input type="text" name=startno  id="startno"  size="15" maxlength="15"  value="<?php echo $_POST['startno']?>"><b>Ending No</b>
			 	<input type="text" name=endno  id="endno"  size="15" maxlength="15"  value="<?php echo $_POST['endno']?>" />
				</td>
		  	</tr>	
			<tr>
				<td width="100" bgcolor="#A3D3EF"><b>Shipping Mark</b></td>
			 	<td width="75" bgcolor="#F0F0F0">
			 	<input type="text" name=shpadd  id="shpadd"  size="46"  value="<?php echo $_POST['shpadd']?>" />			 
				</td>
			<!--<tr>-->
			<!--	<td colspan="2" align='center'>-->
			<!--	<input type="submit" name="getbarcode" id="getbarcode" value="Generate BarCode" style=" background:#3399FF; color:#fff; font-weight:bold; margin-top: 10px;padding: 4px 3px;border: medium none;" />-->
			<!--	</td>-->
			<!--</tr>-->
		</table>
		<center><span>
			  <input type="submit" name="getbarcode" id="getbarcode" value="Generate BarCode" style=" background:#3399FF; color:#fff; font-weight:bold; margin-top: 10px;padding: 4px 3px;border: medium none;" />
		</span></center>
		<br/>
	<?php 
     }else{
		 echo "<center><div style='background:#F0F0F0;width:250px;padding:3px;'>Product does not exist.</span></center>";
		}
   }
	?>
		</form>
			<div style="clear:both" ></div>
			</div>		
				<div id="Footer">
			<?php  require("include/footer.php");  ?>
				</div>
			</div>
</div>
<script src="js/jquery-1.12.4.min.js"></script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("barcodegenform");
  frmvalidator.addValidation("prodname","dontselect=0", "Please select a Product.");
  frmvalidator.addValidation("ph","req","Please enter Pharmacopoeia");
  frmvalidator.addValidation("batch_size","req","Please enter Batch Size");
  frmvalidator.addValidation("batch_size","maxlen=20","Max length is 20"); 
  frmvalidator.addValidation("batch_id","req","Please enter Batch ID");
  frmvalidator.addValidation("batch_id","maxlen=20","Max length is 20"); 
  // frmvalidator.addValidation("invoice_id","req","Please enter Invoice ID");
  // frmvalidator.addValidation("invoice_id","maxlen=20","Max length is 20");
  // frmvalidator.addValidation("mfgday","maxlen=2");
  // frmvalidator.addValidation("mfgday","req","Please enter Mfg Day - DD");
  // frmvalidator.addValidation("mfgday","numeric", "Numeric only");
  frmvalidator.addValidation("mfgmonth","maxlen=2");
  frmvalidator.addValidation("mfgmonth","req","Please enter Mfg Month - MM");
  frmvalidator.addValidation("mfgmonth","numeric", "Numerics  only");
  frmvalidator.addValidation("mfgyear","maxlen=4");
  frmvalidator.addValidation("mfgyear","req","Please enter Mfg Year - YYYY");
  frmvalidator.addValidation("mfgyear","numeric", "Numerics only");
  // frmvalidator.addValidation("expday","maxlen=2");
  // frmvalidator.addValidation("expday","req","Please enter Expiry Day - DD");
  // frmvalidator.addValidation("expday","numeric");
  frmvalidator.addValidation("expmonth","maxlen=2");
  frmvalidator.addValidation("expmonth","req","Please enter Expiry Month - MM");
  frmvalidator.addValidation("expmonth","numeric");
  frmvalidator.addValidation("expyear","maxlen=4");
  frmvalidator.addValidation("expyear","req","Please enter Expiry Year - YYYY");
  frmvalidator.addValidation("expyear","numeric");
  frmvalidator.addValidation("quantity","req","Please enter Quantity");
  // frmvalidator.addValidation("grs_wgt","req","Please enter Gross Weight");
  frmvalidator.addValidation("storage","req","Please enter storage");
  frmvalidator.addValidation("mfg_lic_no","req","Please enter MFG Licence.");
  frmvalidator.addValidation("ssccode","req","Please enter No of Shipping Cartons.");
  frmvalidator.addValidation("ssccode","numeric");
  frmvalidator.addValidation("shpadd","req","Please enter Shipping Mark.");
 </script>
</body>
</html>
