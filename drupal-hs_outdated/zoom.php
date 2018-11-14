<!DOCTYPE html>

<html>

 <head>

  <title> Test Javascript API </title>

  //Remember to include jquery.js as script src
  <script type="text/javascript" src="http://d24cgw3uvb9a9h.cloudfront.net/static/13294/js/lib/jquery.js?44165"></script>
  <script type="text/javascript" src="http://d24cgw3uvb9a9h.cloudfront.net/static/13294/js/lib/jquery.cookie.js?44165"></script>
  //Remember to include zoomus.js as script src
  <script type="text/javascript" src="http://d24cgw3uvb9a9h.cloudfront.net/static/37726/js/api/zoomus.js?44165"></script>
<script>

</script>
 </head>

 <body>

  <input type="button" id="btn_login" value="Javascript Login Zoom"/>

  <br><br>

  <input type="button" id="btn_list" value="List Meetings"/>

  <br><br>

  Meeting Number:
  <input type="text" name="meeting_number" id="meeting_number" maxlength="15"/>

  <br><br>

  <input type="button" id="btn_get" value="Get Meeting"/>

  <input type="button" id="btn_pmi" value="Get PMI"/>

  <input type="button" id="btn_end" value="End Meeting"/>

  <input type="button" id="btn_del" value="Delete Meeting"/>

  <br><br>

  Meeting Info:
  <textarea id="meetingInfo" name="meeingInfo" cols="120" rows="10">

  {"topic":"Tsting meeting by VG",

   "password":"123",

   "type":2,

   "start_time":"2017-7-24T013:52:00Z",

   "duration":35,

   "timezone":"GMT+5:30",

   "option_jbh":true,

   "option_host_video":true,

   "option_participants_video":true,

   "option_start_type":"video",

   "option_audio_type":"both"}

  </textarea>

  <br><br>

  <input type="button" id="btn_create" value="Create Meeting"/>

  <input type="button" id="btn_update" value="Update Meeting"/>
  <input type="button" id="join_meeting" value="Join Meeting"/>

  <br><br>

  <h3 id="api_title"></h3>

  <p id="errMsg"></p>

  <script>

  $('#btn_login').click(function(){

      Zoom.init("https://www.zoom.us/api/v1");
      //Remember to put your email and password to login
      Zoom.login({email:"vivek.gupta@faichi.com",password:"faichi"},function(result){
          console.log(result);
          $('#btn_login').val("login success");

      });

      return false;
  });

  $('#btn_list').click(function(){
      Zoom.listMeeting({page_size:10,page_number:1},function(result){

          $('#api_title').html("List Meeting");
          //$('#errMsg').text(JSON.parse(result));
          $('#errMsg').html(JSON.stringify(result));

          console.log(result);
          /*$.each($.parseJSON(result), function(key,value){
              console.log(key);
              console.log(value);
          });*/
          var hhtml = "";
          jQuery.each(result, function(key, value) {
             hhtml = "<ul>";
              var total_record = 0;
              if(jQuery.type(value) == "array"){
                console.log(value);
                jQuery.each(value, function(k, va) {
                  console.log(va);
                   hhtml += "<ul>";
                  jQuery.each(va, function(nk, nva) {
                      console.log(nk);
                      console.log(nva);
                      hhtml += "<li>"+nk+"="+nva+"</li>";
                  });
                   hhtml += "</ul><hr>";
                });
              }
              else{
                if(key == "total_records"){
                  total_record = value;
                }
                hhtml += "<li>"+key+"="+value+"</li>";
                console.log(key+"=="+value);
              }
              hhtml += "</ul>";
          });
          $('#errMsg').html(hhtml);

      });

      return false;

  });

  $('#btn_create').click(function(){

      Zoom.createMeeting(JSON.parse($('#meetingInfo').val()),

          function(result){

              $('#api_title').html("Create Meeting");

              $('#errMsg').html(JSON.stringify(result));

          });

      return false;

  });

  $('#btn_get').click(function(){

      if($('#meeting_number').val().trim().length < 8){

          alert("Please enter meeting number.");

          return ;

      }

      Zoom.getMeeting({meeting_number: $('#meeting_number').val()},

          function(result){

              var hhtml = "";
          jQuery.each(result, function(key, value) {
             hhtml = "<ul>";
              var total_record = 0;
              if(jQuery.type(value) == "array"){
                console.log(value);
                jQuery.each(value, function(k, va) {
                  console.log(va);
                   hhtml += "<ul>";
                  jQuery.each(va, function(nk, nva) {
                      console.log(nk);
                      console.log(nva);
                      hhtml += "<li>"+nk+"="+nva+"</li>";
                  });
                   hhtml += "</ul><hr>";
                });
              }
              else{
                if(key == "total_records"){
                  total_record = value;
                }
                hhtml += "<li>"+key+"="+value+"</li>";
                console.log(key+"=="+value);
                if(key=="start_url"){
/*                  var x = document.createElement("IFRAME");
                  x.setAttribute("src", value);
                  document.body.appendChild(x);*/

                }
              }
              hhtml += "</ul>";
          });
          $('#errMsg').html(hhtml);

          });

      return false;

  });

  $('#btn_end').click(function(){

      if($('#meeting_number').val().trim().length < 8){

          alert("Please enter meeting number.");

          return ;

      }

      Zoom.endMeeting({meeting_number: $('#meeting_number').val()},

          function(result){

              $('#api_title').html("End Meeting");

              $('#errMsg').html(JSON.stringify(result));

          });

      return false;

  });

  $('#btn_del').click(function(){

      if($('#meeting_number').val().trim().length < 8){

          alert("Please enter meeting number.");

          return ;

      }

      Zoom.deleteMeeting({meeting_number: $('#meeting_number').val()},

          function(result){

              $('#api_title').html("Delete Meeting");

              $('#errMsg').html(JSON.stringify(result));

          });

      return false;

  });

  $('#btn_pmi').click(function(){

      Zoom.getPMI(

          function(result){

              $('#api_title').html("GET PMI");

              $('#errMsg').html(JSON.stringify(result));

          });

      return false;

  });

  $('#btn_update').click(function(){

      if($('#meeting_number').val().trim().length < 8){

          alert("Please enter meeting number.");

          return ;

      }

      var data = JSON.parse($('#meetingInfo').val());

      data.meeting_number = $('#meeting_number').val().trim();

      Zoom.updateMeeting(data,

          function(result){

              $('#api_title').html("Update Meeting");

              $('#errMsg').html(JSON.stringify(result));

          });

      return false;

  });

  </script>

 </body>

</html>
