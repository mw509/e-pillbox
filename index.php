<html>
    <head>
        <title>e-Pill Box</title>
         
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
		<link rel='stylesheet'  href='style/style.css' type='text/css' media='screen' />
		
		 <meta charset="utf-8">
		  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
		  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
		  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
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
    				<input type='text' name='DrugName' placeholder='Enter Name of Drug or Ingredient' /> <br />
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
<div id="response"></div>

<div id="dialog" title="Pill Details">
</div> 

<div align="right">
	About | Disclaimer | Contact 
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

function getMedDetails(prodCode)
{
	// show that something is loading
        showDialog("<b>Getting Pill Details...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'process.php', 
            data: 'ProductCode='+prodCode
        })
        .done(function(data){      
            // show the response
            //$('#response').html(data);
            updateDialog(data);
        })
        .fail(function() {
            // just in case posting your form failed
            alert( "Posting failed." );
        });
 
        // to prevent refreshing the whole page page
        return false;
}

function showDialog(msg) {
	$("#dialog").html(msg);
	$("#dialog").dialog();
};
function updateDialog(msg) {
	$("#dialog").html(msg);
};
</script>
 
</body>
</html>
