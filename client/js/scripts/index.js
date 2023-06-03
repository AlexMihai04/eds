
Vue.use(Buefy);

const email_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
const username_regex = /^[a-zA-Z0-9]{4,16}$/;

var app = new Vue({
    el: '#index_page',
    data:{
        data:{
            "name":"",
            "prename":"",
            "email":"",
            "phone":"",
            "done" : false,
            "date" : new Date().toLocaleDateString("ro")
        },
        IDs:[],
        sec:'',
        sent:false
    },
    methods: {
        send_data(){
            if(username_regex.test(this.data.name) && username_regex.test(this.data.prename) && email_regex.test(this.data.email) && parseInt(this.data.phone) && this.data.phone.length == 10){
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:"server/methods/contact_form.php",
                    data:{
                        "data" : this.data
                    },
                    success:function (data)
                    {
                        app.$buefy.toast.open({
                            message: "Informatiile tale au fost salvate !",
                            position: 'is-bottom',
                            type: 'is-success'
                        })
                    }
                })
            }else{
                app.$buefy.toast.open({
                    message: "Te rugam sa verifici datele introduse !",
                    position: 'is-bottom',
                    type: 'is-danger'
                })
            }
        }
    },
    mounted:function(){
        new Typewriter('#text_typewriter', {
            strings: ['Cream o legatura stransa intre instructor si elev !', 'Usuram tot procesul elevilor si instructorilor !'],
            autoStart: true,
            loop: true,
            delay:7
        });
        AOS.init();
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
        
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    },
    watch:{}
})



$( document ).ready(function() {
    $('body').find("section").each(function(el){ app.IDs.push(this.id); });
});