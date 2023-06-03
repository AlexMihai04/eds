Vue.use(Buefy);
// Vue.use(TroisJSVuePlugin);
var app = new Vue({
    el: '#school_page',
    data:{    
        loading:true,
        
        user: {},


        selected_teacher:{
            data:{},
            user_data:{},
            ins_students:{},
            opened:false,
            selected:null
        },
        
        
        codes_list:[],
        view_codes:false,


        saved:false   
    },

    methods: {
        updater(thing,data){
            this[thing] = data;
        },

        del_code(code){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"server/methods/client_p.php",
                data:{
                    "crsf":$('#crsf').val(),
                    "action" : "del_code",
                    "code":code
                },
                success:function (data)
                {
                    const loadingComponent = app.$buefy.loading.open({
                        container: null
                    })
                    setTimeout(() => {
                        loadingComponent.close();
                        var k = -1;
                        for(i = 0;i<app.codes_list.length;i++){
                            if(app.codes_list[i].code == code) k = i;
                        }
                        app.codes_list.splice(k,1);

                        app.$buefy.toast.open({
                            message: `Codul a fost sters !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                    }, 600);
                }
            })
        },

        create_code(){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"server/methods/client_p.php",
                data:{
                    "crsf":$('#crsf').val(),
                    "action" : "create_code"
                },
                success:function (data)
                {
                    const loadingComponent = app.$buefy.loading.open({
                        container: null
                    })
                    setTimeout(() => {
                        loadingComponent.close();
                        setTimeout(() => {
                            app.$buefy.dialog.alert({
                                title: 'Cod creat !',
                                message: `Cod : ${data}`,
                                type:"is-success",
                                confirmText: 'Inchide'
                            })
                            app.codes_list = app.codes_list || [];
                            app.codes_list.push({"code":data,"used":0,"category":"B"})
                        }, 50);
                    }, 600);
                }
            })
        },

        deselect_teacher(){
            this.selected_teacher = {
                data:{},
                user_data:{},
                opened:false,
                selected:null
            };
        },

        change_state_acc(id,state){
            const loadingComponent = this.$buefy.loading.open({
                container: null
            })
            
            set_udata("banned",state,id);
            // this.selected_student.data.banned = state;
            for(var i = 0;i<this.user.students.length;i++){
                // console.log(this.user.students[i])
                if(this.user.students[i].user_data.id == id){
                    this.user.students[i].data.banned = state;
                    break;
                }
            }
            setTimeout(() => {
                loadingComponent.close();
                if(state == true){
                    this.$buefy.toast.open({
                        message: `Contul a fost dezactivat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                }else{
                    this.$buefy.toast.open({
                        message: `Contul a fost activat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                }
                this.saved = true;
                setTimeout(() => {
                    this.saved = false;
                }, 500);
            }, 700);
        },
    },

    mounted:function() {
        setTimeout(() => {
            this.loading = false;
        }, 600);
    },

    watch:{
        'selected_teacher.selected'(newStudent,oldStudent){
            if(newStudent){
                this.user.students.forEach(element => {
                    if(element.data){
                        if(element.user_data.id == newStudent) this.selected_teacher = {
                            data:element.data,
                            user_data:element.user_data,
                            ins_students:element.ins_students,
                            opened:true,
                            selected:false
                        }; 
                    }
                });
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


function load_codes(){
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"server/methods/client_g.php",
        data:{
            "action" : "get_codes",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            // console.log(data);
            data = JSON.parse(data); 
            app.updater('codes_list',data); 
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
            console.log(data);
            data = JSON.parse(data); 
            // console.log(data);
            app.updater('user',data); 
        }
    })
}

$( document ).ready(function() {
    load();
    load_codes();
});