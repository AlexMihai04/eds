
Vue.use(Buefy);
// Vue.use(TroisJSVuePlugin);
var app = new Vue({
    el: '#test_page',
    data:{
        radioButton:' ',    
        step:0,

        
        option_chose:0,
        test_started:false,

        quest_number : 1,
        quizz_questions:[],

        generated_test:[],

        correct: 0,
        wrong : 0,
        sent:false,
        question_chose:[
            {'chose' : false},
            {'chose' : false},
            {'chose' : false}
        ],

        prev_ans:[
            {'ok' : false},
            {'ok' : false},
            {'ok' : false},
            {'ok' : false},
            {'ok' : false}
        ],

        time_left:1799,

        user: {}
    },
    methods: {
        render_list(){
            if(this.user.data){
                if(this.user.data.chestionare) return this.user.data.chestionare;
                else return {};
            }
        },
        start_test(){
            // var key_list = Object.keys(this.quizz_questions);
            // console.log(key_list);
            // var g = Math.floor(Math.random() * key_list.length);
            // console.log(g);
            // console.log(this.quizz_questions[key_list[g]]);
            this.test_started = true;
            var chosen = {};
            var done = 0;
            this.start_timer();
            var pos_quest = 0;
            var key_list = Object.keys(this.quizz_questions);
            for(var i=0;i<key_list.length;i++){
                if(this.quizz_questions[key_list[i]].cat == this.user.user_data.category) pos_quest++;
            }
            while(done <= 26 & done < key_list.length && done < pos_quest){
                var g = Math.floor(Math.random() * key_list.length);
                if(!chosen[g] && this.quizz_questions[key_list[g]].cat == this.user.user_data.category){
                    chosen[g] = true;
                    this.generated_test[++done] = this.quizz_questions[key_list[g]];
                }
            }
            console.log(this.generated_test);
        },
        finish_test(){
            this.test_started = false;
            this.question_chose = [
                {'chose' : false},
                {'chose' : false},
                {'chose' : false}
            ];
            this.prev_ans = [
                {'ok' : false},
                {'ok' : false},
                {'ok' : false},
                {'ok' : false},
                {'ok' : false}
            ];
            this.time_left = 1799;
            if(!this.user.data.chestionare){
                this.user.data.chestionare = [];
            }
                // this.sent = true;
            if(this.quest_number == this.generated_test.length) this.user.data.chestionare.push({"date" : new Date().toLocaleDateString(),"correct" : this.correct,'total_q':this.quest_number})
            set_udata("chestionare",this.user.data.chestionare);
            setTimeout(() => {
                load();
            }, 1000);
                // setTimeout(() => {
                //     this.sent = false;
                // }, 1000);
            this.quest_number = 1;
            this.correct = 0;
            this.wrong = 0;
            this.sent = false;
            this.step = 0;
        },
        next(){
            if(this.quest_number < 26 && this.quest_number < this.generated_test.length-1){
                var ok = true;
                var g = ['a','b','c'];
                var k = [];
                for(i = 0;i<3;i++){
                    if(this.question_chose[i].chose){
                        k.push(g[i]);
                    }
                }
                for(i = 0;i<3;i++){
                    if(k[i] != this.generated_test[this.quest_number].ans[i]) ok = false;
                }
                if(ok){
                    this.generated_test[this.quest_number].good = true;
                    this.prev_ans[this.step] = true;
                    this.correct++;
                }else{
                    this.wrong++;
                }
                this.step++;
                if(this.step > 4){
                    this.step = 0;
                    this.prev_ans = [
                        {'ok' : false},
                        {'ok' : false},
                        {'ok' : false},
                        {'ok' : false},
                        {'ok' : false}
                    ]
                }
                this.quest_number++;
                this.question_chose = [
                    {'chose' : false},
                    {'chose' : false},
                    {'chose' : false}
                ];
                console.log("test");
                // AOS.refreshHard();
            }else{
                if(this.correct > 21){
                    this.$buefy.toast.open({
                        message: `Felicitari , ai promovat examenul teoretic cu : ${this.correct} / ${this.generated_test.length}`,
                        position: 'is-bottom',
                        type: 'is-success'
                    })
                }else{
                    this.$buefy.toast.open({
                        message: `Din pacate ai picat examenul cu : ${this.correct} / ${this.generated_test.length-1}.`,
                        position: 'is-bottom',
                        type: 'is-danger'
                    })
                }
                if(!this.sent){
                    this.sent = true;
                    
                    setTimeout(() => {
                        this.sent = false;
                        this.finish_test();
                    }, 1000);
                }
            }
        },
        test_value(value){
            if(this.quest_number > value){
                console.log(this.generated_test[this.quest_number-value].good);
                return this.generated_test[this.quest_number-value].good;
            }
        },
        updater(thing,data){
            this[thing] = data;
        },
        choose_quest(nr){
            this.question_chose[nr].chose = !this.question_chose[nr].chose;
        },
        start_timer(){
            setInterval(() => {
                this.time_left = this.time_left-1;
                if(this.time_left == 0){
                    this.finish_test();
                }
            }, 1000);
        }
    },
    watch:{
        'test_started'(newValue,oldValue){
            if(newValue == 1){
                // console.log("test");
            }
        },
        'user'(newValue,oldValue){
            // if(!newValue.data.chestionare) this.user.data.chestionare = {};s
            // console.log(newValue.data.chestionare);
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
    // console.log(Vue);
    load();
    load_quest();
});
