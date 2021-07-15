$(function(){
    $(document).ready(function(){
        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

        //Convert to iCheck checkboxes
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $("img").error(function(){
            $(this).attr("src", baseUrl + "/uploads/default.png");
        });
    });
})