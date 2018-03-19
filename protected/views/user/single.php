<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=470491453282522";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php 
	$fbshare_title = 'What is your Singapore Environment Story?';
    $fbshare_description = 'Tell us what you hope to see in Singapore’s environmental future!';
    $url = 'https://apps.facebook.com/sgenvironmentfuture?entryRedirect=1';
    $photo_caption = "A rare moment.";
    $image = "https://apps.circussocial.com". $this->themeUrl ."/images/share.png";
?>

<?php $this->renderPartial("fbjs", array('data'=>$data,'page'=>'')); ?>

<div class="wrapper">
    <div class="innerWrapper">
        <header class="inside_stories">
            <div class="col-md-6 baner_left"> 
                <a href="<?php echo Yii::app()->createUrl('tab/submit', array('signed_request'=> $this->signed_request)); ?>">
                    <img src="<?= $this->themeUrl; ?>/images/inner_logo_left.png">
                </a> 
            </div>
            <div class="col-md-6 baner_right"></div> 
        </header>
        <section class="inside_maincontainer"> 
            <div class="inside_content">
                <div class="backto-gallery">
                    <a href="<?php echo Yii::app()->createUrl('tab/index', array('signed_request'=> $this->signed_request)); ?>">
                        <button type="button" class="btn btn-gallery btn-primary">Back to Gallery</button>
                    </a>
                </div>                 

                <div class="inside_lft_blk GalleryLeft">                        

                    <figure>  
                        <a href="#"><img src="<?php echo $this->assetsUrl; ?>/entries/Invoker250.jpg" width="160"></a>
                    </figure>

                </div>  

                <div class="inside_rgt_blk">
                    <h2>Cafeteria Experience</h2>
                    <span>
                        <a target="_blank" href="https://www.facebook.com/100006481829705">
                            <img src="https://graph.facebook.com/100006481829705/picture" width="30" height="30">
                        </a>
                        <h4>
                            <a target="_blank" href="https://www.facebook.com/100006481829705">By Jines Farce</a>
                        </h4>
                    </span>
                    <p>I was in the cafeteria when I noticed...</p>
                </div>
                <div class="clear"></div>
            </div>

            <div class="inside_fbapp">
                <div class="inside_lft_blk"></div>
                <div class="inside_rgt_blk">
                    <div class="share-btns">
                        <?php if($data['vote']): ?>
                            <a href="#" id="voteMe"><img  width="37" height="29" src="<?= $this->themeUrl; ?>/images/favourite_inactive.png"></a>
                        <?php else: ?>
                            <a href="#"><img src="<?= $this->themeUrl; ?>/images/favourite.png"></a>
                        <?php endif; ?>
                        <a href="#" class="favourite" id="likesCount">342</a>
                        <a href="#" onclick="shareFeed('<?php echo $url ?>','<?php echo $image ?>','<?php echo $fbshare_title ?>','<?php echo $fbshare_description ?>','<?php echo $photo_caption; ?>');"><img src="<?= $this->themeUrl; ?>/images/share-fb.png"></a>
                    </div>
                    <div id="result"></div>
                    <div class="app_content">
                        <!--div class="fb-comments" data-href="https://apps.circussocial.com/neases/tab/single/1" data-numposts="3" data-width="560" data-colorscheme="light"></div-->
                    </div>
                </div>
                <div class="clear"></div>
            </div>   
        </section>

        <footer>
            <a href="#" data-toggle="modal" data-target="#terms">Terms & Conditions</a>
            <a href="#" data-toggle="modal" data-target="#privacy">Privacy Policy</a>
        </footer>

    </div>

</div>

<input type="hidden" id="entryId" value="1"/>
<?php echo $this->renderPartial("/tab/terms");?>
<?php echo $this->renderPartial("/tab/privacy");?>

<script type="text/javascript">

	function shareFeed(url,image,title,desc,image_caption) {
        /*
	    FB.ui({
	        method: 'feed',
	        name: title,
	        link: url,	    
            picture: image,
            description:desc, 
	        caption: 'https://apps.facebook.com/sgenvironmentfuture',
	    }, function (response){});  
        */

        var objectToLike = 'http://iheartdevs.com/apps/keeplaosclean';
        FB.login(function(){
            // Note: The call will only work if you accept the permission request
            FB.api('/me/feed',
                'POST',
                { link: objectToLike, name: "What is your Singapore Environment Story?",
                  picture: "http://iheartdevs.com/apps/keeplaosclean/user_assets/entries/Invoker250.jpg",
                  caption: "Help save Mother Earth.",
                  description: "Tell us what you hope to see in Singapore’s environmental future!" },
                function(response) {
                    if (!response) {
                        alert('Error occurred.');
                    } else if (response.error) {
                        document.getElementById('result').innerHTML =
                        'Error: ' + response.error.message;
                    } else {
                        document.getElementById('result').innerHTML = "Story Posted on your wall.";
                    }
                });
        }, {scope: 'publish_actions'});

	}

    $('.customScroll').enscroll({
        showOnHover: false,
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3'
    });

    $("#voteMe").click(function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('tab/vote', array('signed_request'=> $this->signed_request, 'active' => 1)); ?>",
            data: {
                entry: $("#entryId").val(),
            },   
            success: function(response){ 
                $("#likesCount").html(parseInt($("#likesCount").html()) + 1);
                $("#voteMe").replaceWith('<a href="#"><img src="<?= $this->themeUrl; ?>/images/favourite.png"></a>');
            }
        });
    });

    $(document).ready(function(){  
        var $players = $('.player');
        var $playButtons = $('.play');
        var $fullButtons = $('.fullscreen');
        var $pauseButton = $('.pause');
        var $toolbar     = $('.toolbar');      

        function onPlayClick(playButton, player, fullButton, pauseButton, optionsBar) {
            if (player.paused) {
                player.play();
            } else {
                player.pause();
            }

            if($(playButton).hasClass('video')) {
                $(playButton).fadeOut();   
                $(optionsBar).show();
                player.onended = function(e) {   
                    $(playButton).fadeIn();
                    $(optionsBar).fadeOut();
                };
            } else if($(playButton).hasClass('music')) {
                if($(playButton).hasClass('music2')) {
                    $(playButton).removeClass("music2");
                } else {
                    $(playButton).addClass("music2");
                    player.onended = function(e) {  
                        $(playButton).removeClass("music2");
                    };  
                }
            }

            $(fullButton).on('click', function(e){
                // Webkit for video elements only
                player.webkitEnterFullScreen();
                player.mozRequestFullScreen(); 
            });

            $(pauseButton).on('click', function(e) {
                player.pause();
                $(playButton).fadeIn();
                $(optionsBar).fadeOut();
            });
        }        

        for(var i = 0; i < $playButtons.length; i++) {
            var playButton  = $playButtons[i];
            var player      = $players[i];
            var fullButton  = $fullButtons[i];
            var pauseButton = $pauseButton[i];
            var optionsBar  = $toolbar[i];            

            $(playButton).click(
                onPlayClick.bind(null, playButton, player, fullButton, pauseButton, optionsBar)
            );
        }  

    });

</script>