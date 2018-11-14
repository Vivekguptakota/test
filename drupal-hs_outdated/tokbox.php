<html>
<body>
<!-- OpenTok.js library -->
<style>
body, html {
    background-color: gray;
    height: 100%;
}

#videos {
    position: relative;
    width: 100%;
    height: 100%;
    margin-left: auto;
    margin-right: auto;
}

#subscriber {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
}

#publisher {
    position: absolute;
    width: 360px;
    height: 240px;
    bottom: 10px;
    left: 10px;
    z-index: 100;
    border: 3px solid white;
    border-radius: 3px;
}
</style>
<script src="https://static.opentok.com/v2/js/opentok.js"></script>
<script>

var apiKey = '45912762';
var sessionId = '1_MX40NTgyODA2Mn5-MTQ5OTc2OTAxMTAzOH5yMjFpSVNIb3prREdiZFFMdXpVNk9ta2Z-UH4';
var token = 'T1==cGFydG5lcl9pZD00NTgyODA2MiZzaWc9MTFhNGJjZDEzODk5YmE3NGZiY2ExNjIyN2RhMmY1YzdiM2QxNzI0YzpzZXNzaW9uX2lkPTFfTVg0ME5UZ3lPREEyTW41LU1UUTVPVGMyT1RBeE1UQXpPSDV5TWpGcFNWTkliM3ByUkVkaVpGRk1kWHBWTms5dGEyWi1VSDQmY3JlYXRlX3RpbWU9MTQ5OTc2OTQwNCZub25jZT0wLjE4MjkxMTk5MzY0NzY1MTk4JnJvbGU9cHVibGlzaGVyJmV4cGlyZV90aW1lPTE0OTk4NTU4MDQ=';


var session = OT.initSession(apiKey, sessionId)
session.connect(token, function(error) {
   var publisher = OT.initPublisher();
   session.publish(publisher);
    console.log(publisher);

})


session.on('streamCreated', function(event) { console.log(event);
  session.subscribe(event.stream, 'subscriber', {
    insertMode: 'append',
    width: '100%',
    height: '100%'
  }, handleError);
});


</script>
</body>
</html>
