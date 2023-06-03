<?php
    include 'server/lib/security.php';

    error_reporting(0);
    
    session_start();

    if($_SESSION["is_auth"]){
        header("location:panel.php");
    }
    $sec = new sec();
    if(!$_SESSION["crsf"]) $_SESSION["crsf"] = $sec->generate_token();
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>EDS | Login</title>


    <!-- JQUERY -->
    <script src="client/js/lib/jquery.min.js"></script>

    <!-- WEBSITE CUSTOMIZATION -->
    <link rel="stylesheet" href="client/css/style/style.css">

    <!-- SCROLLBAR CUSTOMIZATION -->
    <link rel="stylesheet" href="client/css/style/scrollbar.css">

    <!-- BULMA CSS(RESPONSIVE FRAMEWORK) -->
    <link rel="stylesheet" href="client/css/bulma/bulma.min.css">

    <!-- BULMA-QUICKVIEW -->
    <script src="https://cdn.jsdelivr.net/npm/bulma-quickview@2.0.0/dist/js/bulma-quickview.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bulma-quickview@2.0.0/dist/css/bulma-quickview.min.css" rel="stylesheet">

    <!-- BUEFY -->
    <link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">

    <!-- BULMA DIVIDER -->
    <link href="https://cdn.jsdelivr.net/npm/bulma-divider@0.2.0/dist/css/bulma-divider.min.css" rel="stylesheet">

    <!-- BULMA PAGELOADER -->
    <link href="https://cdn.jsdelivr.net/npm/bulma-pageloader@0.3.0/dist/css/bulma-pageloader.min.css" rel="stylesheet">'
    

    <!-- ANIMATE.CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/f1ead0569a.js" crossorigin="anonymous"></script>

    <!-- FOOTER -->
    <script src="static/footer.js"></script>
  </head>
  <body>
    <div id="login_page">

        <!-- LOADER -->
        <div id="loading" class="pageloader is-bottom-to-top is-warning" :class="{ 'is-active': loading }"><span class="title"></span></div>
        <div id="main">
            <!-- LOGO -->
            <nav class="level">
                <a href="index.html" class="level-item has-text-centered mt-4 animate__animated animate__fadeInDown">
                    <img src="client/photos/logo.png" alt="" style="height: 60px;">
                </a>
            </nav>

            <section class="hero is-fullheight" style="height: 100% !important;">
                <div class="hero-body has-text-centered">
                    <div class="container">
                        <div class="columns is-centered">
                            <div class="column is-4-widescreen is-12-mobile is-8-tablet is-5-desktop is-centered">
                                <template v-if='mode == 0'>
                                    <h1 class="title has-text-centered animate__animated animate__fadeInUp">LOGIN</h1>
                                    <br>
                                    <b-steps type="is-danger" v-model="step_login" :animateInitially=true :animated=true :has-navigation=false mobile-mode=nul>
                                        <b-step-item step="1" label="Username"></b-step-item>
                                        <b-step-item step="2" label="Parola"></b-step-item>
                                    </b-steps>
                                    <form id="login_form" name="login_form">
                                        <div class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Username</label>
                                            <div class="control">
                                                <input @input="event => login_test(1,event.target.value)" class="input" type="text" placeholder="Username-ul tau" id="username_login" name="username_login">
                                            </div>
                                        </div>
                                        <div v-if="step_login >= 1" data-nav="next" class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Parola</label>
                                            <div class="control">
                                                <input @input="event => login_test(2,event.target.value)" class="input" type="password" placeholder="Parola ta" id="pass_login" name="pass_login">
                                            </div>
                                        </div>
                                        <b-field>
                                            <b-switch id="switch_mode" :value="false" type="is-warning animate__animated animate__fadeInDown" passive-type="is-danger" v-model='mode'>
                                                {{(mode == 1)?"Register":"Login"}}
                                            </b-switch>
                                        </b-field>
                                        <br>
                                        <button type="button" v-if="step_login >= 2" class="button is-danger coral animate__animated animate__fadeInUp" style="width:100%" @click="login()">Login</button>
                                    </form>
                                </template>
                                <template v-else='mode == 1'>
                                    <h1 class="title has-text-centered animate__animated animate__fadeInUp">REGISTER</h1>
                                    <br>
                                    <b-steps type="is-warning" v-model="step_reg" :clickable="false" :animateInitially=true :animated=true :has-navigation=false mobile-mode=nul>
                                        <b-step-item step="1" label="Cod"></b-step-item>
                                        <b-step-item step="2" label="Username"></b-step-item>
                                        <b-step-item step="3" label="Parola"></b-step-item>
                                        <b-step-item step="4" label="Nume"></b-step-item>
                                        <b-step-item step="5" label="Prenume"></b-step-item>
                                        <b-step-item step="6" label="Telefon"></b-step-item>
                                    </b-steps>
                                    <form id="register_form" name="register_form">
                                        <div class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Cod inregistrare</label>
                                            <div class="control">
                                                <input class="input" @input="event => register_test(1,event.target.value)" type="text" placeholder="Codul primit" id="code_reg" name="code_reg">
                                            </div>
                                            <br>
                                            <b-message v-if="step_reg == 0" type="is-danger" has-icon>
                                                Codul de inregistrare primit de la scoala de soferi
                                            </b-message>
                                        </div>
                                        <div v-if="step_reg >= 1" class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Username</label>
                                            <div class="control">
                                                <input class="input" @input="event => register_test(2,event.target.value)" type="text" placeholder="Username-ul tau" id="username_reg" name="username_reg">
                                            </div>
                                            <br>
                                            <b-message v-if="step_reg == 1" type="is-danger" has-icon>
                                                -Intre 4 si 16 caractere.
                                                <br>
                                                -Doar litere si cifre.
                                            </b-message>
                                        </div>
                                        <div v-if="step_reg >= 2" class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Parola</label>
                                            <div class="control">
                                                <input class="input" @input="event => register_test(3,event.target.value)" type="password" placeholder="Parola ta" id="pass_reg" name="pass_reg">
                                            </div>
                                            <br>
                                            <b-message v-if="step_reg == 2" type="is-danger" has-icon>
                                                -Intre 8 si 20 caractere.
                                                <br>
                                                -Doar litere si cifre.
                                            </b-message>
                                        </div>
                                        <div v-if="step_reg >= 3" class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Nume</label>
                                            <div class="control">
                                                <input class="input" @input="event => register_test(4,event.target.value)" type="text" placeholder="Numele tau" id="name_reg" name="name_reg">
                                            </div>
                                        </div>
                                        <div v-if="step_reg >= 4" class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Prenumele</label>
                                            <div class="control">
                                                <input class="input" @input="event => register_test(5,event.target.value)" type="text" placeholder="Prenumele tau" id="sname_reg" name="sname_reg">
                                            </div>
                                        </div>
                                        <div v-if="step_reg >= 5" class="field animate__animated animate__fadeInLeft">
                                            <label class="label">Numar de telefon</label>
                                            <div class="control">
                                                <input class="input" @input="event => register_test(6,event.target.value)" type="text" placeholder="Numarul de telefon" id="phone_reg" name="phone_reg">
                                            </div>
                                        </div>
                                        <b-field>
                                            <b-switch id="switch_mode" :value="false"
                                            type="is-warning animate__animated animate__fadeInDown" passive-type="is-danger" v-model='mode'>
                                                {{(mode == 1)?"Register":"Login"}}
                                            </b-switch>
                                        </b-field>
                                        <br>
                                        <button type="button" v-if="step_reg >= 5" class="button is-warning animate__animated animate__fadeInUp" style="width:100%" @click="register()">Register</button>
                                    </form>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- CRSF -->
    <input type="hidden" id="crsf" name="crsf" value="<?php echo $_SESSION['crsf']; ?>">

    <!-- VUE -->
    <script src="client/js/lib/vue.min.js"></script>

    <!-- BUEFY -->
    <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

    <!-- LISTENERS -->
    <script src="client/js/scripts/login.js"></script>

    
  </body>
</html>