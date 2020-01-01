<?php
if(isset($_POST["country"])){
	// Capture selected country
	$country = $_POST["country"];
	$state_province='';
	if( isset($_POST["state_province"]) ) {
	 	$state_province=$_POST["state_province"];
	 }
	// Define country and city array
	$countryArr = array(
"USA" => array(
				"Alaska",
				"Alabama",
				"Arkansas",
				"Arizona",
				"California",
				"Colorado",
				"Connecticut",
				"District of Columbia",
				"Delaware",
				"Florida",
				"Georgia",
				"Hawaii",
				"Iowa",
				"Idaho",
				"Illinois",
				"Indiana",
				"Kansas",
				"Kentucky",
				"Louisiana",
				"Massachusetts",
				"Maryland",
				"Maine",
				"Michigan",
				"Minnesota",
				"Missouri",
				"Northern Mariana Islands",
				"Mississippi",
				"Montana",
				"North Carolina",
				"North Dakota",
				"Nebraska",
				"New Hampshire",
				"New Jersey",
				"New Mexico",
				"Nevada",
				"New York",
				"Ohio",
				"Oklahoma",
				"Oregon",
				"Pennsylvania",
				"Rhode Island",
				"South Carolina",
				"South Dakota",
				"Tennessee",
				"Texas",
				"Utah",
				"Virginia",
				"Vermont",
				"Washington",
				"Wisconsin",
				"West Virginia",
				"Wyoming"
),
"Canada" => array(
				"Alberta",
				"British Columbia",
				"Manitoba",
				"New Brunswick",
				"Newfoundland and Labrador",
				"Northwest Territories",
				"Nova Scotia",
				"Nunavut",
				"Ontario",
				"Prince Edward Island",
				"Quebec",
				"Saskatchewan",
				"Yukon"
			),
"UK" => array(
				"Wales",
				"Scotland",
				"Northern Ireland",
				"England"
			),
);
	 $selected_value='';
	// Display city dropdown based on country name
	if($country !== 'Select'){
		echo "<label>State/Province</label>";
		echo "<select name='state_province' id='state_province' >";
		foreach($countryArr[$country] as $value){ ?>
			<option value="<?php echo $value; ?>" <?php if( $value==$state_province ){ echo 'selected="selected"';}?> ><?php echo  $value ;?></option>
		<?php }
		echo "</select>";
	} 
}
?>