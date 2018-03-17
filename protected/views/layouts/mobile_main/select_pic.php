<script type="text/javascript" src="js/jquery-1.7.2.js"></script>

<?php $name =  rand()."-".time().".jpg"; ?>


<?php echo $url; ?>

<img src="index.php?r=splashrinso/tab/saveFbPhoto&url=<?php echo $url; ?>&name=<?php echo $name; ?>" />

<script>

	
	$(document).ready(function() {
  
	window.opener.window.addFbRow('<?php echo $name; ?>','<?php echo $catName; ?>','fbPhoto');

	window.close();
 });
	
	
	
</script>	