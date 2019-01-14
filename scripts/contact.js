$(function(){
  // configure scrollbar powered by PerfectScrollbar
  const ps = new PerfectScrollbar('.enable-scroll', {
    wheelSpeed: 1,
    wheelPropagation: false,
    swipeEasing: true,
    minScrollbarLength: 20
  });

  $('#confirm').hide();

  // pop up confirmation dialog and its configuration
  // submit the form on clicking 'OK' button
  // cancel = actually do nothing on clicking 'Cancel' button

    $('#sbmtbtn').click(function(){
      try {
        let name = $('#name').prop('value');
        let email = $('#email').prop('value');
        let subject = $('#subject').prop('value');
        let message = $('#message').prop('value');
        if(name === '' | email === '' | subject === '' | message === ''){
          throw new Error('You have to fill form items to submit.');
        }
        message = message.replace(/\r?\n/g, '<br>');
        $('#confirm').fadeIn(500);
        $('#name-c').text(name);
        $('#email-c').text(email);
        $('#subject-c').text(subject);
        $('#message-c').html(message);
      }
      catch (e) {
        window.alert(e.message);
      }
    });

    $('#yes').click(function(){
      $('#contact').submit();
      $('#confirm').hide();
    });

    $('#no').click(function(){
      $('#confirm').fadeOut(500);
    });
});
