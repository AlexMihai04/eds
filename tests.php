<?php
    include 'server/lib/security.php';

    error_reporting(0);
    
    session_start();

    if(!$_SESSION["is_auth"] || !$_SESSION["user"]){
        session_destroy();
        header("location:login.php");
        return;
    }

    $sec = new sec();
    if(!$_SESSION["crsf"]) $_SESSION["crsf"] = $sec->generate_token();


    include "server/lib/ranks.php";

    //CONFIG FILE
    $cfg = include "cfg/config.php";

    // RANKS MODULE
    $ranks = new ranks();
    $ranks->parse_config($cfg);
    // echo $ranks;

    if(!$ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"quizz")){
        header("location:qmanager.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <title>EDS | Chestionare</title>


        <!-- JQUERY -->
        <script src="client/js/lib/jquery.min.js"></script>

        <!-- WEBSITE CUSTOMIZATION -->
        <link rel="stylesheet" href="client/css/style/style.css">

        <!-- BULMA CSS(RESPONSIVE FRAMEWORK) -->
        <link rel="stylesheet" href="client/css/bulma/bulma.min.css">
        
        <!-- BUEFY -->
        <link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">

        <!-- BULMA DIVIDER -->
        <link href="client/css/bulma-divider/bulma-divider.min.css" rel="stylesheet">

        <!-- BULMA PAGELOADER -->
        <link href="https://cdn.jsdelivr.net/npm/bulma-pageloader@0.3.0/dist/css/bulma-pageloader.min.css" rel="stylesheet">

        <!-- ANIMATE ON SCROLL -->
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

        <!-- ANIMATE.CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

        <!-- FontAwesome -->
        <script src="https://kit.fontawesome.com/f1ead0569a.js" crossorigin="anonymous"></script>

        <!-- FOOTER -->
        <script src="static/footer.js"></script>
    </head>
    <body>
        <div id="test_page">
            <b-navbar>
                <template #brand>
                    <b-navbar-item>
                        <img src="client/photos/logo.png">
                    </b-navbar-item>
                </template>
        
                <template #end>
                    <b-navbar-item tag="div">
                        <div class="buttons">
                            <b-button type="is-danger" icon-left="home" tag="a" href="panel.php" icon-pack="fa" rounded></b-button>
                            <b-button type="is-info" icon-left="sign-out" tag="a" href="logout.php" icon-pack="fa" rounded></b-button>
                        </div>
                    </b-navbar-item>
                </template>
            </b-navbar>
            <section class="hero is-fullheight" style="height: 100% !important;">
                <div class="hero-body has-text-centered">
                    <div class="container">
                        <div class="columns is-centered">
                            <div class="column is-8-widescreen is-12-mobile is-8-tablet is-8-desktop is-centered" v-if="test_started">
                                <template>
                                    <h1 class="title has-text-centered">Timp ramas : {{parseInt(time_left/60)}} : {{time_left - (parseInt(time_left/60)*60)}}</h1>
                                    <br>
                                    <div class="card">
                                        <div class="card-content">
                                            
                                            <b-steps type="is-danger" size="is-small" v-model="step" :animateInitially=true :animated=true :has-navigation=false mobile-mode=nul>
                                                <b-step-item step="1" :type="{ 'is-success': prev_ans[0] == true,'is-warning': prev_ans[0] == false}"></b-step-item>
                                                <b-step-item step="2" :type="{ 'is-success': prev_ans[1] == true,'is-warning': prev_ans[1] == false}"></b-step-item>
                                                
                                                <b-step-item step="3" :type="{ 'is-success': prev_ans[2] == true,'is-warning': prev_ans[2] == false}"></b-step-item>
                                                
                                                <b-step-item step="4" :type="{ 'is-success': prev_ans[3] == true,'is-warning': prev_ans[3] == false}"></b-step-item>
                                                
                                                <b-step-item step="5" :type="{ 'is-success': prev_ans[4] == true,'is-warning': prev_ans[4] == false}"></b-step-item>
                                            </b-steps>
                                            <br>
                                            <h1>{{generated_test[quest_number].question}}</h1>
                                            <br>
                                            <br>
                                            <b-button :class="{ 'is-success': question_chose[0].chose}" @click="choose_quest(0)" expanded :outlined="!question_chose[0].chose">{{generated_test[quest_number].a}}</b-button>
                                            <br>
                                            <b-button :class="{ 'is-success': question_chose[1].chose}" @click="choose_quest(1)" expanded :outlined="!question_chose[1].chose">{{generated_test[quest_number].b}}</b-button>
                                            <br>
                                            <b-button :class="{ 'is-success': question_chose[2].chose}" @click="choose_quest(2)" expanded :outlined="!question_chose[2].chose">{{generated_test[quest_number].c}}</b-button>
                                            <br>
                                            <br>
                                            <div class="container">
                                                <div class="columns">
                                                    <div class="column">
                                                        <b-button type="is-danger" @click="finish_test()" expanded>Inchide</b-button>
                                                    </div>

                                                    <div class="column">
                                                        <b-button type="is-success"@click="next()" expanded>Urmatoarea intrebare</b-button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    
                                </template>
                            </div>
                            <div class="column is-4-widescreen is-12-mobile is-8-tablet is-5-desktop is-centered" v-if="!test_started">
                                <template>
                                    <!-- <h1 class="title has-text-centered">LOGIN</h1> -->
                                    <!-- <br> -->
                                    <b-steps type="is-danger" icon-pack="fa" icon-next="arrow-right" icon-prev="arrow-left" v-model="option_chose" :animateInitially=true :animated=true :has-navigation=true mobile-mode=nul clickable=true>
                                        <b-step-item step="1" label="Chestionarele tale">
                                            <div class="is-divider" data-content=""></div>
                                            <table class="table is-fullwidth">
                                                <template>
                                                    <tbody>
                                                        <tr v-for="(item,index) in render_list()" data-aos="fade-left" data-aos-duration="1200">
                                                            <td>{{index + 1}}</td>
                                                            <td>{{item.correct}} / {{item.total_q - 1}}</td>
                                                        </tr>
                                                    </tbody>
                                                </template>
                                            </table>
                                        </b-step-item>
                                        <b-step-item step="2" label="Incepe un chestionar">
                                            <br>
                                            <b-message type="is-warning" icon-pack="fa" has-icon>
                                                Doar chestionarele finalizate se salveaza!
                                            </b-message>
                                            <br>
                                            <div class="buttons">
                                                <b-button type="is-success" @click="start_test()" expanded>Incepe un chestionar</b-button>
                                            </div>
                                        </b-step-item>
                                    </b-steps>
                                    
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- AOS -->
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            AOS.init();
        </script>
        <!-- CRSF -->
        <input type="hidden" id="crsf" name="crsf" value="<?php echo $_SESSION['crsf']; ?>">
        <!-- VUE -->
        <script src="client/js/lib/vue.min.js"></script>

        <!-- BUEFY -->
        <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

        <!-- LISTENERS -->
        <script src="client/js/scripts/test.js"></script>
    </body>
</html>