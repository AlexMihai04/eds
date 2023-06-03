
Vue.use(Buefy);
// Vue.use(TroisJSVuePlugin);
var app = new Vue({
    el: '#qpage',
    data:{    
        quizz_questions:[],
        selected_question:-1,
        add_question:false,
        user: {},
        modifier:{
            "question":"",
            "a":"",
            'b':"",
            "c":"",
            "ans" : [],
            "cat":''
        },
        cat_sel:'',
        nr:0,
        saved:false,
        final_list : false      
    },
    methods: {
        updater(thing,data){
            this[thing] = data;
        },
        confirm_modal(i){
            this.$buefy.dialog.confirm({
                message: 'Esti sigur ca doresti sa stergi intrebarea ?',
                type:'is-success',
                onConfirm: () => this.delete_question(i)
            })
        },
        add_qu(){
            // console.log(this.modifier.length)
            if(this.modifier.question.length > 5 && this.modifier.a.length > 0 && this.modifier.b.length > 0 && this.modifier.c.length > 0 && this.modifier.ans.length > 0){
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:"server/methods/client_p.php",
                    data:{
                        "action" : "add_question",
                        "crsf":$('#crsf').val(),
                        "data":this.modifier
                    },
                    success:function (data)
                    {
                        app.$buefy.toast.open({
                            message: `Intrebarea a fost adaugata !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                        load_quest();
                    }
                })
            }
        },
        delete_question(i){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"server/methods/client_p.php",
                data:{
                    "action" : "delete_question",
                    "crsf":$('#crsf').val(),
                    'q_id':i
                },
                success:function (data)
                {
                    if(data){
                        app.$buefy.toast.open({
                            message: `Intrebarea a fost stearsa !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                        load_quest();
                    }
                }
            })
        },
        save_data(){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"server/methods/client_p.php",
                data:{
                    "action" : "update_question",
                    "crsf":$('#crsf').val(),
                    'data':this.modifier,
                    'q_id':this.selected_question
                },
                success:function (data)
                {
                    if(data){
                        app.$buefy.toast.open({
                            message: `Intrebarea a fost modificata !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                        app.quizz_questions[app.selected_question] = app.modifier;
                        app.saved = true;
                        setTimeout(() => {
                            app.saved = false;
                        }, 2000);
                    }
                }
            })
        }
    },
    watch:{
        'selected_question'(newValue,oldValue){
            if(newValue != -1) this.modifier=this.quizz_questions[newValue];
            else{
                this.modifier = {
                    "question":"",
                    "a":"",
                    'b':"",
                    "c":"",
                    "ans" : []
                }   
            }
        },
        'add_question'(newValue,oldValue){
            if(newValue == true){
                this.modifier = {
                    "question":"",
                    "a":"",
                    'b':"",
                    "c":"",
                    "ans" : []
                }
            }
        }
    }
})

function set_udata(field,data,id){
    var ids = -1;
    if(id) ids = id;
    // console.log(data);
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"server/methods/client_p.php",
        data:{
            "action" : "set_data",
            "crsf":$('#crsf').val(),
            'field':field,
            'data' : data,
            'id' : ids
        },
        success:function (data)
        {
            console.log(data);
        }
    })
}

// LOAD USER DATA

function load(){
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"server/methods/client_g.php",
        data:{
            "action" : "get_udata",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            data = JSON.parse(data); 

            app.updater('user',data); 
        }
    })
}


function load_quest(){
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"server/methods/client_g.php",
        data:{
            "action" : "get_chestionare",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            data = JSON.parse(data); 
            app.updater("quizz_questions",data);
        }
    })
}

$( document ).ready(function() {
    load();
    load_quest();
});

document.addEventListener('aos:in:rendered', ({detail}) => {
    // console.log(detail.getElementsByTagName("td")[0].innerHTML == app.quizz_questions.length)
    if(detail.getElementsByTagName("td")[0].innerHTML == app.quizz_questions.length) app.final_list = true;
    else app.final_list = false;
});
