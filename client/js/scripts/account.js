var app = new Vue({
    el: '#account_page',
    data:{
        tablet : false,
        mobile:false,
        account : 0,
        date:new Date(),
        loading:false
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
    setTimeout(() => {
        app.loading = true;
        setTimeout(() => {
            app.loading = false;
        }, 3000);
    }, 100);

});

// CALENDARS
var options = {
    minDate : app.date,
    weekStart:1,
    showHeader:false,
    displayMode:"inline",
    clearLabel:"Sterge",
    todayLabel:"Astazi"
}

// Initialize all input of type date
var calendars = bulmaCalendar.attach('[type="date"]',options);

// Loop on each calendar initialized
for(var i = 0; i < calendars.length; i++) {
	// Add listener to select event
	calendars[i].on('select', date => {
		console.log(date);
	});
}

function toMobile(){
    calendars = bulmaCalendar.attach('[type="date"]',options);
}


var quickviews = bulmaQuickview.attach();

var steps = bulmaSteps.attach();
