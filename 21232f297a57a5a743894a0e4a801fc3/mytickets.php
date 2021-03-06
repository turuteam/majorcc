<?php
require("./header.php");
if ($checkLogin) {
	if (isset($_GET["btnSearch"])) {
		$currentGet = "";
		$currentGet .= ($_GET["boxDob"]!="")?"boxDob=".$_GET["boxDob"]."&":"";
		$currentGet .= "txtBin=".$_GET["txtBin"]."&lstCountry=".$_GET["lstCountry"]."&lstState=".$_GET["lstState"]."&lstCity=".$_GET["lstCity"]."&txtZip=".$_GET["txtZip"];
		$currentGet .= ($_GET["boxSSN"]!="")?"&boxSSN=".$_GET["boxSSN"]:"";
		$currentGet .= "&btnSearch=Search&";
	}
	$searchBin = $db->escape($_GET["txtBin"]);
	$searchCountry = $db->escape($_GET["lstCountry"]);
	$searchState = $db->escape($_GET["lstState"]);
	$searchCity = $db->escape($_GET["lstCity"]);
	$searchZip = $db->escape($_GET["txtZip"]);
	$searchSSN = ($_GET["boxSSN"] == "on")?" AND card_ssn <> ''":"";
	$searchDob = ($_GET["boxDob"] == "on")?" AND card_dob <> ''":"";
	$sql = "SELECT count(*) FROM `".TABLE_CARDS."` WHERE card_status = '".STATUS_DEFAULT."' AND card_userid = ".$_SESSION["user_id"]." AND AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') LIKE '".$searchBin."%' AND ('".$searchCountry."'='' OR card_country = '".$searchCountry."') AND ('".$searchState."'='' OR card_state = '".$searchState."') AND ('".$searchCity."'='' OR card_city = '".$searchCity."') AND card_zip LIKE '".$searchZip."%'".$searchSSN.$searchDob;
	$totalRecords = $db->query_first($sql);
	$totalRecords = $totalRecords["count(*)"];
	$perPage = 20;
	$totalPage = ceil($totalRecords/$perPage);
	if (isset($_GET["page"])) {
		$page = $db->escape($_GET["page"]);
		if ($page < 1)
		{
			$page = 1;
		}
		else if ($page > $totalPage)
		{
			$page = 1;
		}
	}
	else
	{
		$page = 1;
	}
	//$sql = "SELECT *, AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number FROM `".TABLE_CARDS."` WHERE card_status = '".STATUS_DEFAULT."' AND card_userid = ".$_SESSION["user_id"]." AND AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') LIKE '".$searchBin."%' AND ('".$searchCountry."'='' OR card_country = '".$searchCountry."') AND ('".$searchState."'='' OR card_state = '".$searchState."') AND ('".$searchCity."'='' OR card_city = '".$searchCity."') AND card_zip LIKE '".$searchZip."%'".$searchSSN.$searchDob." ORDER BY card_id LIMIT ".(($page-1)*$perPage).",".$perPage;
	$sql = "SELECT *, AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') AS card_number FROM `".TABLE_CARDS."` WHERE card_status = '".STATUS_DEFAULT."' AND card_userid = ".$_SESSION["user_id"]." AND AES_DECRYPT(card_number, '".strval(DB_ENCRYPT_PASS)."') LIKE '".$searchBin."%' AND ('".$searchCountry."'='' OR card_country = '".$searchCountry."') AND ('".$searchState."'='' OR card_state = '".$searchState."') AND ('".$searchCity."'='' OR card_city = '".$searchCity."') AND card_zip LIKE '".$searchZip."%'".$searchSSN.$searchDob." ORDER BY card_buyTime DESC LIMIT ".(($page-1)*$perPage).",".$perPage;
	$listcards = $db->fetch_all_array($sql);
?>

<?php
	$sql = "SELECT DISTINCT card_country FROM `".TABLE_CARDS."` WHERE card_status = '".STATUS_DEFAULT."' AND card_userid = '".$_SESSION["user_id"]."'";
	$allCountry = $db->fetch_all_array($sql);
	if (count($allCountry) > 0) {
		foreach ($allCountry as $country) {
		}
	}
?>
				<div id="check_history">
					<div class="section_title">MY CARDS</div>
					<div class="section_page_bar">

<?php
	if ($totalRecords > 0) {
		echo "Page:";
		if ($page>1) {
			echo "<a class=\"input\" href=\"?".$currentGet."page=".($page-1)."\">&lt;</a>";
			echo "<a class=\"input\" href=\"?".$currentGet."page=1\">1</a>";
		}
		if ($page>3) {
			echo "...";
		}
		if (($page-1) > 1) {
			echo "<a class=\"input\" href=\"?".$currentGet."page=".($page-1)."\">".($page-1)."</a>";
		}
		echo "<input type=\"TEXT\" class=\"page_go\" value=\"".$page."\" onchange=\"window.location.href='?".$currentGet."page='+this.value\"/>";
		if (($page+1) < $totalPage) {
			echo "<a class=\"input\" href=\"?".$currentGet."page=".($page+1)."\">".($page+1)."</a>";
		}
		if ($page < $totalPage-2) {
			echo "...";
		}
		if ($page<$totalPage) {
			echo "<a class=\"input\" href=\"?".$currentGet."page=".$totalPage."\">".$totalPage."</a>";
			echo "<a class=\"input\" href=\"?".$currentGet."page=".($page+1)."\">&gt;</a>";
			
		}
	}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 250px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #222831;
  margin: auto;
  padding: 20px;
  border: 4px solid #191d22;
  width: 30%;
}

/* The Close Button */
.close {
  color: #f72b50;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.marg {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: 3px solid #ccc;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  outline: none;
}

.marg {
  border: 3px solid #555;
}

</style>

					</div>
					<div class="section_content">
					    													<center><input  type="submit" class="btn btn-del" id="myBtn" value="REPORT A PROBLEM" /></center>
					    													<p></p>

						<table class="content_table">
							<thead>
								<tr>
									<th>CARD NUMBER</th>
									<th>EXP</th>
									<th>CCV</th>
									<th>NAME</th>
									<th>ADRESSE</th>
									<th>CITY</th>
									<th>STATE</th>
									<th>ZIP</th>
									<th>COUNTRY</th>
									<th>RESULT</th>



								</tr>
							</thead>
							<tbody>
<?php
	if (count($listcards) > 0) {
		foreach ($listcards as $key=>$value) {
?>


								<tr class="formstyle">
									<td class="centered">
										<span><?=$value['card_number']?>

									</td>
									<?php
			switch ($value['card_check']) {
				case strval(CHECK_VALID):
					$value['card_checkText'] = "<span class=\"tag-success\">VALID</span>";
					break;
				case strval(CHECK_INVALID):
					$value['card_checkText'] = "<span class=\"tag-warn\">TIMEOUT</span>";
					break;
				case strval(CHECK_REFUND):
					$value['card_checkText'] = "<span class=\"tag-del\">INVALID - REFUNDED</span>";
					break;
				case strval(CHECK_UNKNOWN):
					$value['card_checkText'] = "<span class=\"tag-del\">UNKNOWN</span>";
					break;
				default :
					$value['card_checkText'] = "<span class=\"tag-success\"><a href=\"#\" onclick=\"checkCard('".$value['card_id']."')\">Check</a></span>";
					break;
			}
?>
									<td class="centered bold">
										<span><?=$value['card_month']?>/<?=$value['card_year']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_cvv']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_name']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_address']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_city']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_state']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_zip']?>
									</td>
									<td class="centered bold">
                                                                                <?=$value['card_country']?>
									</td>
									<td class="centered bold">
									       <span id="check_<?=$value['card_id']?>"><?=$value['card_checkText']?></span>
                                                                                <span><?=$value['card_comment']?></span>
										</td>
										<td class="centered bold">
                                                                                    <span><?=$value['card_comment']?></span>
                                                                                    
                                                                                    
                                                             	<td class="centered">
										</td>
                                                     <div id="myModal" class="modal">
                                                            <div class="modal-content">
    <span class="close">&times;</span>
<body class="agileits_w3layouts">
    <h1 style="color:white; class="agile_head text-center">REPORT A PROBLEM</h1>
    <div class="w3layouts_main wrap">
	    <form action="feedback.php" method="post" class="agile_form">
			 <ul class="agile_info_select">
				 <input type="radio" name="view" value="BAD RESULT" id="excellent" required> 
				 	  <label style="color:white; for="excellent">BAD RESULT</label>
				      <div class="check w3"></div>
				 
				 <input type="radio" name="view" value="EXPIRED CARD" id="good"> 
					  <label style="color:white; for="good">EXPIRED CARD</label>
				      <div class="check w3ls"></div>
				 
				 <input type="radio" name="view" value="INVALID CARD" id="neutral">
					 <label style="color:white; for="neutral">INVALID CARD</label>
				     <div class="check wthree"></div>
				 
				 <input type="radio" name="view" value="OTHER" id="poor"> 
					  <label style="color:white; for="poor">OTHER</label>
				      <div class="check w3_agileits"></div>
				 
			 </ul>	  
			<textarea placeholder="Other" class="marg" name="comments"></textarea>
			
			<input type="hidden" placeholder="Your Name (optional)" name="name" value="<?=$user_info["user_name"]?>" readonly="readonly" />
			<input type="hidden" placeholder="Your Email (optional)" name="email" value="<?=$user_info["user_mail"]?>" readonly="readonly"/>
			<input type="text" class="marg" placeholder="Card number" name="num" required=""/><br>
			<center><input type="submit" value="SEND REPORT" class="btn btn-del" /></center>
			<p></p>
			<center><p><strong style="color:#f72b50;font-size:12px;">*if you don't get any reponse that mean your report has been declined*</strong></p></center>

	  </form>
	</div>  </div>

</div>
                                        
                                        </td>
								</tr>
								<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<?php
		}

	}
	else {
?>
								<tr>
									<td colspan="3" class="red bold centered">
										No record found.
									</td>
								</tr>
<?php
	}
?>
							</tbody>
							
						</table>
					</div>
				</div>
<?php
}
else {
	require("./minilogin.php");
}
require("./footer.php");
?>