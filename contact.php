<?php

include('database_connection.php');

include_once('function.php');

if(!isset($_SESSION['type']))
{
	header('location:login.php');
}

require('header.php');
?>

<link href="https://fonts.googleapis.com/css?family=Oswald:700|Patua+One|Roboto+Condensed:700" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<section id="contact">
  <div class="container">
    <div class="well well-sm">
      <h3><strong>Contact Us</strong></h3>
    </div>
	
	<div class="row">
	  <div class="col-md-7">
      <!--
        <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d3736489.7218514383!2d90.21589792292741!3d23.857125486636733!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1506502314230" width="100%" height="315" frameborder="0" style="border:0" allowfullscreen></iframe> -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d18447.132529029765!2d-76.73678723073652!3d18.024255710818824!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xd05acd895429b661!2sVocational+Training+Development+Institute!5e0!3m2!1sen!2sjm!4v1525119840125" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
      </div>

      <div class="col-md-5">
          <h4><strong>Get in Touch</strong></h4> 
          <ul class="list-inline banner-social-buttons">
                    <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-twitter"> <span class="network-name">Twitter</span></i></a></li>
                    <li><a href="https://www.facebook.com/VTDIofficial/" class="btn btn-default btn-lg"><i class="fa fa-facebook"> <span class="network-name">Facebook</span></i></a></li>
                    <li><a href="https://www.youtube.com/channel/UCwiMDipz_hsS_RJlR3n1Q1w" class="btn btn-default btn-lg"><i class="fa fa-youtube-play"> <span class="network-name">Youtube</span></i></a></li>
                  </ul>
        <form id="contactForm" name="sentMessage">
          <div class="form-group">
            <input type="text" class="form-control" id="name" name="name" value=""  placeholder="Name">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" id="email" name="email" value=""  placeholder="E-mail">
          </div>
          <div class="form-group">
            <input type="tel" class="form-control"  id="phone" name="phone" value="" placeholder="Phone">
          </div>
          <div class="form-group">
            <textarea class="form-control" id="message" name="message" rows="3"  placeholder="Message"></textarea>
          </div>
          <!--<button class="btn btn-default" type="submit" name="button"> -->
          <button class="btn btn-default" type="submit" id="sendMessageButton" name="sendMessageButton">
              <i class="fa fa-paper-plane-o" aria-hidden="true"></i> Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('submit','#contactForm',function(event){
      event.preventDefault();
      //alert('From sub');
      var name = $("input#name").val();
      var email = $("input#email").val();
      var phone = $("input#phone").val();
      var message = $("textarea#message").val();
      if(!name || !email || !phone || !message){
        alert('Contact Form Fields cannot be empty!!');
        return false;
      }
      $this = $("#sendMessageButton");
      $this.prop("disabled", true); // disable submit button when AJAX call is complete
      //alert('seems goood');
      $.ajax({
        url:"mail/contact_me.php",
        method:"POST",
        data:{name:name,email:email,phone:phone,message:message},
        success:function(data){
          $('#contactForm').html('<div class="alert alert-success">'+data+'</div>');
        },
        complete: function() {
          setTimeout(function() {
            $this.prop("disabled", false); // Re-enable submit button when AJAX call is complete
          }, 1000);
        }
      });
      //Debug line
      //console.log(name +"---" + email + "----" + phone + "----" + message + "-----");
    });
  });
</script><!--Howard-->
<!--
<link href="https://fonts.googleapis.com/css?family=Oswald:700|Patua+One|Roboto+Condensed:700" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<section id="contact" class="content-section text-center">
        <div class="contact-section">
            <div class="container">
              <h2>Contact Us</h2>
              <p>Feel free to shout us by feeling the contact form or visiting our social network sites like Fackebook,Whatsapp,Twitter.</p>
              <div class="row">
                <div class="col-md-8 col-md-offset-2">
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="exampleInputName2">Name</label>
                      <input type="text" class="form-control" id="exampleInputName2" placeholder="Jane Doe">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail2">Email</label>
                      <input type="email" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com">
                    </div>
                    <div class="form-group ">
                      <label for="exampleInputText">Your Message</label>
                     <textarea  class="form-control" placeholder="Description"></textarea> 
                    </div>
                    <button type="submit" class="btn btn-default">Send Message</button>
                  </form>

                  <hr>
                    <h3>Our Social Sites</h3>
                  <ul class="list-inline banner-social-buttons">
                    <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-twitter"> <span class="network-name">Twitter</span></i></a></li>
                    <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-facebook"> <span class="network-name">Facebook</span></i></a></li>
                    <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-youtube-play"> <span class="network-name">Youtube</span></i></a></li>
                  </ul>
                </div>
              </div>
            </div>
        </div>
      </section>

      -->