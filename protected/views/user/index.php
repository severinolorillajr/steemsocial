<?php //$this->renderPartial("fbjs", array('data'=>$data)); ?>

<div class="wrapper">
    <div class="innerWrapper">
        <header>
            <div class="tellus">
                <p>
                    Welcome to SteemSocial!
                </p>
                <a href="<?php echo Yii::app()->createUrl('tab/login', array('page' => 'login', 'redirect' => 'false','signed_request'=> $this->signed_request)); ?>" class="text-center">
                    <img class="link" src="<?= $this->themeUrl; ?>/images/login.png" style="width:70%">
                </a>
            </div>
        </header>

        <section class="maincontainer">
            <div class="refinements">
                <div class="pull-left RefinementPanel">
                    <ul>
                        <li><a class="navLink active" href="#">Popular</a></li>
                        <li><a class="navLink" href="#">Recent</a></li>
                        <li><a class="navLink" href="#">My Submission</a></li>
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

<?php //echo $this->renderPartial("/tab/terms");?>
<?php //echo $this->renderPartial("/tab/privacy");?>


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

    $(".loadmore").click(function(){
        $.ajax({
            type: "GET",
            url: "<?php //echo Yii::app()->createUrl('tab/loadMore', array('signed_request'=> $this->signed_request)); ?>",
            data: {
                offset: $("#offset").val(),
                sort  : $("#sort").val(),
                search: $("#searchBox").val()
            },   
            success: function(response){
                console.log(response);
                $(".GallerySection").append(response);  
                $("#offset").val( parseInt($("#offset").val()) + 2 );
            }
        });
    });

    */
    
    $(document).ready(function(){  
    
        var user = document.getElementById('user').value;

    var query = {
        tag: user,
        limit: 10
    };
    
    console.log(query);

        var profile;

        steem.api.getAccounts([user], function(err, result) {
            var data = result[0].json_metadata;
            var profile = JSON.parse(data).profile.profile_image;
    
            steem.api.getDiscussionsByBlog(query, function (err, discussions) {
                if (!err) {
                console.log(discussions);
                    discussions.map(function (discussion) {
                        console.log(discussion);

                        if (discussion.author == user) {

                            //var url = discussion.body.match(/https?:\/\/[^\s]+/g);
                            var url = discussion.body.match(/https?:\/\/\S+(?:png|jpe?g|gif)\S*/);
                            var img = url[0];

                            console.log(url);
                        
                            var response="<div class='Gallery'><div class='pull-left GalleryLeft'><figure style='height: 260px'><a href=''><img src='" + img.replace(/<(?:.|\n)*?>/gm, '').replace('\">', '') + "' width='260'></a></figure></div><div class='pull-right GalleryRight'><h1 class='title'>" + discussion.title + "</h1><div class='auther'><figure><a href='https://www.steemit.com/@" + user + "' target='_blank'><img src='" + profile + "' width='40' height='40'></a></figure><span><a href='https://www.steemit.com/@" + user + "' target='_blank'>By " + discussion.author +"()</a></span></div><p class='description'>" + discussion.body.substr(0, 300).replace(/<(?:.|\n)*?>/gm, '') + "...</p><a href='' class='green text-center'><img src='<?= $this->themeUrl; ?>/images/viewmore.png'></a></div></div>";
                            
                            $(".GallerySection").append(response);  
                        }
                    });
                }
            });

        });


        
    });
</script>