

Vue.use(Buefy);


var app = new Vue({
    el: '#panel_page',
    data:{
        // VARIABLE FOR THE LOADING LISTENER
        loading:true,
        reload : false,
        saved:false,

        last_data:'',

        set_cod_reg:false,
        cod_reg_in:'',


        sala_data:'',
        prog_sala:false,

        oras_data:'',
        prog_oras:false,

        acc_setings:false, //TODO setari cont


        d_cat:[
            {"cat" : "A"},
            {"cat" : "B"},
            {"cat" : "C"},
            {"cat" : "E, BE, C1E, CE, D1E, DE"}
        ],

        profile_photo_up:null,

        // HOUR MENU FOR INSTRUCTOR
        open_hmenu:false,

        // CODES LIST
        codes_list:[],
        view_codes :false,

        counter:0,
        // HOURS CALENDAR
        date: new Date(),
        events: [],
        // NOTIF
        response:{
            "res" : -1,
            "message" : '',
            "duration": 2000
        },

        user:{},
        ready:false,
        selected_date:null,
        selected_hour:null,

        ok_hours:[],

        // TEACHER PAGE
        selected_student:{
            data:{},
            user_data:{},
            opened:false,
            selected:null
        },
        step:1,

        coming_hours:[],
        all_hours_data:{},
        all_hours_show:false,
        teacher_info:{},
        added_hours:{
            selected_date:null,
            hour_input:null,
            data:[],
            columns:[
                {
                    field: 'date',
                    label: ''
                },
                {
                    field: 'hour',
                    label: ''
                }
            ]
        }

    },
    methods: {
        del_phour(item){
            var k = -1;
            for(i = 0;i<this.user.data.hours_added.length && (k == -1);i++){
                var element = this.user.data.hours_added[i];
                if(element.date == item.date && element.hour == item.hour){
                    k = i;
                }
            }

            if(this.user.data.hours_added[k].used){
                set_udata("next_hour",null,this.user.data.hours_added[k].used_by);
            }
            this.user.data.hours_added.splice(k,1);
            setTimeout(() => {
                this.$buefy.toast.open({
                    message: `Ora a fost stearsa si anulata !`,
                    position: 'is-bottom',
                    type: 'is-success'
                })
                set_udata("hours_added",this.user.data.hours_added);
            }, 500);
        },
        show_hour(item){
            if(new Date().toLocaleDateString() <= new Date(item.date).toLocaleDateString()){
                return true;
            }
            return false;
        },
        has_hour_set(){
            if(this.user.data){
                if(this.user.data.next_hour){
                    if(this.user.data.next_hour.date){
                        return true;
                    }
                }
            }
            return false;
        },
        quiz_nr(){
            this.counter = this.counter + 1;
            return this.counter;
        },
        delete_hour(id){
            set_udata("next_hour",null,id);
            for(var i = 0;i<this.user.data.hours_added.length;i++){
                // console.log(this.user.teacher.data.hours_added[i]);
                if(this.user.data.hours_added[i].date == new Date(this.selected_student.data.next_hour.date).toLocaleDateString() && this.user.data.hours_added[i].hour == this.selected_student.data.next_hour.hour)
                {
                    this.user.data.hours_added[i].used = false;
                }
            }   
            var t = this.user.data.hours_added;
            const loadingComponent = this.$buefy.loading.open({
                container: null
            })
            setTimeout(() => {
                set_udata("hours_added",t);
                loadingComponent.close();
                setTimeout(() => {
                    this.$buefy.toast.open({
                        message: `Ora a fost anulata !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                    this.selected_student.data.next_hour = null;
                }, 150);
            }, 1000);
        },  
        mark_hour_done(id){
            if(!this.selected_student.data.hours_done) this.selected_student.data.hours_done = [];
            if(this.selected_student.data.next_hour != "" && this.selected_student.data.next_hour != null){
                this.selected_student.data.hours_done.push({"date" : this.selected_student.data.next_hour.date,"hour" : this.selected_student.data.next_hour.hour})
                set_udata("next_hour",null,id);
                this.selected_student.data.next_hour = null;
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                setTimeout(() => {
                    set_udata("hours_done",this.selected_student.data.hours_done,id);
                    setTimeout(() => {
                        
                        loadingComponent.close();
                        this.$buefy.toast.open({
                            message: `Salvat !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                    }, 300);
                }, 1000); 
            }else{
                this.$buefy.toast.open({
                    message: `Acest elev nu are o ora stabilita !`,
                    position: 'is-bottom',
                    type: 'is-danger'
                })
            }
        },
        school_done(id,state){
            set_udata("schoold",state,id);
            const loadingComponent = app.$buefy.loading.open({
                container: null
            })
            setTimeout(() => {
                loadingComponent.close();
                this.selected_student.data.schoold = state;
                this.saved = true;
                this.$buefy.toast.open({
                    message: `Salvat !`,
                    position: 'is-bottom',
                    type: 'is-success'
                })
                setTimeout(() => {
                    this.saved = false;
                }, 1000);
            }, 500);
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
                    // app.$buefy.dialog.alert({
                    //     title: 'Cod creat !',
                    //     message: `Cod : ${data}`,
                    //     type:"is-success",
                    //     confirmText: 'Inchide'
                    // })
                    // app.codes_list.push({"code":data,"used":0})
                }
            })
        },
        change_state_acc(id,state){
            const loadingComponent = this.$buefy.loading.open({
                container: null
            })
            
            set_udata("banned",state,id);
            // this.selected_student.data.banned = state;
            for(var i = 0;i<this.teacher_info.students_list.length;i++){
                if(this.teacher_info.students_list[i].data.user_data.id == id){
                    this.teacher_info.students_list[i].data.data.data.banned = state;
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
        change_plate(){
            this.$buefy.dialog.prompt({
                message: `Numarul masinii de inmatriculare`,
                type: 'is-warning',
                inputAttrs: {
                    placeholder: 'ex. PH82UDR',
                    maxlength: 8
                },
                trapFocus: true,
                onConfirm: (value) => {
                    set_udata("car_plate",value);
                    const loadingComponent = this.$buefy.loading.open({
                        container: null
                    })
                    setTimeout(() => {
                        loadingComponent.close()
                        setTimeout(() => {
                            this.$buefy.toast.open({
                                message: `Numarul masinii a fost setat la : ${value}`,
                                position: 'is-bottom',
                                type: 'is-success'
                            })
                            app.teacher_info.car_data[0].value =value;
                        }, 50);
                    }, 700);
                }
            })
        },
        deselect_student(){
            this.selected_student = {
                data:{},
                user_data:{},
                opened:false,
                selected:null
            };
        },
        parse_hour(){
            if(this.added_hours.selected_date && this.added_hours.hour_input){
                var ok = true; 

                // WE VERIFY IF THE DATE AND HOUR HAS ALREADY BEEN SELECTED
                this.added_hours.data.forEach(element => {
                    if(element.date == new Date(this.added_hours.selected_date).toLocaleDateString() && element.hour == this.added_hours.hour_input){
                        ok = false;
                    }
                });
                if(this.user.data.hours_added){
                    this.user.data.hours_added.forEach(element => {
                        if(element.date == new Date(this.added_hours.selected_date).toLocaleDateString() && element.hour == this.added_hours.hour_input){
                            ok = false;
                        }
                    }); 
                }else{
                    this.user.data.hours_added = [];
                }   
                if(ok){
                    if(this.user.data.hours_added){
                        this.user.data.hours_added.push({'date':new Date(this.added_hours.selected_date).toLocaleDateString(),'hour':this.added_hours.hour_input});
                        set_udata("hours_added",this.user.data.hours_added);
                    }else{
                        this.added_hours.data.push({'date':new Date(this.added_hours.selected_date).toLocaleDateString(),'hour':this.added_hours.hour_input});
                        set_udata("hours_added",this.added_hours.data);
                    }
                    reload();    
                    this.$buefy.toast.open({
                        message: `Ora adaugata !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                }else{
                    // reload();    
                    this.$buefy.toast.open({
                        message: `Ora aceasta este deja adaugata !`,
                        position: 'is-bottom',
                        type: 'is-danger'
                    })
                }
            }
        },
        get_format_n(){
            // console.log(this.selected_student);
            if(this.selected_student.opened && this.selected_student.data.next_hour != null && this.selected_student.data != "" && this.selected_student.data.next_hour.hour && this.selected_student.data.next_hour.date){
                return new Date(this.selected_student.data.next_hour.date).toLocaleDateString('ro') + " | " + this.selected_student.data.next_hour.hour
            }else{
                return "nu este stabilit"
            }
        },
        updater(thing,data){
            this[thing] = data;
        },
        set_next_hour(){
            if(this.selected_hour){
                // console.log(this.user.teacher.data.hours_added);
                var pos = -1;
                
                for(var i = 0;i<this.user.teacher.data.hours_added.length;i++){
                    // console.log(this.user.teacher.data.hours_added[i]);
                    if(this.user.teacher.data.hours_added[i].date == new Date(this.selected_date).toLocaleDateString() && this.user.teacher.data.hours_added[i].hour == this.selected_hour)
                    {
                        // console.log(this.user.teacher.data.hours_added[i]);
                        this.user.teacher.data.hours_added[i].used = true; 
                        this.user.teacher.data.hours_added[i].used_by = this.user.user_data.id;
                    }
                }   
                // set_udata("hours_added",this.user.teacher.data.hours_added,this.user.teacher.user_data.id);
                set_udata("next_hour",{'date' : new Date(this.selected_date).toLocaleDateString(),'hour' : this.selected_hour});
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                setTimeout(() => {
                    set_udata("hours_added",this.user.teacher.data.hours_added,this.user.teacher.user_data.id);
                    loadingComponent.close();
                    this.saved = true;
                    setTimeout(() => {
                        this.user.data.next_hour={'date' : new Date(this.selected_date).toLocaleDateString(),'hour' : this.selected_hour};
                        this.$buefy.toast.open({
                            message: `Salvat !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                    }, 150);
                    setTimeout(() => {
                        this.saved = false;
                    }, 700);
                }, 1000);
            }
        },
        set_codr(){
            if(this.cod_reg_in != ''){
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                set_udata("cod_reg",this.cod_reg_in,this.selected_student.user_data.id);
                this.selected_student.data.cod_reg = this.cod_reg_in;
                setTimeout(() => {
                    loadingComponent.close();
                    this.$buefy.toast.open({
                        message: `Salvat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                    this.saved = true;
                    setTimeout(() => {
                        this.saved = false;
                        this.set_cod_reg = false;
                        this.cod_reg_in = '';
                        this.selected_student.opened = true;
                    }, 500);
                }, 700);
            }
        },
        set_psala(type){
            if(type == 0 && this.sala_data != ''){
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                set_udata("sala_p",this.sala_data,this.selected_student.user_data.id);
                this.selected_student.data.sala_p = this.sala_data;
                setTimeout(() => {
                    loadingComponent.close();
                    this.$buefy.toast.open({
                        message: `Salvat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                    this.saved = true;
                    setTimeout(() => {
                        this.saved = false;
                        this.prog_sala = false;
                        this.sala_data = '';
                        this.selected_student.opened = true;
                    }, 500);
                }, 700);
            }else{
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                set_udata("sala_p",'',this.selected_student.user_data.id);
                this.selected_student.data.sala_p = '';
                setTimeout(() => {
                    loadingComponent.close();
                    this.$buefy.toast.open({
                        message: `Salvat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                    this.saved = true;
                    setTimeout(() => {
                        this.saved = false;
                        this.prog_sala = false;
                        this.sala_data = '';
                        this.selected_student.opened = true;
                    }, 500);
                }, 700);
            }
        },
        set_poras(type){
            if(type == 0 && this.oras_data != ''){
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                set_udata("oras_p",this.oras_data,this.selected_student.user_data.id);
                this.selected_student.data.oras_p = this.oras_data;
                setTimeout(() => {
                    loadingComponent.close();
                    this.$buefy.toast.open({
                        message: `Salvat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                    this.saved = true;
                    setTimeout(() => {
                        this.saved = false;
                        this.prog_oras = false;
                        this.oras_data = '';
                        this.selected_student.opened = true;
                    }, 500);
                }, 700);
            }else{
                const loadingComponent = this.$buefy.loading.open({
                    container: null
                })
                set_udata("oras_p",'',this.selected_student.user_data.id);
                this.selected_student.data.oras_p = this.oras_data;
                setTimeout(() => {
                    loadingComponent.close();
                    this.$buefy.toast.open({
                        message: `Salvat !`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                    this.saved = true;
                    setTimeout(() => {
                        this.saved = false;
                        this.prog_oras = false;
                        this.oras_data = '';
                        this.selected_student.opened = true;
                    }, 500);
                }, 700);
            }
        },
        change_code_cat(code,cat){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"server/methods/client_p.php",
                data:{
                    "crsf":$('#crsf').val(),
                    "action" : "modify_code",
                    "code":code,
                    "cat":cat
                },
                success:function (data)
                {
                    const loadingComponent = app.$buefy.loading.open({
                        container: null
                    })
                    setTimeout(() => {
                        loadingComponent.close();
                        for(i = 0;i<app.codes_list.length;i++){
                            if(app.codes_list[i].code == code){
                                app.codes_list[i].category = cat;
                            }
                        }
                        app.$buefy.toast.open({
                            message: `Categoria a fost modificata !`,
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                    }, 600);
                }
            })
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
                    console.log(data);
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
        sala_d(data){
            const loadingComponent = this.$buefy.loading.open({
                container: null
            })
            set_udata("sala_d",data,this.selected_student.user_data.id);
            this.selected_student.data.sala_d = data;
            setTimeout(() => {
                loadingComponent.close();
                this.$buefy.toast.open({
                    message: `Salvat !`,
                    position: 'is-bottom',
                    type: 'is-success'
                })
                this.saved = true;
                setTimeout(() => {
                    this.saved = false;
                }, 500);
            }, 700);
        },
        oras_d(data){
            const loadingComponent = this.$buefy.loading.open({
                container: null
            })
            set_udata("oras_d",data,this.selected_student.user_data.id);
            this.selected_student.data.sala_d = data;
            setTimeout(() => {
                loadingComponent.close();
                this.$buefy.toast.open({
                    message: `Salvat !`,
                    position: 'is-bottom',
                    type: 'is-success'
                })
                this.saved = true;
                setTimeout(() => {
                    this.saved = false;
                }, 500);
            }, 700);
        }
    },
    mounted:function() {
        setTimeout(() => {
            this.loading = false;
            new Typewriter('#banned_message', {
                strings: ['Ne pare rau dar instructorul de care tu apartineai a fost restrictionat de pe platforma ! Daca crezi ca este vorba despre o eroare , te rugam sa contactezi scoala de soferi de care tu apartii !'],
                autoStart: true,
                loop: true,
                delay:20
            });
        }, 600);
    },
    watch:{
        'selected_student.selected'(newStudent,oldStudent){
            // console.log(newStudent);
            if(newStudent){
                this.user.students.forEach(element => {
                    if(element.data){
                        if(element.user_data.id == newStudent) this.selected_student = {
                            data:element.data.data,
                            user_data:element.user_data,
                            opened:true,
                            selected:false
                        }; 
                    }
                });
            }
        },
        'selected_date'(newDate,oldDate){
            this.ok_hours = [];
            newDate = new Date(newDate).toLocaleDateString();
            if(this.user.teacher.data.hours_added){
                this.user.teacher.data.hours_added.forEach(element => {
                    if(element.date == newDate && (!element.used || element.used == 'false')){
                        this.ok_hours.push(element.hour);
                    }
                });
            }   
            // console.log(this.ok_hours);
        },
        'user.data.step'(newDate,oldDate){
            // console.log(this.user.data.step);
            this.user.data.step = parseInt(newDate);
        },
        'all_hours_show'(){
            console.log(this.all_hours_data);
        }
    }
})


function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

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

function verify(){
    if(app.user.data.step == null) set_udata("step",1);
    if(app.user.data.hours_done){
        if(app.user.data.hours_done.length > 0){
            set_udata("step",2);
        }else if(app.user.data.hours_done.length >= 15){
            set_udata("step",3);
        }else if(app.user.data.schoold && (app.user.data.schoold == 'true' || app.user.data.schoold == true)){
            set_udata("step",5);
        }else if(app.user.data.basic_test){ //BASIC TEST PROMOVATED
            set_udata("step",6);
        }else if(app.user.data.driving_test){
            set_udata("step",8);
        }
    }else{
        set_udata("step",1);
        app.user.data.step = 1;
    }
    app.ready = true;
}



// DEFAULT LOADER
$( document ).ready(function() {
    // console.log(Vue._installedPlugins);
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
            // console.log(data);
            data = JSON.parse(data); 
            console.log(data);
            if(!data.data) set_udata("1","1");
            app.updater('user',data); 

            setTimeout(() => {
                verify();
            }, 200);
        }
    })
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
    setTimeout(() => {
        var students_list = [];
        app.user.students.forEach(element => {
            if(element.data && element.user_data.name && element.user_data.id != app.user.user_data.id){
                if(element.data){
                    element.data.data = JSON.parse(element.data.data);
                    students_list.push({'data':element});
                    if(element.data.data.next_hour){
                        var today = new Date().toLocaleDateString('ro');
                        var his_hour = new Date(element.data.data.next_hour.date).toLocaleDateString('ro');
                        if(today == his_hour){
                            app.coming_hours.push({'text':element.user_data.name + " " + element.user_data.prename,'value' : element.data.data.next_hour.hour});
                        }
                        
                        app.all_hours_data[his_hour] = app.all_hours_data[his_hour] || []
                        app.all_hours_data[his_hour].push({"student" : element.user_data.name + " " + element.user_data.prename,"hour":element.data.data.next_hour.hour});
                    }
                }
            }
        });
        // console.log(students_list);
        // console.log(app.all_hours_data);

    
        app.teacher_info = {
            data:[
                {'text' : "Total cursanti ",'value':app.user.students.length - 1}
            ],
            students_list:students_list,
            car_data:[
                {'text' : "Numar inmatriculare",'value':app.user.data.car_plate || "Nesetat"}
            ],
            hours_today:app.coming_hours,
            columns: [
                {
                    field: 'text',
                    label: ''
                },
                {
                    field: 'value',
                    label: ''
                }
            ]
        }
        // const thisMonth = new Date().getMonth();
        // const thisYear = new Date().getFullYear();
        if(app.user.teacher.data != null){
            if(app.user.teacher.data.hours_added != null && app.user.teacher.data.hours_added != ""){
                app.user.teacher.data.hours_added.forEach(element => {
                    var hd = new Date(element.date);
                    if(hd >= app.date && !element.used){
                        app.events.push({
                            date: hd,
                            type: 'is-danger'
                        })
                    }
                });
            }
        }   
    }, 800);
    

    function repeat(){
        setInterval(function() {
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
                    // console.log(data);
                    if(data.user_data.rank == "instructor"){
                
                        data.students.forEach(element => {
                            element.data.data = JSON.parse(element.data.data);
                        });
                    }
                    app.updater('user',data); 
                }
            })
        }, 6000);
    }

    repeat();
});

function reload(){
    app.reload = true;
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
            // console.log(data);
            if(data.user_data.rank == "instructor"){
                
                data.students.forEach(element => {
                    element.data.data = JSON.parse(element.data.data);
                });
            }
            app.updater('user',data); 
        }
    })
    app.reload = false;
}





