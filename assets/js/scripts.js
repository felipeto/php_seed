jQuery(document).ready(function()
{
    jQuery(".view_password").mousedown(function(){
        jQuery(this).siblings("input").attr("type","text");
    });
    jQuery(".view_password").mouseup(function(){
        jQuery(this).siblings("input").attr("type","password");
    });
});

function create_message_alert(message,type)
{
    var type_class;
    switch(type)
    {
        case "error":
            type_class = "errormsg";
            break;
        case "ok":
            type_class = "successmsg";
            break;
        case "info":
            type_class = "infomsg";
            break;
        case "alert":
        case "warning":
            type_class = "alertmsg";
            break;
    }
    var error_msg = jQuery("<div />",{class: "style-msg "+type_class});
    //var dismiss_button = jQuery('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>');
    //error_msg.append(dismiss_button);
    var message_div = jQuery("<div />",{class: "sb-msg"});
    message_div.html(message).appendTo(error_msg);
    setTimeout(function(){
        error_msg.fadeOut(500,function(){
            error_msg.remove();
        });
    },7000);
    return error_msg;
}