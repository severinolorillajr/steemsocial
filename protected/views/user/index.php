<?php
    //$this->renderPartial("fbjs", array('data'=>$data));
 ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12&appId=1103972806410212";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div class="wrapper">
    <div class="innerWrapper">
        <header>
            <div class="tellus">
                <p>
                    Welcome to SteemSocial!
                </p>
                <a href="<?php echo Yii::app()->createUrl('tab/login', array('page' => 'login', 'redirect' => 'false','signed_request'=> $this->signed_request)); ?>" class="text-center">
                    <img class="link" src="<?= $this->themeUrl; ?>/images/steemsocial2.png" style="width:50%">
                </a>
            </div>
        </header>

        <section class="maincontainer">
            <div class="refinements">
                <div class="pull-left RefinementPanel">
                    <ul>
                        <li><a class="navLink active" href="#">Feeds</a></li>
                        <li><a class="navLink" href="#">Recent Shares</a></li>
                        <li><a class="navLink" href="#">Most Shared</a></li>
                    </ul>
                </div>

                <div class="pull-right SearchPanel">
                    <form class="searchBox">
                        <input type="text" id="searchBox" value="">
                        <button type="button" id="searchButton"></button>
                    </form>
                </div>

            </div>

            <div class="GallerySection"></div>   
                     
            <div class="loadmore">
                <a class="red sm"><img src="<?= $this->themeUrl; ?>/images/loadmore.png"></a>
            </div>
        </section>

        <footer>
            <a href="#" class="modeDal" data-toggle="modal" data-target="#terms">Terms & Conditions</a>
            <a href="#" class="modeDal" data-toggle="modal" data-target="#privacy">Privacy Policy</a>
        </footer>
    </div>
</div>

<?php echo $this->renderPartial("/user/terms");?>
<?php echo $this->renderPartial("/user/privacy");?>


<input type="hidden" id="offset" value="2"/>
<input type="hidden" id="sort" value="<?php echo $data['sort']; ?>"/>
<input type="hidden" id="user" value="<?php echo $data['user']; ?>"/>

<script src="//cdn.steemjs.com/lib/latest/steem.min.js"></script>
<ul id="result"></ul>

<script type="text/javascript">
    $(".modeDal").click(function(e) {
        e.preventDefault();
        window.scrollTo(0,0);
    });

    $(".mouseWheelButtons .carousel").jCarouselLite({
        btnNext: ".mouseWheelButtons .next",
        btnPrev: ".mouseWheelButtons .prev",
        mouseWheel: true,
        auto: 5000,
        speed: 800,
        easing: 'linear',
        circular: true
    });  


    $('.customScroll').enscroll({
        showOnHover: false,
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3'
    });


    function getIndicesOf(searchStr, str, caseSensitive) {
        var startIndex = 0, searchStrLen = searchStr.length;
        var count = 0;
        var index, indices = [];
        if (!caseSensitive) {
            str = str.toLowerCase();
            searchStr = searchStr.toLowerCase();
        }

        while ((index = str.indexOf(searchStr, startIndex)) > -1) {
            indices.push(index);
            startIndex = index + searchStrLen;
            count++;
        }
        return count;
    }


    function singlePage(post_url) {
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('user/single', array()); ?>",
            data: {
                post_url: "https://steemit.com" + post_url + ".json",
                author: window.author,
                profile: window.profile
            },   
            success: function(response){
                $( "html" ).html(response);
            }
        });
      
    }

    function getPostData(post_url) {
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('user/getPostData', array()); ?>",
            data: {
                post_url: "https://steemit.com" + post_url + ".json",
                author: window.author,
                profile: window.profile
            },   
            success: function(response){
            	
		        FB.login(function(){
		            // Note: The call will only work if you accept the permission request
		            FB.api('/me/feed',
		                'POST',
		                { link: "https://steemit.com" + post_url, name: "What is your Singapore Environment Story?",
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
        });    
    }

      
    $(document).ready(function(){  

	    if (window.location.hash) {
		  	$(window.location.hash).modal('show');
		}
    
        var user = document.getElementById('user').value;

        var query = {
            tag: user,
            limit: 10
        };
    
        var profile;
        var rep;

        steem.api.getAccounts([user], function(err, result) {

            var data = result[0].json_metadata;
            rep  = result[0].reputation;

            profile = JSON.parse(data).profile.profile_image;
            window.profile = profile;
    
            steem.api.getDiscussionsByBlog(query, function (err, discussions) {

                if (!err) {
                    const rowLen = discussions.length;

                    discussions.map(function (discussion, i) {

                        if (discussion.author == user) {
                            console.log(discussion);

                            var url = discussion.body.match(/https?:\/\/\S+(?:png|jpe?g|gif)\S*/);
                            var img = url[0];

                            var json = {
                                profile: profile,
                                user: user,
                                rep: rep,
                                body: discussion.body,
                                img: img
                            }; 
                      
                            var response = "<div class='Gallery'><div class='pull-left GalleryLeft'><figure style='height: 260px'><a href=''><img src='" + img.replace(/<(?:.|\n)*?>/gm, '').replace('\">', '').replace(')', '') + "' width='260'></a></figure><div class='social-media-icons'><ul style='list-style-type:none'><li><a href='javascript:void(0)' onclick='getPostData(\"" + discussion.url + "\")'><img src='<?= $this->themeUrl; ?>/images/sm_facebook.png'/></a></li></ul></div></div><div class='pull-right GalleryRight'><h1 class='title'>" + discussion.title + "</h1><div class='auther'><figure><a href='https://www.steemit.com/@" + user + "' target='_blank'><img src='" + profile + "' width='40' height='40'></a></figure><span><a href='https://www.steemit.com/@" + user + "' target='_blank'>By " + discussion.author +"(" + steem.formatter.reputation(rep) + ")</a></span></div><p class='description'>" + discussion.body.substr(0, 300).replace(/<(?:.|\n)*?>/gm, '') + "...</p><a href='javascript:void(0)' class='green text-center'><img src='<?= $this->themeUrl; ?>/images/viewmore.png' onclick='singlePage(\"" + discussion.url + "\")'></a></div></div>";
                            
                            $(".GallerySection").append(response);  
                        }

                        if (rowLen === i + 1) {
                            window.permlink = discussion.permlink;
                            window.author = discussion.author;
                        }
                    });
                }

            });

        });


        $(".GallerySection").on( "click", ".shareFb img", function() {
            alert("haha");
            console.log($(this.next).data("json"));
            /*
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo Yii::app()->createUrl('user/single', array()); ?>",
                data: params,
                success: function(data) {
                    alert("Form submitted successfully.\nReturned json: " + data["json"]);
                }
            });*/
        });

        
        $(".loadmore").click(function(){

            steem.api.getDiscussionsByBlog({"tag": "shoganaii", "limit": 11, "start_permlink": window.permlink, "start_author": window.author}, function(err, discussions) {

               if (!err) {
                    const rowLen = discussions.length - 1;

                    discussions.slice(1).map(function (discussion, i) {

                        if (discussion.author == user) {

                            var url = discussion.body.match(/https?:\/\/\S+(?:png|jpe?g|gif)\S*/);
                            var img = url[0];
                        
                            var response = "<div class='Gallery'><div class='pull-left GalleryLeft'><figure style='height: 260px'><a href=''><img src='" + img.replace(/<(?:.|\n)*?>/gm, '').replace('\">', '').replace(')', '') + "' width='260'></a></figure></div><div class='pull-right GalleryRight'><h1 class='title'>" + discussion.title + "</h1><div class='auther'><figure><a href='https://www.steemit.com/@" + user + "' target='_blank'><img src='" + profile + "' width='40' height='40'></a></figure><span><a href='https://www.steemit.com/@" + user + "' target='_blank'>By " + discussion.author +"(" + steem.formatter.reputation(rep) + ")</a></span></div><p class='description'>" + discussion.body.substr(0, 300).replace(/<(?:.|\n)*?>/gm, '') + "...</p><div class='social-media-icons'><ul style='list-style-type:none'><li><a href='' class='shareFb'><img src='<?= $this->themeUrl; ?>/images/sm_facebook.png'/></a></li></ul></div><a href='javascript:void(0)' class='green text-center'><img src='<?= $this->themeUrl; ?>/images/viewmore.png' onclick='singlePage(\"" + discussion.url + "\")'></a></div></div>";
                            
                            $(".GallerySection").append(response);  
                        }

                        if (rowLen === i + 1) {
                            window.permlink = discussion.permlink;
                            window.author = discussion.author;
                        }
                    });
                }

            });
        }); // end loadMore


        /*

        $("#searchButton").click(function() {
            if($("#searchBox").val()) {
                $.ajax({
                    type: "POST",
                    url: "<?php //echo Yii::app()->createUrl('tab/search', array('signed_request'=> $this->signed_request)); ?>",
                    data: {
                        search: $("#searchBox").val(),
                    },   
                    success: function(response){   
                        $("#sort").val("search");   
                        $(".GallerySection").html('');
                        if(response.indexOf("GalleryLeft") > -1) {
                            $("#offset").val("2");
                            $(".navLink").removeClass("active");
                            $(".GallerySection").append(response);
                            var count = getIndicesOf("GalleryLeft", response, false);
                            if(count == 1) 
                                $(".loadmore").fadeOut(); 
                            else
                                $(".loadmore").fadeIn();
                        } else {
                            $(".GallerySection").append("<div class='emptyGallery'>There are no entries to be shown.</div>"); 
                            $(".loadmore").fadeOut(); 
                        }
                    }
                });
            }
        });

        $('form.searchBox').submit(false);

        $("#searchBox").keyup(function (e) {  
            e.preventDefault();
            if (e.keyCode == 13) {
                if($("#searchBox").val()) {
                    $.ajax({
                        type: "POST",
                        url: "<?php //echo Yii::app()->createUrl('tab/search', array('signed_request'=> $this->signed_request)); ?>",
                        data: {
                            search: $("#searchBox").val(),
                        },   
                        success: function(response){   
                            $("#sort").val("search");   
                            $(".GallerySection").html('');
                            if(response.indexOf("GalleryLeft") > -1) {
                                $("#offset").val("2");
                                $(".navLink").removeClass("active");
                                $(".GallerySection").append(response);
                                var count = getIndicesOf("GalleryLeft", response, false);
                                if(count == 1) 
                                    $(".loadmore").fadeOut(); 
                                else
                                    $(".loadmore").fadeIn();
                            } else {
                                $(".GallerySection").append("<div class='emptyGallery'>There are no entries to be shown.</div>"); 
                                $(".loadmore").fadeOut(); 
                            }
                        }
                    });
                }
            }
        });

        */
        
    });
</script>