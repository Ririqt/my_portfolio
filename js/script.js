function passWord() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

$('a').click(function(e) {
    e.preventDefault();  //stop the browser from following
    window.location.href = '../files/resume.pdf';
});
