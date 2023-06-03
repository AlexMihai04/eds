<?php

    include 'server/lib/security.php';

    error_reporting(0);
    
    session_start();

    if(!$_SESSION["is_auth"] || !$_SESSION["user"]){
        session_destroy();
        header("location:login.php");
        return;
    }
    if($_SESSION["user"]["user_data"]["rank"] != "detinator_firma"){
        header("location:panel.php");
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

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>EDS | School</title>


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
    <!-- <script src="static/footer.js"></script> -->
  </head>
  <body>

    <!-- LOADER -->
    <div id="school_page" class="container" >
      <div id="loading" class="pageloader is-bottom-to-top is-warning" :class="{ 'is-active': loading }"><span class="title"></span></div>
      <div id="main">
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
              <!-- <b-navbar-item v-if="user.user_data.rank == 'elev'" href="tests.php">
                  Chestionare
              </b-navbar-item> -->
              <!-- <b-navbar-item href="qmanager.php">
                  Chestionare
              </b-navbar-item> -->
          </template>
  
          <template #end>
              <b-navbar-item tag="div">
                  <div class="buttons">
                      <b-button type="is-warning" icon-left="key" icon-pack="fa" @click="view_codes = true">Vezi codurile</b-button>
                      <b-button type="is-warning" icon-left="key" icon-pack="fa" @click="create_code()">Creeaza cod</b-button>
                      <b-button type="is-info" icon-left="sign-out" tag="a" href="logout.php" icon-pack="fa"w></b-button>
                  </div>
              </b-navbar-item>
          </template>
        </b-navbar>
        <br>

        <div class="modal" :class="{ 'is-active': selected_teacher.opened }">
          <div class="modal-background"></div>
          <div class="modal-card" data-aos="fade-down" data-aos-duration="1800" data-aos-offset="40">
              <header class="modal-card-head">
                  <p class="modal-card-title">{{selected_teacher.user_data.name}} {{selected_teacher.user_data.prename}} <br>
                    <a style="font-size:1rem">Cont creat la data de : {{new Date(selected_teacher.user_data.date_created).toLocaleDateString('ro')}}</a>
                    
                  </p>
                  <button class="delete" aria-label="close" @click="deselect_teacher()"></button>
              </header>
              <section class="modal-card-body">
                <div class="container">
                  <div class="columns">
                      <div class="column">
                          <center><strong>Data crearii contului </strong>
                              <br>
                              <span>{{new Date(selected_teacher.user_data.date_created).toLocaleDateString('ro')}}</span>
                          <center>
                      </div>
                      <div class="column">
                          <center><strong>Numar autovehicul </strong>
                              <br>
                              <span>{{selected_teacher.data.car_plate || "Nu este setat"}}</span>
                          <center>
                      </div>
                  </div>
                </div>
                <div class="is-divider" data-content="Elevi"></div>
                <table class="table is-fullwidth">
                    <thead>
                        <tr>
                            <th>Nume</th>
                            <th>Prenume</th>
                            <th>Ore efectuate</th>
                        </tr>
                    </thead>
                    <template v-for="(item,index) in selected_teacher.ins_students">
                        <tbody>
                            <tr>
                                <td>{{item.user_data.name}}</td>
                                <td>{{item.user_data.prename}}</td>
                                <td>{{(item.data.hours_done || []).length}}</td>
                            </tr>
                        </tbody>
                    </template>
                </table>
              </section>
              <footer class="modal-card-foot">
                  <button class="button is-success" @click="deselect_teacher()">Inchide</button>
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
                              <th></th>
                          </tr>
                      </thead>
                      <template v-for="(item,index) in codes_list">
                          <tbody>
                              <tr>
                                  <td>{{item.code}}</td>
                                  <td>{{item.used}}</td>
                                  <td>
                                      <button class="button is-danger is-outlined" @click="del_code(item.code)"> <!--TODO functionality-->
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

          <div class="column">
            
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
                      <p class="subtitle is-6">Administrator</p>
                    </div>
                </div>
              </div>
            </div>

          </div>

        </div>
        <div class="columns">
          <div class="column">
            <table class="table is-fullwidth" >
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Nume</th>
                      <th>Prenume</th>
                      <th>Nr.elevi</th>
                      <td>Actiuni</td>
                  </tr>
              </thead>
              <template v-for="(item,index) in user.students"> <!--TODO functionality-->
                  <tbody>
                      <tr data-aos="fade-left" data-aos-duration="1200" data-aos-offset="40" data-aos-easing="ease-in-sine">
                          <td>{{item.user_data.id}}</td>
                          <td>{{item.user_data.name}}</td>
                          <td>{{item.user_data.prename}}</td>
                          <td>{{item.ins_students.length}}</td>
                          <td>
                            <button class="button is-success is-outlined" @click="selected_teacher.selected = item.user_data.id"> <!--TODO functionality-->
                              <span class="icon is-small">
                                  <i class="fas fa-edit"></i>
                              </span>
                            </button>
                            <button v-if="!item.data.banned || item.data.banned == 'false'" class="button is-danger is-outlined" @click="change_state_acc(item.user_data.id,true)">
                                <span class="icon is-small">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </button>
                            <button v-else class="button is-success is-outlined" @click="change_state_acc(item.user_data.id,false)">
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
        </div>
      </div>
      <br>

      <footer class="footer" data-aos="fade-up" data-aos-duration="600" style="padding:2rem 1rem 2rem !important" id="footer">
        <div class="content has-text-centered">
            <p>
                <strong>EDS</strong> creat de catre <strong>Udrescu Alexandru Mihai</strong>.
            </p>
            <a class="button is-outlined" href="mailto: udrescualexandrumihai@gmail.com">
                <span class="icon">
                  <i class="fab fa-google"></i>
                </span>
            </a>
            <a class="button is-outlined" href="https://instagram.com/_alexmihai_?igshid=NTc4MTIwNjQ2YQ==">
                <span class="icon">
                  <i class="fab fa-instagram"></i>
                </span>
            </a>
            <a class="button is-outlined" href="https://www.facebook.com/udr04">
                <span class="icon">
                  <i class="fab fa-facebook"></i>
                </span>
            </a>
        </div>
      </footer>
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
    <script src="client/js/scripts/school.js"></script>
  </body>
</html>