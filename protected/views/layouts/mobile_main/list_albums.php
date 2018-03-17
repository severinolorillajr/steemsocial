
<style>
.album_listing a
{
	font-size:12px;
	font-weight:bold;
	text-decoration:none;
	padding:5px;
	color:#3B5998;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.album_listing a:hover
{
	color:#000000;
}
h1
{
	padding-left:20px;
	background-color:#3B5998;
	font-size:14px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	padding:5px;
	color:#FFFFFF;
	padding-left:15px;
	margin:5px;
}
</style>






    <h1 id="headtitle"> Choose an album </h1>
    
    <div class="album_listing" align="left" >
    <?php echo $this->renderPartial("/tab/facebook_photos/fb_albums_listing",array("albums"=>$albums)); ?>
    </div>
    <div id="photosListingArea" ></div>
    <div id="singlePhotoArea" ></div>
    
    
