<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=194576450615735&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php
# Page Description
# Processing Page for collecting user querries and requesting information from FDA APIs
# Developed at the Mest Hackathon 2015 (January) event in Accra.
# Also a practice project for the new developers on the team.
# Developed by: Emmanuel Eshun-Davies, Joshua Stone and Joel Stone.

require_once("epbengine/lib/CRequest.php");

#Define Default values
$ResultCount = 0;

#Request/Parsing
$request    = new cRequest;
$Ingredient = $request->post('DrugName');
$PCode      = $request->post('ProductCode');
unset($request);


#Check if someone is stupid enough to submit an empty querry
if($Ingredient || $PCode){
	#Check if request is from the search box or from a clicked link
	if ($Ingredient) { #This is to define the url for a Pill ingredient search
		$url = "http://pillbox.nlm.nih.gov/PHP/pillboxAPIService.php?ingredient=$Ingredient&key=671CGO9FHU";
	} else if ($PCode) { #This is for when a specific product is selected
		$url = "http://pillbox.nlm.nih.gov/PHP/pillboxAPIService.php?prodcode=$PCode&key=671CGO9FHU";
	}

// create curl resource 
$ch = curl_init(); 

// set url 
curl_setopt($ch, CURLOPT_URL, $url); 

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// $output contains the output string 
$ret = curl_exec($ch); 

$output = simplexml_load_string($ret);
$ResultCount = $output->pill->count();

#This is for a search for ingredients
if($Ingredient !=NULL){
	if ($output->record_count <= 0) {
		print("No Results Found for <b>\"$Ingredient\"</b>");
		die();
	} else {
		print "Displaying $ResultCount Results for <b>\"$Ingredient\"</b>";
	}
	
	foreach ($output->pill as $pill) {
		$ProductCode = $pill->PRODUCT_CODE;
		$Author = $pill->AUTHOR;
		$Ingredients = $pill->INGREDIENTS;
		$InIngredients = $pill->SPL_INACTIVE_ING;
	
print
<<<HTML
<a href="javascript:getMedDetails('$ProductCode');"><h2>$Author (Product Code: $ProductCode)</h2></a>
This pill contains $Ingredients with inactive ingredients of $InIngredients.<br />
<br />
HTML;
	}
}

#This is for when a specific product is selected
if($PCode != NULL){
	if ($output->record_count <= 0) {
		print("No Details Found.<b>$url</b>");
		die();
	}
	$pill = $output->pill[0];
	$ProductCode = $pill->PRODUCT_CODE;
	$Author = $pill->AUTHOR;
	$Ingredients = $pill->INGREDIENTS;
	$InIngredients = $pill->SPL_INACTIVE_ING;
	$Size = $pill->SPLSIZE;
	
print
<<<HTML
<h2>Displaying Details for Product Code $ProductCode</h2>
<table>
	<tr>
		<td><b>Author</b></td><td>$Author</td>
	</tr>
	<tr>
		<td><b>Ingredients</b></td><td>$Ingredients</td>
	</tr>
	<tr>
		<td><b>Inactive Ingredients</b></td><td>$InIngredients</td>
	</tr>
	<tr>
		<td><b>Size</b></td><td>$Size</td>
	</tr>
</table>
<br />
<a href="#"> Send a report/review about this Pill</a>
<br />
<a href="#"> Find a store near you</a>
<br />
<br />
<b>Share This Information</br>
<a href="https://www.facebook.com/sharer/sharer.php?app_id=194576450615735&u=http%3A%2F%2Fwww.searchengineinc.info%2F&display=popup&ref=plugin" target="_blank"><img src="images/fb.jpg" width="50px" /></a>
<a href="#" target="_blank"><img src="images/t.jpg" width="50px" /></a>
<a href="#" target="_blank"><img src="images/e.jpg" width="50px" /></a>
HTML;
	}

curl_close($ch); 
}

?>
