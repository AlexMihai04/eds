<?php
    include 'server/lib/security.php';

    // error_reporting(0);
    
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

    if(!$ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"add_question")){
        header("location:tests.php");
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
        <style>
        .arrows {
            width: 60px;
            height: 72px;
            position: fixed;
            left: 10%;
            margin-left: -30px;
            top: 85%;
        }

        .arrows path {
            stroke: #FFDD57;
            fill: transparent;
            stroke-width: 5px;	
            animation: arrow 2s infinite;
            -webkit-animation: arrow 2s infinite; 
        }

        @keyframes arrow
        {
            0% {opacity:0}
            40% {opacity:1}
            80% {opacity:0}
            100% {opacity:0}
        }

        @-webkit-keyframes arrow /*Safari and Chrome*/
        {
            0% {opacity:0}
            40% {opacity:1}
            80% {opacity:0}
            100% {opacity:0}
        }

        .arrows path.a1 {
            animation-delay:-1s;
            -webkit-animation-delay:-1s; /* Safari 和 Chrome */
        }

        .arrows path.a2 {
            animation-delay:-0.5s;
            -webkit-animation-delay:-0.5s; /* Safari 和 Chrome */
        }

        .arrows path.a3 {	
            animation-delay:0s;
            -webkit-animation-delay:0s; /* Safari 和 Chrome */
        }
        </style>


        <div id="qpage">
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

            <!-- MODIFY QUESTION -->
            <div class="modal" :class="{ 'is-active': (selected_question != -1) }">
                <div class="modal-background"></div>
                <div class="modal-card animate__animated animate__fadeInDown">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Modifica intrebarea</p>
                        <button class="delete" aria-label="close" @click="selected_question = -1"></button>
                    </header>
                    <section class="modal-card-body">
                        <b-field :type="{'is-success' : saved}" v-if="selected_question != -1" label="Intrebare">
                            <b-input :value="quizz_questions[selected_question].question" maxlength="100" v-model="modifier.question"></b-input>
                        </b-field>
                        <b-field :type="{'is-success' : saved}" v-if="selected_question != -1" label="Raspuns A">
                            <b-input :value="quizz_questions[selected_question].a" maxlength="100" v-model="modifier.a"></b-input>
                        </b-field>
                        <b-field :type="{'is-success' : saved}" v-if="selected_question != -1" label="Raspuns B">
                            <b-input :value="quizz_questions[selected_question].b" maxlength="100" v-model="modifier.b"></b-input>
                        </b-field>
                        <b-field :type="{'is-success' : saved}" v-if="selected_question != -1" label="Raspuns C">
                            <b-input :value="quizz_questions[selected_question].c" maxlength="100" v-model="modifier.c"></b-input>
                        </b-field>
                        <br>
                        <div class="block">
                            <b-checkbox :type="{'is-success' : saved,'is-warning' : !saved}" v-model="modifier.ans"
                                native-value="a">
                                A
                            </b-checkbox>
                            <b-checkbox :type="{'is-success' : saved,'is-warning' : !saved}" v-model="modifier.ans"
                                native-value="b">
                                B
                            </b-checkbox>
                            <b-checkbox :type="{'is-success' : saved,'is-warning' : !saved}" v-model="modifier.ans"
                                native-value="c">
                                C
                            </b-checkbox>
                        </div>
                        <b-field label="Categorie de permis">
                            <b-select v-model="modifier.cat" icon="account">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="multi">E, BE, C1E, CE, D1E, DE</option>
                            </b-select>
                        </b-field>
                    </section>
                    <footer class="modal-card-foot">
                        <button class="button is-success" @click="save_data()">Salveaza</button>
                    </footer>
                </div>
            </div>

            <!-- ADD QUESTION -->
            <div class="modal" :class="{ 'is-active': add_question }">
                <div class="modal-background"></div>
                <div class="modal-card animate__animated animate__fadeInDown">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Adauga intrebare</p>
                        <button class="delete" aria-label="close" @click="add_question = false"></button>
                    </header>
                    <section class="modal-card-body">
                        <b-field :type="{'is-success' : saved}" label="Intrebare">
                            <b-input maxlength="100" v-model="modifier.question"></b-input>
                        </b-field>
                        <b-field :type="{'is-success' : saved}" label="Raspuns A">
                            <b-input maxlength="100" v-model="modifier.a"></b-input>
                        </b-field>
                        <b-field :type="{'is-success' : saved}" label="Raspuns B">
                            <b-input maxlength="100" v-model="modifier.b"></b-input>
                        </b-field>
                        <b-field :type="{'is-success' : saved}" label="Raspuns C">
                            <b-input maxlength="100" v-model="modifier.c"></b-input>
                        </b-field>
                        <br>
                        <div class="block">
                            <b-checkbox :type="{'is-success' : saved,'is-warning' : !saved}" v-model="modifier.ans"
                                native-value="a">
                                A
                            </b-checkbox>
                            <b-checkbox :type="{'is-success' : saved,'is-warning' : !saved}" v-model="modifier.ans"
                                native-value="b">
                                B
                            </b-checkbox>
                            <b-checkbox :type="{'is-success' : saved,'is-warning' : !saved}" v-model="modifier.ans"
                                native-value="c">
                                C
                            </b-checkbox>
                        </div>
                        <b-field label="Categorie de permis" :type="{'is-success' : saved}">
                            <b-select v-model="modifier.cat" icon="account">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="multi">E, BE, C1E, CE, D1E, DE</option>
                            </b-select>
                        </b-field>
                    </section>
                    <footer class="modal-card-foot">
                        <button class="button is-success" @click="add_qu()">Adauga</button>
                    </footer>
                </div>
            </div>
            <section class="hero is-fullheight" style="height: 100% !important;">
                <div class="hero-body has-text-centered">
                    <div class="container">
                        <div class="columns is-centered">
                            <div class="column is-8-widescreen is-12-mobile is-8-tablet is-5-desktop is-centered">
                                <div class="card glow_green">
                                    <div class="card-content">
                                        <div class="media">
                                            <div class="media-content">
                                                <p class="title is-4">Manager chestionare</p>
                                            </div>
                                        </div>

                                        <div class="content">
                                            <?php if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"add_question")){ ?>
                                            <b-button type="is-success" expanded @click="add_question = true">Adauga o intrebare noua</b-button>
                                            <?php } ?>
                                            <br>
                                            <b-field label="Categorie de permis">
                                                <b-select v-model="cat_sel" icon="account">
                                                    <option value="">Toate</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                    <option value="multi">E, BE, C1E, CE, D1E, DE</option>
                                                </b-select>
                                            </b-field>
                                            <table class="table is-fullwidth" style="box-shadow: 0 0.5em 1em -0.125em rgba(10,10,10,.1), 2px 5px 0 3px rgba(10,10,10,.1);">
                                                <thead>
                                                    <tr>
                                                        <th>Categorie</th>
                                                        <th>Intrebare</th>
                                                        <th>Actiuni</th>
                                                    </tr>
                                                </thead>
                                                <template v-for="(item,index) in quizz_questions">
                                                    <tbody>
                                                        <tr v-if="item.cat == cat_sel || cat_sel == ''" data-aos="fade-left" data-aos-duration="1200" data-aos-offset="100" data-aos-easing="ease-in-sine" data-aos-id="rendered">
                                                            <td>{{item.cat || 'Nedefinit'}}</td>
                                                            <td>{{item.question}}</td>
                                                            <td><center>
                                                                <?php if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"update_question")){ ?>
                                                                <button class="button is-success is-outlined" @click="selected_question = index">
                                                                    <span>Modifica</span>
                                                                    <span class="icon is-small">
                                                                        <i class="fas fa-edit"></i>
                                                                    </span>
                                                                </button>
                                                                <?php } ?>
                                                                <?php if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"delete_question")){ ?>
                                                                    <button class="button is-danger is-outlined" @click="confirm_modal(index)">
                                                                        <span class="icon is-small">
                                                                            <i class="fas fa-trash"></i>
                                                                        </span>
                                                                    </button>
                                                                <?php } ?>
                                                            </center></td>
                                                        </tr>
                                                    </tbody>
                                                </template>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


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
        <script src="client/js/scripts/qmanager.js"></script>
    </body>
</html>