<?php
if (!$placeid) {

  slot('h1', '<h1> 不具合連絡システム | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/EquipmentManagement?placeid=1000079">野洲工場</a>
      <a class="blue" href="/EquipmentManagement?placeid=1000073">山崎工場</a>
      <a class="blue" href="/EquipmentManagement?placeid=1000125">NPG</a>
    </div>
    ';
  return;
}

use_javascript("hpro/handsontable.full.min.js");
use_javascript("hpro/languages/all.js");
use_stylesheet("hpro/handsontable.full.min.css");
use_javascript("jquery/jquery.ui.datepicker-ja.js");
use_javascript("jquery/jquery.ui.ympicker.js");

use_stylesheet("MissingDefect/validationEngine.jquery.css");
use_stylesheet("MissingDefect/print.min.css");
use_stylesheet("MissingDefect/bootstrap.min.css");
use_stylesheet("MissingDefect/bootstrap.min.css.map");
use_javascript("MissingDefect/print.min.js");

$btn = '<div id="header_sel" style="float:left; margin-top:1px;" >';
$btn .= '
        <input class="selgroups" type="text" id="place_sel" placeholder="設備検索" list="list_place" autocomplete="off">
        <datalist id="list_place">';
foreach ($obj_place as $name_place => $value_place) {
  foreach ($obj_place[$name_place] as  $name_position => $value_position) {
    foreach ($obj_place[$name_place][$name_position] as $item) {
      $btn .= "<option value='" . $item . "'>\n";
    }
  }
}
$btn .= '</datalist>
        <button type="button" class="btn_def" onclick="get_data();"> <i class="fa fa-search" aria-hidden="true" ></i>検索</button>
        <button type="button" class="btn_def" onclick="fb_refresh();"> <i class="fa fa-registered" aria-hidden="true" ></i>更新</button>
        <button type="button" class="btn_def" onclick="fb_graphic();"> <i class="fa fa-area-chart" aria-hidden="true" ></i>グラフ</button>
        <button type="button" class="btn_def" onclick="fb_viewdata();"> <i class="fa fa-database" aria-hidden="true" ></i>一覧</button>
        </div>
        <div style="float:left;">
        <label style="font-size: 0.8vw;" id="lb_person" onclick="fb_get_user();"></label>

        <input type="hidden" id="login_person"/>
        <input type="hidden" id="input_das_person"/>
        <input type="hidden" id="usercord"/>
        <input type="hidden" id="gp1"/>
        <input type="hidden" id="gp2"/>
        </div>
        <div style="float:right; padding-top:3px">
          <label style="font-size: 0.8vw;" id="lb_login_person"></label>
          <label style="font-size: 0.8vw;" style="color: khaki;">表示パターン</label>
          <input type="checkbox" id="checkbox_101" name="表示パターン" value="品名"/>
          <label style="font-size: 0.8vw;" class="radio9" for="checkbox_101" name="表示パターン">品名</label>
          <input type="checkbox" id="checkbox_102" name="表示パターン" value="発見日"/>
          <label style="font-size: 0.8vw;" class="radio9" for="checkbox_102" name="表示パターン">発見日</label>
        </div>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>
<html>
<html>

<head>
  <style>
    * {box-sizing: border-box;}
    body {font-size: 0.8vw;}
    .selgroups {float: left; margin-left: 5px; padding: 0px 0px 0px 10px;}
    .class_place {width: 10%; float: left; background-color: bisque;}
    .class_position {width: 10%; float: left; background-color: cornsilk;}
    .class_facility {width: 10%; float: left; background-color: beige;}
    .class_unit {width: 70%; float: left; padding: 10px; background-color: aliceblue; display: none;}
    .class_unit_input {width: 20%; float: left; padding: 5px; text-align: center;}
    .class_map {width: 60%; float: left; padding: 5px; text-align: center;}
    .class_picture {max-width: 100%;}
    .smallclass {width: 100%; padding: 5px;}
    .btn_place, .btn_position, .btn_facility { width: 100%; }
    .clicked {color: orange;}
  </style>
  <script>
    var placeid = '<?php echo $placeid; ?>';
    var list_place = <?php echo htmlspecialchars_decode($list_place); ?>;
    $('body').contextmenu(function() {
      return false;
    });
    $(window).load(function() {
      $('button').button();
      fb_refresh();
    });

    function get_data() {
      if ($('#place_sel').val() == "") {
        fb_refresh();
        return;
      }
      $('.class_place').html('');
      $('.class_position').html('');
      $('.class_facility').html('');
      Object.keys(list_place).forEach(function(name_place) {
        Object.keys(list_place[name_place]).forEach(function(name_position) {
          if (Array.isArray(list_place[name_place][name_position])) {
            list_place[name_place][name_position].forEach((value, key) => {
              if (value == $('#place_sel').val()) {
                $('.class_place').append('<div class="smallclass"><button class="btn_place clicked">' + name_place + '</button></div>');
                $('.class_position').append('<div class="smallclass"><button class="btn_position clicked">' + name_position + '</button></div>');
                $('.class_facility').append('<div class="smallclass"><button class="btn_facility clicked">' + value + '</button></div>');
              }
            });
          }
        });
      });
      $('button').button();
      $('.btn_facility').on('click', function() {
        fb_unit_add();
      });
    }

    function fb_refresh() {
      $('#place_sel').val("");
      $('.class_place').html('');
      $('.class_position').html('');
      $('.class_facility').html('');
      Object.keys(list_place).forEach(function(name_place) {
        $('.class_place').append('<div class="smallclass"><button class="btn_place">' + name_place + '</button></div>');
      });
      $('.btn_place').on('click', function() {
        var this_btn = $(this).text();
        $('.btn_place').removeClass('clicked');
        $('.class_position').html('');
        $('.class_facility').html('');
        $('.class_unit').css('display', 'none');
        $(this).addClass('clicked');
        Object.keys(list_place[this_btn]).forEach(function(name) {
          $('.class_position').append('<div class="smallclass"><button class="btn_position">' + name + '</button></div>');
        });
        $('button').button();
        $('.btn_position').on('click', function() {
          $('.btn_position').removeClass('clicked');
          $('.class_facility').html('');
          $('.class_unit').css('display', 'none');
          $(this).addClass('clicked');
          console.log(1);
          list_place[this_btn][$(this).text()].forEach((value, key) => {
            $('.class_facility').append('<div class="smallclass"><button class="btn_facility">' + value + '</button></div>');
          });
          $('button').button();
          $('.btn_facility').on('click', function() {
            fb_unit_add();
          });
        });
      });

      $('button').button();
    }

    function fb_unit_add(item) {
      $('.class_unit').css('display', 'block');
    }
  </script>
</head>

<body>
  <div id='alert' style='text-align: center; font-size: 16px; '>
    <div id='message'></div>
  </div>
  <div id='alert_dept' style='text-align: center; font-size: 16px;'>
  </div>
  <div id='class_main'>
    <div class='class_place'>
    </div>
    <div class='class_position'>
    </div>
    <div class='class_facility'>
    </div>
    <div class='class_unit'>
      <div class='class_unit_input'>
        <div>
          <label for="">品名</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">在庫</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">場所</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">場所</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">場所</label>
          <input type="text" name="" id="" />
        </div>
      </div>
      <div class='class_unit_input'>
        <div>
          <label for="">品名</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">在庫</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">場所</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">場所</label>
          <input type="text" name="" id="" />
        </div>
        <div>
          <label for="">場所</label>
          <input type="text" name="" id="" />
        </div>
      </div>
      <div class='class_map'>
        <img class="class_picture" src="https://khahoangfpt.pythonanywhere.com/static/images/Internet%20of%20Things%20Architecture.png" alt="マップ" />
      </div>
      <div style="float: right;">
        <button type="button" class="btn_def" onclick="get_data();"> 更新</button>
      </div>

    </div>
  </div>
</body>

</html>
