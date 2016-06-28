    $(document).ready(function(){
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];

        $(".day-button").click(function () {
            var date = this.value;
            var newDate = new Date(date*1000);
            var dateName = months[newDate.getMonth()]+" "+newDate.getDate()+", "+newDate.getFullYear();
            $("#selected-date").text(dateName);
            $.get('./posts', {date:date}, function(data) {
                $('#posts-body').html(data);
            });
        });

        // $('.privacy-toggle').click(function () {
        //     var a = this.id.split('-');
        //     $.get('/post/'+a[1]+'/privacy', function(data) {
        //         $("#privacy-"+a[1]).html(data);
        //     });
        // });

        $('.comment-form').submit(function () {
         var id = this.id;
         var comment = $('#comment'+id).val();
         if (comment != '') {
             console.log(id+"-"+comment);
             $.post('/post/'+id+'/comment',{comment:comment}, function(data) {
                var result = JSON.parse(data)
                $('#count'+id).text(result['comments']);
                $('#comments'+id).append(result['text']);
                $('#comments'+id).show('slow');
             })
             $('#comment'+id).val('');
         }
         return false;
        });

        $(".change-cover").click(function() {
            console.log(this.id);
            $.post('/background',{image:this.id} , function(data){
                console.log(data);
                var url = "/uploads/"+data;
                $('body').css('background-image', 'url(' +url+ ')');
            });
        });

        $(".remove-button").click(function() {
            var id = $("#modalLabel").text();
            console.log(id);
            if (this.value == 'post-remove') {
                console.log("post here");
                window.location = '/post/'+id+'/delete';
            } else {
                console.log("not a post");
                $.get('/post/'+id+'/delete', function(data){
                    $('#post'+id).hide(1000, function(){
                         $('#post'+id).remove();
                    });
                });
            }
        });

        //upload image preview
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-upload').attr('src', e.target.result);
                    $('#image-container').show('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#cancel-image').click(function(){
            $('#image-container').hide('slow');
            $('#image-upload').attr('src','#');
            $('input[type=file]').val('');
        });

        $('input[type=file]').change(function(e){
            if (this.value == '') {
                $('#image-container').hide('slow'); 
            } else {
                readURL(this);
            }
        });

        $(".like-button").click(function() {
            var id = this.value;
            $.get('/post/'+id+'/like',{id:id}, function(data){
                if(data == 0){
                    $("#like"+id).html(data);
                } else {
                    $("#like"+id).html(data);
                }
            });
        });

        $(".like-button-post").click(function() {
            var id = this.value;
            $.get(id+'/like',{id:id}, function(data){
                if(data == 0){
                    $("#like"+id).html(data);
                } else {
                    $("#like"+id).html(data);
                }
            });
        });

        $(".comments").toggle();

        $(".comments-button").click(function () {
            var id =  this.value;
            console.log("#comments"+id);
            $("#comments"+id).toggle('slow');
        });

        $('#image-container').hide();

        $("#write-cancel").click(function() {
            $("#write").toggle('slow');
            $("#calendar").toggle('slow');
            $('#image-upload').attr('src','#');
            $('#image-container').hide();
        });

        if ($("#error-count").val() > 0) {
            $("#calendar").hide();
        } else {
            $("#write").hide();
        }

        $("#write-button").click(function() {
            $("#write").toggle('slow');
            $("#calendar").toggle('slow');
        });

        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $(document).on('click', '.privacy-toggle', function() {
            var a = this.id.split('-');
                $.get('/post/'+a[1]+'/privacy', function(data) {
                    $("#privacy-"+a[1]).html(data);
                });
        });


        $(document).on('click', 'a[data-toggle=modal]', function() {
            $("#modalLabel").text(this.id);
            $("#")
            $("#deletePost").val(this.id);
        });
    
    });
