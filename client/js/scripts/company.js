
Vue.use(Buefy);
Vue.use(VueGridLayout);

var app = new Vue({
    el: '#company_page',
    data:{},
    methods: {
    },
    mounted:function(){
        // new Typewriter('#text_typewriter', {
        //     strings: ['O modalitate interactiva de a invata sa conduci !', 'Foarte usor de folosit !', 'Accesibil de oriunde !'],
        //     autoStart: true,
        //     loop: true,
        //     delay:7
        // });
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
