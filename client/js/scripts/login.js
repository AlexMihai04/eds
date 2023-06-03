// REGEX EXPRESSION 
const email_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
const code_regex = /^(\w[a-z]{5,6})@(\w[0-9]{3,4})@(\w[A-Z]{2,3})$/g;
const pass_regex = /^[a-zA-Z0-9]{8,20}$/;
const username_regex = /^[a-zA-Z0-9]{4,16}$/;

Vue.use(Buefy);

var app = new Vue({
    el: '#login_page',
    data:{
        loading:false,
        mode:false, // MODE =           0 : LOGIN | 1 : REGISTER
        step_login:0, // LOGIN STEPS =          0 : NOT ENTERED ANYTHING | 1 : ENTERED EMAIL | 2 : ENTERED BOTH
        step_reg:0,
        active:true
    },
    methods: {
        login(){
            var info = $("#login_form").serializeArray();
            console.log(info);
            if(this.step_login>=2){
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:"server/methods/login.php",
                    data:{
                        'crsf':$('#crsf').val(),
                        'username' : info[0].value,
                        'password' : info[1].value
                    },
                    success:function (data)
                    {
                        try{
                            console.log(data);
                            var data = JSON.parse(data);
                            
                            if(data.res == 1){
                                app.$buefy.toast.open({
                                    message: data.message,
                                    position: 'is-bottom',
                                    type: 'is-success'
                                })
                            }else{
                                app.$buefy.toast.open({
                                    message: data.message,
                                    position: 'is-bottom',
                                    type: 'is-danger'
                                })
                            }

                            if(data.res == 1){
                                setTimeout(() => {
                                    get_loader(3000);
                                    setTimeout(() => {
                                        window.location.href = "panel.php";
                                    }, 1500);
                                }, 800);
                            }
                        }catch(e){
                            console.log(e);
                        }
                    }
                })
            }
        },
        register(){
            var info = $("#register_form").serializeArray();
            if(this.step_reg >= 5){
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:"server/methods/register.php",
                    data:{
                        'crsf':$('#crsf').val(),
                        'code' : info[0].value,
                        'username' : info[1].value,
                        'password' : info[2].value,
                        'name' : info[3].value,
                        'prename' : info[4].value,
                        'phone_number':info[5].value
                    },
                    success:function (data)
                    {
                        try{
                            console.log(data);
                            var data = JSON.parse(data);

                            if(data.res == 1){
                                app.$buefy.toast.open({
                                    message: data.message,
                                    position: 'is-bottom',
                                    type: 'is-success'
                                })
                            }else{
                                app.$buefy.toast.open({
                                    message: data.message,
                                    position: 'is-bottom',
                                    type: 'is-danger'
                                })
                            }

                            if(data.response == 1){
                                setTimeout(() => {
                                    get_loader(3000);
                                    setTimeout(() => {
                                        window.location.href = "panel.php";
                                    }, 1500);
                                }, 800);
                            }
                        }catch(e){
                            console.log(data);
                        }
                    }
                })
            }
        },
        login_test(nr,text){
            if(nr == 1){ //EMAIL
                if(username_regex.test(text)) app.step_login = nr;
                else app.step_login = nr-1;
            }else if(nr == 2){ //PASSWORD
                if(text.length >= 8) app.step_login = nr;
                else app.step_login = nr-1;
            }
        },
        register_test(nr,text){
            if(nr == 1){
                if(code_regex.test(text)) app.step_reg = nr;
                else app.step_reg = nr-1;
            }else if(nr == 2){
                if(username_regex.test(text)) app.step_reg = nr;
                else app.step_reg = nr-1;
            }else if(nr == 3){
                if(pass_regex.test(text)) app.step_reg = nr;
                else app.step_reg = nr-1;
            }else if(nr == 4){
                if(username_regex.test(text)) app.step_reg = nr;
                else app.step_reg = nr-1;
            }else if(nr == 5){
                if(username_regex.test(text)) app.step_reg = nr;
                else app.step_reg = nr-1;
            }else if(nr == 6){
                if(username_regex.test(text)) app.step_reg = nr;
                else app.step_reg = nr-1;
            }
        }
    }
})

// SWITCH BUTTON LISTENER
$('#switch_mode').on('input',function(){
    get_loader(500);
    app.step_login = 0;
    app.step_reg = 0;
})



function get_loader(time){
    $("#main").hide();
    setTimeout(() => {
        setTimeout(() => {
            $("#main").show();
        }, 500);
        app.loading = true;
        setTimeout(() => {
            app.loading = false;
        }, time);
    }, 100);
}


// DEFAULT LOADER
$( document ).ready(function() {
    get_loader(700);
});





