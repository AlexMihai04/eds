var app = new Vue({
    el: '#main_page',
    data:{
        tablet : false,
        mobile:false
    },
    methods: {}
})







// RETURN THE PAGE WIDTH
function getWidth() {
    return $(window).width() + 17;
}


$( window ).on( "resize", function() {
    if(getWidth() > 769 && getWidth() <= 1023){
        app.tablet = true;
        app.mobile = false;
    }else if(getWidth() <= 769){
        app.mobile = true;
        app.tablet = false;
    }else{  
        app.mobile = false;
        app.tablet = false;
    }
});

$( document ).ready(function() {
    if(getWidth() > 769 && getWidth() <= 1023){
        app.tablet = true;
        app.mobile = false;
    }else if(getWidth() <= 769){
        app.mobile = true;
        app.tablet = false;
    }else{  
        app.mobile = false;
        app.tablet = false;
    }
});