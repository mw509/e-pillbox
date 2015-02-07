<?php
# Description
# Processing Page for collecting user querries and requesting information from FDA APIs

require_once("epbengine/lib/CRequest.php");

#User Search Item
$request = new cRequest;
$Ingredient = $request->post('DrugName');
$PCode = $request->get('ProductCode');
unset($request);

#Check if customer request is from the search box or from a clicked link
if ($Ingredient) {
	$url = "http://pillbox.nlm.nih.gov/PHP/pillboxAPIService.php?ingredient=$Ingredient&key=671CGO9FHU";
} else if ($PCode) {
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
curl_close($ch);

$output = simplexml_load_string($ret);
$ResultCount = $output->pill->count(); 
?>
<html>
    <head>
        <title>e-Pill Box</title>
         
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
		<link rel='stylesheet'  href='style/style.css' type='text/css' media='screen' />
		<script>
		$(document).ready(function(){
			$('#userForm').submit(function(){
			 
				// show that something is loading
				$('#response').html("<b>Loading response...</b>");
				 
				/*
				 * 'post_receiver.php' - where you will pass the form data
				 * $(this).serialize() - to easily read form data
				 * function(data){... - data contains the response from post_receiver.php
				 */
				$.ajax({
				    type: 'POST',
				    url: 'process.php', 
				    data: $(this).serialize()
				})
				.done(function(data){
				     
				    // show the response
				    $('#response').html(data);
				     
				})
				.fail(function() {
				 
				    // just in case posting your form failed
				    alert( "Posting failed." );
				     
				});
		 
				// to prevent refreshing the whole page page
				return false;
		 
			});
		});
		</script>
    </head>
<body>
 
<!-- our form -->  
<form id='userForm'>
    <table align="center" width="80%">
    		<tr>
    			<td>		
    				<input type='text' name='DrugName' placeholder='Enter Drug Name' /> <br />
    			<td>
    		</tr>
    		<tr>
    			<td align="center">
    				<input type='submit' value='Search' /></div>
    			</td>
    		</tr>
    	</table>
</form>
 
<!-- where the response will be displayed -->
<div id="response">
<?php
if ($PCode != NULL) {
	//print "<b><i>$ResultCount</i></b><br />";
	$pill = $output->pill[0];
	$ProductCode = $pill->PRODUCT_CODE;
	$Author = $pill->AUTHOR;
	$Ingredients = $pill->INGREDIENTS;
	$InIngredients = $pill->SPL_INACTIVE_ING;
	
print
<<<HTML
<a href="?ProductCode=$ProductCode"><h2>$Author (Product Code: $ProductCode)</h2></a>
This pill contains $Ingredients with inactive ingredients of $InIngredients.<br />
<br />
HTML;
	}
?>
</div>
 

<script>
$(document).ready(function(){
    $('#userForm').submit(function(){
     
        // show that something is loading
        $('#response').html("<b>Searching Database...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'process.php', 
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            $('#response').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
});
</script>
 
</body>
</html>
