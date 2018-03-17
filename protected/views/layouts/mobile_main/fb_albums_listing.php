<ul>
    <?php
    foreach($albums['data'] as $album)
    {
	    echo '<li><a href="index.php?r=splashrinso/FacebookPhotos/listPhotos&albumID='.$album['id'].'&catName='.@$_REQUEST['catName'].'&signed_request='.$_REQUEST['signed_request'].'" onclick=showPhotos("'.$album['id'].'") >'.$album['name'].'</a></li>';
    }
    
    ?>
</ul>
