<?php

    include 'server/lib/security.php';

    error_reporting(0);
    
    session_start();

    if(!$_SESSION["is_auth"] || !$_SESSION["user"]){
        session_destroy();
        header("location:login.php");
        return;
    }
    if($_SESSION["user"]["user_data"]["rank"] == "detinator_firma"){
        header("location:school.php");
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


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>EDS | Panel</title>


    <!-- JQUERY -->
    <script src="client/js/lib/jquery.min.js"></script>

    <!-- WEBSITE CUSTOMIZATION -->
    <link rel="stylesheet" href="client/css/style/style.css">

    <!-- SCROLLBAR CUSTOMIZATION -->
    <link rel="stylesheet" href="client/css/style/scrollbar.css">

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
    <div id="panel_page" class=".container.is-widescreen" >

        <!-- LOADER -->
        <div id="loading" class="pageloader is-bottom-to-top is-warning" :class="{ 'is-active': loading }"><span class="title"></span></div>
        <div id="main" v-if="(user.teacher.data.banned == 'false' || !user.teacher.data.banned) || user.user_data.rank != 'elev'">
            <!-- NAVBAR -->
            <b-navbar style="border-bottom: 0.25rem solid #f14668;">
                <template #brand>
                    <b-navbar-item>
                        <img src="client/photos/logo.png">
                    </b-navbar-item>
                </template>
                <template #start>
                    <b-navbar-item href="index.html">
                        Acasa
                    </b-navbar-item>
                    <b-navbar-item v-if="user.user_data.rank == 'elev'" href="tests.php">
                        Chestionare
                    </b-navbar-item>
                    <b-navbar-item v-if="user.user_data.rank == 'instructor'" href="qmanager.php">
                        Chestionare
                    </b-navbar-item>
                </template>
        
                <template #end>
                    <b-navbar-item tag="div">
                        <div class="buttons">
                            <?php if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"inspect_self_codes")){ ?><b-button v-if="user.user_data.rank == 'instructor'"type="is-warning" icon-left="key" icon-pack="fa" @click="view_codes = true">Vezi codurile</b-button><?php }?>
                            <?php if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"create_code")){ ?><b-button v-if="user.user_data.rank == 'instructor'"type="is-warning" icon-left="key" icon-pack="fa" @click="create_code()">Creeaza cod</b-button><?php }?>
                            <b-button type="is-info" icon-left="sign-out" tag="a" href="logout.php" icon-pack="fa"w></b-button>
                        </div>
                    </b-navbar-item>
                </template>
            </b-navbar>
            <br>

            <!-- ELEV PAGE VIEW -->
            <!-- <template v-if="user.user_data.rank == 'elev'"> -->
            <?php if($_SESSION["user"]["user_data"]["rank"] == "elev"){ ?>
                <div class="container.is-phone ml-4 mr-4">
                    <div class="columns">
                        <div class="column is-3-widescreen is-hidden-touch" data-aos="fade-right" data-aos-duration="1800" data-aos-offset="40">
                            <h3 class="title">Progres</h3>
                            <b-steps type="is-warning" clickable=false mobile-mode="null" v-model="user.data.step" size="is-small" label-position="right" :has-navigation=false vertical>
                                <b-step-item label="Cont inregistrat" disabled></b-step-item>
                                <b-step-item label="Inceput scoala" disabled></b-step-item>
                                <b-step-item label="Finalizare ore" disabled></b-step-item>
                                <b-step-item label="Inchidere dosar" disabled></b-step-item>
                                <b-step-item label="Examen teoretic" disabled></b-step-item>
                                <b-step-item label="Examen teoretic promovat" disabled></b-step-item>
                                <b-step-item label="Examen practic" disabled></b-step-item>
                                <b-step-item label="Examen practic promovat" disabled></b-step-item>
                            </b-steps>
                        </div>
                        <div class="column is-6-widescreen is-12-mobile is-6-tablet is-5-desktop">
                            <div class="container.is-widescreen">
                                <div class="columns">
                                    <div class="column is-12-widescreen">
                                        <!-- <h3 class="title">Profil</h3> -->
                                        <div class="card glow_fade" data-aos-once="true" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40">
                                            <div class="card-content">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <figure class="image is-48x48" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40">
                                                            <img src="client/photos/license.png" alt="Placeholder image">
                                                        </figure>
                                                    </div>
                                                    <div class="media-content" data-aos="fade-right" data-aos-duration="1800" data-aos-offset="40">
                                                        <p class="title is-4">{{user.user_data.name}} {{user.user_data.prename}}</p>
                                                        <p class="subtitle is-6">Instructor : {{user.teacher.user_data.name}} {{user.teacher.user_data.prename}} <br>Telefon : 0{{user.teacher.user_data.phone}}<br><template v-if="user.data.next_hour">Urmatoare ora : {{new Date(user.data.next_hour.date).toLocaleDateString('ro')}} | {{user.data.next_hour.hour}}</template></p>
                                                        <!-- <p class="subtitle is-6">Instructor : {{user.teacher.user_data.name}} {{user.teacher.user_data.prename}}</p> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="is-divider" data-content="Ore efectuate"></div>

                                <div class="columns">
                                    <div class="column is-12-widescreen">
                                        <template v-if="user.data.hours_done">
                                            <table class="table is-fullwidth" >
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Ora</th>
                                                    </tr>
                                                </thead>
                                                <template v-for="(item,index) in (user.data.hours_done!=null)?user.data.hours_done:([])">
                                                    <tbody>
                                                        <tr data-aos="fade-left" data-aos-duration="1200" data-aos-offset="40" data-aos-easing="ease-in-sine">
                                                            <td>{{new Date(item.date).toLocaleDateString('ro')}}</td>
                                                            <td>{{item.hour}}</td>
                                                        </tr>
                                                    </tbody>
                                                </template>
                                            </table>
                                        </template>
                                        <template v-else>
                                            <b-message type="is-warning" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40" icon-pack="fa" has-icon>
                                                Din pacate nu ai nici o ora de condus :( !
                                            </b-message>
                                        </template>
                                    </div>
                                </div>
                                <div class="is-divider" data-content="Chestionare realizate"></div>

                                <div class="columns">
                                    <div class="column is-12-widescreen">
                                        <template v-if="user.data.chestionare">
                                            <table class="table is-fullwidth" data-aos="fade-up" data-aos-duration="1800" data-aos-offset="40">
                                                <thead>
                                                    <tr>
                                                    <th>Data</th>
                                                    <th>Punctaj</th>
                                                    </tr>
                                                </thead>
                                                <template v-for="(item,index) in (user.data.chestionare!=null)?user.data.chestionare:([])">
                                                    <tbody>
                                                        <tr data-aos="fade-left" data-aos-duration="1200" data-aos-offset="40" data-aos-easing="ease-in-sine">
                                                            <td>{{new Date(item.date).toLocaleDateString('ro') }}</td>
                                                            <td>{{item.correct}} / {{item.total_q - 1}}</td>
                                                        </tr>
                                                    </tbody>
                                                </template>
                                            </table>
                                        </template>
                                        <template v-else>
                                            <b-message type="is-warning" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40" icon-pack="fa" has-icon>
                                                Nu ai rezolvat pana in acest moment nici un chestionar !
                                            </b-message>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3-widescreen is-12-mobile is-6-tablet is-5-desktop">
                            <div class="container.is-widescreen">
                                <div class="columns">
                                    <div class="column is-12-widescreen" data-aos="fade-left" data-aos-duration="1800" data-aos-offset="40">
                                        <template v-if="!has_hour_set() && (events.length > 0)">
                                            <center>
                                            <h3 class="title" style="font-size:1.25rem">Programeaza-ti urmatoarea ora</h3>
                                            <b-datepicker 
                                                inline 
                                                :min-date="date"
                                                :max-date="new Date(Date.now() + 7 * 24 * 60 * 60 * 1000)"
                                                icon-pack="fa"
                                                icon-right="arrow-right"
                                                icon-left="arrow-left"
                                                first-day-of-week="1"
                                                v-model="selected_date"
                                                :events="events"
                                                indicators="bars"
                                                >
                                            </b-datepicker>
                                            <template v-if="selected_date != null && ok_hours.length > 0">
                                                <b-field label="Ora">
                                                    <b-select placeholder="Select a character" v-model="selected_hour" required>
                                                        <template v-for="(item,index) in ok_hours">
                                                            <option :value="item">{{item}}</option>
                                                        </template>
                                                    </b-select>
                                                </b-field>
                                                <div class="buttons" data-aos="fade-left" data-aos-duration="1800" data-aos-offset="40" v-if="selected_hour != null">
                                                    <b-button type="is-success" @click="set_next_hour()" expanded>Confirma</b-button>
                                                </div>
                                            </template>
                                            </center>
                                        </template>
                                        <template v-else>
                                            <template v-if="events.length == 0 && !has_hour_set()">
                                                <b-message type="is-danger" icon-pack="fa" has-icon>
                                                    Din pacate instructorul tau nu are nici o ora libera in program !
                                                </b-message>
                                            </template>
                                            <template v-else>
                                                <b-message type="is-success" class="glow_greeni" icon-pack="fa" has-icon>
                                                    Ti-ai programat deja urmatoarea ora de condus !
                                                </b-message>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            <?php }?>
            <!-- </template>
             -->
            <!-- INSTRUCTOR PAGE VIEW -->
            <!-- <template v-if="user.user_data.rank=='instructor'"> -->
            <?php if($_SESSION["user"]["user_data"]["rank"] == "instructor"){ ?>
                <div class="container.is-phone ml-4 mr-4">

                    <!-- COD REG -->
                    <div class="modal" :class="{ 'is-active': set_cod_reg }">
                        <div class="modal-background"></div>
                        <div class="modal-card animate__animated animate__fadeInDown">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Seteaza cod inregistrare</p>
                                <button class="delete" aria-label="close" @click="set_cod_reg = false;selected_student.opened = true"></button>
                            </header>
                            <section class="modal-card-body">
                                <b-field :type="{'is-success' : saved}" label="Cod">
                                    <b-input value="Cod inregistrare" maxlength="15" v-model="cod_reg_in"></b-input>
                                </b-field>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button is-success" @click="set_codr()">Salveaza</button>
                            </footer>
                        </div>
                    </div>

                    <!-- SALA DATE -->
                    <div class="modal" :class="{ 'is-active': prog_sala }">
                        <div class="modal-background"></div>
                        <div class="modal-card animate__animated animate__fadeInDown">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Sala</p>
                                <button class="delete" aria-label="close" @click="prog_sala = false;selected_student.opened = true"></button>
                            </header>
                            <section class="modal-card-body">
                                <b-field :type="{'is-success' : saved}" label="Data">
                                    <b-input value="Data sala" maxlength="10" v-model="sala_data"></b-input>
                                </b-field>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button is-success" @click="set_psala(0)">Salveaza</button>
                                <button class="button is-danger" @click="set_psala(1)">Sterge sala</button>
                            </footer>
                        </div>
                    </div>

                    <!-- ORAS DATE -->
                    <div class="modal" :class="{ 'is-active': prog_oras }">
                        <div class="modal-background"></div>
                        <div class="modal-card animate__animated animate__fadeInDown">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Oras</p>
                                <button class="delete" aria-label="close" @click="prog_oras = false;selected_student.opened = true"></button>
                            </header>
                            <section class="modal-card-body">
                                <b-field :type="{'is-success' : saved}" label="Data">
                                    <b-input value="Data oras" maxlength="10" v-model="oras_data"></b-input>
                                </b-field>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button is-success" @click="set_poras(0)">Salveaza</button>
                                <button class="button is-danger" @click="set_poras(1)">Sterge orasul</button>
                            </footer>
                        </div>
                    </div>
                    
                    <!-- STUDENT PAGE -->
                    <div class="modal" :class="{ 'is-active': selected_student.opened }">
                        <div class="modal-background"></div>
                        <div class="modal-card animate__animated animate__fadeInDown">
                            <header class="modal-card-head">
                                <p class="modal-card-title">
                                    Elev : {{selected_student.user_data.name}} {{selected_student.user_data.prename}} <br>
                                    Telefon : 0{{selected_student.user_data.phone}}
                                    <br>
                                    <a style="font-size:1rem" @click="set_cod_reg = true;selected_student.opened = false" v-if="selected_student.data.cod_reg">Cod inregistrare : {{selected_student.data.cod_reg}}</a>
                                    <a style="font-size:1rem" @click="set_cod_reg = true;selected_student.opened = false" v-else>Seteaza cod inregistrare</a>
                                </p>
                                <button class="delete" aria-label="close" @click="deselect_student()"></button>
                            </header>
                            <section class="modal-card-body">
                                <!-- {{selected_student.data.hours_done}} -->
                                <div class="container">
                                    <div class="columns">
                                        <div class="column">
                                            <center><strong>Ore efectuate </strong>
                                                <br>
                                                <span v-if="selected_student.data.hours_done">{{selected_student.data.hours_done.length}} ore.</span>
                                                <span v-else>0 ore</span>
                                            <center>
                                        </div>
                                        <div class="column">
                                            <center>
                                                <strong>Sala </strong>
                                                <br>
                                                <a v-if="!selected_student.data.sala_p || selected_student.data.sala_p ==''" @click="prog_sala = true;selected_student.opened = false">Nu este setata</a>
                                                <!-- <a v-else> -->
                                                   <b-dropdown aria-role="list" expanded v-else>
                                                        <template #trigger="{ active }">
                                                            <a>
                                                                {{selected_student.data.sala_p}}
                                                                <span class="icon is-small">
                                                                    <i class="fas fa-arrow-down"></i>
                                                                </span>
                                                            </a>
                                                        </template>


                                                        <b-dropdown-item aria-role="listitem" @click="sala_d(true)">Luat</b-dropdown-item>
                                                        <b-dropdown-item aria-role="listitem" @click="sala_d(false)">Picat</b-dropdown-item>
                                                        <b-dropdown-item aria-role="listitem" @click="prog_sala = true;selected_student.opened = false">Modifica data</b-dropdown-item>
                                                    </b-dropdown>
                                                <!-- </a> -->
                                            <center>
                                        </div>
                                        <div class="column">
                                            <center>
                                                <strong>Orasul </strong>
                                                <br>
                                                <a v-if="!selected_student.data.oras_p || selected_student.data.oras_p ==''" @click="prog_oras = true;selected_student.opened = false">Nu este setat</a>
                                                <!-- <a v-else @click="prog_oras = true;selected_student.opened = false">{{selected_student.data.oras_p}}</a> -->
                                                <b-dropdown aria-role="list" expanded v-else>
                                                    <template #trigger="{ active }">
                                                        <a>
                                                            {{selected_student.data.oras_p}}
                                                            <span class="icon is-small">
                                                                <i class="fas fa-arrow-down"></i>
                                                            </span>
                                                        </a>
                                                    </template>


                                                    <b-dropdown-item aria-role="listitem" @click="oras_d(true)">Luat</b-dropdown-item>
                                                    <b-dropdown-item aria-role="listitem" @click="oras_d(false)">Picat</b-dropdown-item>
                                                    <b-dropdown-item aria-role="listitem" @click="prog_oras = true;selected_student.opened = false">Modifica data</b-dropdown-item>
                                                </b-dropdown>
                                            <center>
                                        </div>
                                    </div>
                                </div>
                                <div class="is-divider" data-content="Actiuni"></div>
                                <div class="container">
                                    <div class="columns">
                                        <div class="column">
                                            Urmatoarea ora : {{get_format_n()}}
                                        </div>
                                        <div class="column">
                                            <b-dropdown v-if="get_format_n() != 'nu este stabilit'" aria-role="list" expanded>
                                                <template #trigger="{ active }">
                                                    <b-button type="is-danger is-left is-outlined" expanded>
                                                        <span>Optiuni</span>
                                                        <span class="icon is-small">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                        </span>
                                                    </b-button>
                                                </template>


                                                <b-dropdown-item aria-role="listitem" @click="mark_hour_done(selected_student.user_data.id)">Marcheaza ca efectuat</b-dropdown-item>
                                                <b-dropdown-item aria-role="listitem" @click="delete_hour(selected_student.user_data.id)">Anuleaza ora</b-dropdown-item>
                                            </b-dropdown>
                                        </div>
                                    </div>
                                    <div class="columns">
                                        <div class="column">
                                            Elevul a terminat scoala ?
                                        </div>
                                        <div class="column">
                                            <b-dropdown aria-role="list" expanded>
                                                <template #trigger="{ active }">
                                                    <b-button type="is-left is-outlined" :type="{'is-success' : saved,'is-outlined' : saved || !saved,'is-danger' : !saved}" expanded>
                                                        <span v-if="!selected_student.data.schoold || selected_student.data.schoold == 'false'">Nu</span>
                                                        <span v-else>Da</span>
                                                        <span class="icon is-small">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </span>
                                                    </b-button>
                                                </template>


                                                <b-dropdown-item aria-role="listitem" v-if="!selected_student.data.schoold || selected_student.data.schoold == 'false'" @click="school_done(selected_student.user_data.id,true)">Da</b-dropdown-item>
                                                <b-dropdown-item aria-role="listitem" @click="school_done(selected_student.user_data.id,false)" v-else>Nu</b-dropdown-item>
                                            </b-dropdown>
                                        </div>
                                    </div>
                                    <div class="columns" v-if="selected_student.data && selected_student.data.sala_p">
                                        <div class="column">
                                            Elevul a luat sala
                                        </div>
                                        <div class="column">
                                            <b-button type="is-left is-outlined" :type="{'is-success' : saved,'is-outlined' : saved || !saved,'is-danger' : !saved}" expanded>
                                                <span v-if="!selected_student.data.sala_d || selected_student.data.sala_d == 'false'">Nu</span>
                                                <span v-else>Da</span>
                                            </b-button>
                                        </div>
                                    </div>
                                    <div class="columns" v-if="selected_student.data && selected_student.data.oras_p">
                                        <div class="column">
                                            Elevul a luat orasul
                                        </div>
                                        <div class="column">
                                            <b-button type="is-left is-outlined" :type="{'is-success' : saved,'is-outlined' : saved || !saved,'is-danger' : !saved}" expanded>
                                                <span v-if="!selected_student.data.oras_d || selected_student.data.oras_d == 'false'">Nu</span>
                                                <span v-else>Da</span>
                                            </b-button>
                                        </div>
                                    </div>
                                </div>
                                <div class="is-divider" data-content="Ore"></div>


                                <table class="table is-fullwidth">
                                    <thead>
                                        <tr>
                                        <th>Data</th>
                                        <th>Ora</th>
                                        </tr>
                                    </thead>
                                    <template v-for="(item,index) in selected_student.data.hours_done">
                                        <tbody>
                                            <tr>
                                            <td>{{new Date(item.date).toLocaleDateString('ro')}}</td>
                                            <td>{{item.hour}}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </table>
                                <div class="is-divider" data-content="Chestionare"></div>
                                <table class="table is-fullwidth">
                                    <thead>
                                        <tr>
                                        <th>Data</th>
                                        <th>Punctaj</th>
                                        </tr>
                                    </thead>
                                    <template v-for="(item,index) in selected_student.data.chestionare">
                                        <tbody>
                                            <tr>
                                            <td>{{new Date(item.date).toLocaleDateString('ro')}}</td>
                                            <td>{{item.correct}} / {{item.total_q - 1}}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button is-success" @click="deselect_student()">Inchide</button>
                            </footer>
                        </div>
                    </div>
                    <div class="modal" :class="{ 'is-active': all_hours_show }">
                        <div class="modal-background"></div>
                        <div class="modal-card" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Toate orele programate</p>
                                <button class="delete" aria-label="close" @click="all_hours_show = false"></button>
                            </header>
                            <section class="modal-card-body">
                                <div class="container">
                                    <div class="columns">
                                        <template v-for="(item,index) in all_hours_data">
                                            <div class="column">
                                                <table data-aos="fade-right" data-aos-duration="1800" data-aos-offset="40" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Data</th>
                                                            <th>Nume</th>
                                                            <th>Ora</th>
                                                        </tr>
                                                    </thead>
                                                    <template v-for="(k,i) in item">
                                                        <tbody>
                                                            <tr data-aos="fade-left" data-aos-duration="1800" data-aos-offset="40">
                                                                <td>{{index}}</td>
                                                                <td>{{k.student}}</td>
                                                                <td>{{k.hour}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </template>
                                                </table>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button is-success" @click="all_hours_show = false">Inchide</button>
                            </footer>
                        </div>
                    </div>
                    <div class="modal" :class="{ 'is-active': view_codes }">
                        <div class="modal-background"></div>
                        <div class="modal-card" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Codurile tale</p>
                                <button class="delete" aria-label="close" @click="view_codes = false"></button>
                            </header>
                            <section class="modal-card-body">
                                <table class="table is-fullwidth">
                                    <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Folosit</th>
                                            <th>Categorie</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <template v-for="(item,index) in codes_list">
                                        <tbody>
                                            <tr>
                                                <td>{{item.code}}</td>
                                                <td>{{item.used}}</td>
                                                <td>
                                                    <b-dropdown aria-role="list" expanded>
                                                        <template #trigger="{ active }">
                                                            <b-button type="is-danger is-left is-outlined">
                                                                <span>Categorie : {{item.category}}</span>
                                                                <span class="icon is-small">
                                                                    <i class="fas fa-arrow-down"></i>
                                                                </span>
                                                            </b-button>
                                                        </template>

                                                        <template v-for="(cats,index) in d_cat">
                                                            <b-dropdown-item v-if="item.category != cats.cat" aria-role="listitem" @click="change_code_cat(item.code,cats.cat)">{{cats.cat}}</b-dropdown-item>
                                                        </template>
                                                    </b-dropdown>
                                                </td>
                                                <td>
                                                    <button class="button is-danger is-outlined" @click="del_code(item.code)">
                                                        <span class="icon is-small">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button is-success" @click="view_codes = false">Inchide</button>
                            </footer>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-4-widescreen is-4-desktop is-4-tablet is-12-mobile">
                            <div class="card glow_fade" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40" style="min-height:100%;max-height:100%">
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <img src="client/photos/question_mark.svg" alt="Placeholder image">
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-4">{{user.user_data.name}} {{user.user_data.prename}}</p>
                                            <p class="subtitle is-6">Instructor</p>
                                        </div>
                                    </div>
                                    <center>
                                        <span class="button is-outlined is-fullwidth is-success">
                                            Data de astazi : {{new Date(date).toLocaleDateString('ro')}}
                                        </span>
                                    </center>
                                    <!-- <div class="is-divider" data-content="Informatii generale"></div> -->
                                    <div class="content">
                                        <b-table :data="teacher_info.data" :columns="teacher_info.columns"></b-table>
                                        <!-- <br> -->
                                        <!-- <time>{{ new Date(date).toLocaleDateString('ro') }}</time> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4-widescreen is-4-desktop is-4-tablet is-12-mobile">
                            <div class="card glow_fade" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40" style="min-height:100%;max-height:100%">
                                <div class="card-content" style="min-height:100%;max-height:100%">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <img src="client/photos/clock.svg" alt="Placeholder image">
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-4">Ore astazi</p>
                                            <p class="subtitle is-6">&nbsp;</p>
                                        </div>
                                    </div>
                                    <center>
                                    <span class="button is-outlined is-fullwidth is-success" @click="all_hours_show=true" expanded>
                                        Vizualizeaza toate orele programate
                                    </span></center>
                                    <!-- <div class="is-divider" data-content="Informatii generale"></div> -->
                                    <div class="content">
                                        <b-table :data="teacher_info.hours_today" :columns="teacher_info.columns"></b-table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4-widescreen is-4-desktop is-4-tablet is-12-mobile">
                            <div class="card glow_fade" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40" style="min-height:100%;max-height:100%;">
                            <!-- <center><b-tooltip type= "is-warning" label="Apasa pentru a modifica" always></b-tooltip></center> -->
                                <div class="card-content" style="min-height:100%;max-height:100%">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <img src="client/photos/car.svg" alt="Placeholder image">
                                            </figure>
                                        </div>    
                                        <div class="media-content">
                                            <p class="title is-4">Autovehicul</p>
                                            <p class="subtitle is-6">&nbsp;</p>
                                        </div>
                                    </div>
                                    <center>
                                        <span @click="change_plate()" class="button is-outlined is-fullwidth is-success" expanded>
                                            Modifica
                                        </span>
                                    </center>
                                    <!-- <div class="is-divider" data-content="Informatii generale"></div> -->
                                    <div class="content">
                                        <b-table :data="teacher_info.car_data" :columns="teacher_info.columns"></b-table>

                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    <!-- <div class="is-divider" data-content="Adauga ore libere"></div> -->
                    <div class="columns">
                        <div class="column is-4-widescreen is-4-desktop is-6-tablet is-12-mobile">
                            <div class="is-divider" data-aos="fade-up" data-aos-duration="1800" data-aos-offset="40" data-content="Ore libere"></div>
                            <br>
                            <b-button data-aos="fade-left" data-aos-duration="1800" data-aos-offset="40" type="is-success" @click="open_hmenu = true" expanded>Programator ore</b-button>
                        </div>
                        <div class="column is-8-widescreen is-8-desktop is-6-tablet is-12-mobile">
                            <div class="is-divider" data-aos="fade-up" data-aos-duration="1800" data-aos-offset="40" data-content="Elevii tai"></div>
                            <!-- <b-table :data="teacher_info.students_list" :selected.sync="selected_student.selected" :columns="teacher_info.columns" style="box-shadow: 0 0.5em 1em -0.125em rgba(10,10,10,.1), 2px 5px 0 3px rgba(10,10,10,.1);"></b-table> -->
                            <table data-aos="fade-right" data-aos-duration="1800" data-aos-offset="40" class="table is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nume</th>
                                        <th>Prenume</th>
                                        <center><th>Actiuni</th></center>
                                    </tr>
                                </thead>
                                <template v-for="(item,index) in teacher_info.students_list">
                                    <tbody>
                                        <tr data-aos="fade-left" data-aos-duration="1800" data-aos-offset="40">
                                            <td>{{item.data.user_data.id}}</td>
                                            <td>{{item.data.user_data.name}}</td>
                                            <td>{{item.data.user_data.prename}}</td>
                                            <td>
                                                <?php if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"see_st_data")){ ?>
                                                <button class="button is-success is-outlined" @click="selected_student.selected = item.data.user_data.id">
                                                    <span>Vizualizeaza</span>
                                                    <span class="icon is-small">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </button>
                                                <?php } ?>
                                                <button v-if="!item.data.data.data.banned || item.data.data.data.banned == 'false'" class="button is-danger is-outlined" @click="change_state_acc(item.data.user_data.id,true)">
                                                    <span class="icon is-small">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                </button>
                                                <button v-else class="button is-success is-outlined" @click="change_state_acc(item.data.user_data.id,false)">
                                                    <span class="icon is-small">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                        <div class="modal" :class="{ 'is-active': open_hmenu }">
                            <div class="modal-background"></div>
                            <div class="modal-card" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40">
                                <header class="modal-card-head">
                                    <p class="modal-card-title">Orele adaugate</p>
                                    <button class="delete" aria-label="close" @click="open_hmenu = false"></button>
                                </header>
                                <section class="modal-card-body">
                                    <div class="is-divider" data-content="Ore deja adaugate"></div>
                                    <br>
                                    <table class="table is-fullwidth">
                                        <thead>
                                            <tr>
                                                <!-- <th>{{user.data.hours_added}}</th> -->
                                                <th>Data</th>
                                                <th>Ora</th>
                                                <th>Programat</th>
                                                <th><center>Actiuni</center></th>
                                            </tr>
                                        </thead>
                                        <template v-for="(item,index) in user.data.hours_added" >
                                            <tbody>
                                                <tr>
                                                    <template v-if="show_hour(item)">
                                                        <td>{{new Date(item.date).toLocaleDateString('ro')}}</td>
                                                        <td>{{item.hour}}</td>
                                                        <td><template v-if="item.used && item.used != 'false'">Da</template><template v-else>Nu</template></th>
                                                        <td><center><button class="delete" style="background-color:rgb(241, 70, 104);" aria-label="close" @click="del_phour(item)"></button></center></td>
                                                    </template>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </table>
                                    <br>
                                    <div class="is-divider" data-content="Adauga ore"></div>
                                    <center>
                                        <b-datepicker 
                                            inline 
                                            :min-date="date"
                                            icon-pack="fa"
                                            icon-right="arrow-right"
                                            icon-left="arrow-left"
                                            first-day-of-week="1"
                                            v-model="added_hours.selected_date"
                                            >
                                        </b-datepicker>
                                    </center>
                                    <br>
                                    <b-field label="Ora"
                                        message="Scrie ora aici"
                                        horizontal>
                                        <b-input value="00:00" minlength="5" maxlength="5" v-model="added_hours.hour_input"></b-input>
                                    </b-field>
                                    <div class="buttons">
                                        <b-button type="is-success" @click="parse_hour()" expanded>Adauga ora</b-button>
                                    </div>
                                </section>
                                <footer class="modal-card-foot">
                                    <button class="button is-success" @click="open_hmenu = false">Inchide</button>
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            <?php } ?>
            <!-- </template> -->
        </div>
        <div v-else>
            <b-navbar style="border-bottom: 0.25rem solid #f14668;">
                <template #brand>
                    <b-navbar-item>
                        <img src="client/photos/logo.png">
                    </b-navbar-item>
                </template>
        
                <template #end>
                    <b-navbar-item tag="div">
                        <div class="buttons">
                            <b-button type="is-info" icon-left="sign-out" tag="a" href="logout.php" icon-pack="fa"w></b-button>
                        </div>
                    </b-navbar-item>
                </template>
            </b-navbar>
            <section class="hero is-fullheight" style="height: 100% !important;">
                <div class="hero-body has-text-centered">
                    <div class="container">
                        <div class="columns is-centered">
                            <div class="column is-6">
                                <article class="message is-danger">
                                    <div class="message-header">
                                        <p>Mesaj important</p>
                                        <!-- <button class="delete" aria-label="delete"></button> -->
                                    </div>
                                    <div class="message-body" id="banned_message"></div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
                                      
    <!-- TYPEWRITTER -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/TypewriterJS/2.13.1/core.min.js"></script>



    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- CRSF -->
    <input type="hidden" id="crsf" name="crsf" value="<?php echo $_SESSION['crsf']; ?>">

    <style>

    </style>

    <!-- VUE -->
    <script src="client/js/lib/vue.min.js"></script>


    <!-- BUEFY -->
    <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>


    <!-- LISTENERS -->
    <script src="client/js/scripts/panel.js"></script>

    
  </body>
</html>