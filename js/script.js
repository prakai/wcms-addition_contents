$(function() {

    $(".addition_content > li > i.toolbar").click(function(){
        var id = 'addition_content_show_'+$(this).attr('value');
        if($(this).hasClass("content_show")){
            var content = 'show';
            $(this).removeClass('glyphicon-eye-close content_show').addClass('glyphicon-eye-open content_hide');
            $(this).attr('title', 'Hide content');
            $.post("",{
                fieldname: id,
                content: content
            });
        } else if($(this).hasClass("content_hide")){
            var content = 'hide';
            $(this).removeClass('glyphicon-eye-open content_hide').addClass('glyphicon-eye-close content_show');
            $(this).attr('title', 'Show content');
            $.post("",{
                fieldname: id,
                content: content
            });
        }else{
            var id = 'addition_content_'+$(this).attr('value');
            $.post("",{
                del_ac: id
            });
            new Promise(resolve => setTimeout(resolve, 5000));
            window.location.reload();
        }
    });

    $(".content_plus").click(function(){
        var id = 'addition_content_'+$(this).attr('value');
        var content = 'Empty content';
        $.post("",{
            add_ac: id,
            content: content
        });
        new Promise(resolve => setTimeout(resolve, 5000));
        window.location.reload();
    });
});
