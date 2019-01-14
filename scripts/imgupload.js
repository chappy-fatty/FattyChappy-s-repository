$(function(){
  // configure scrollbar powered by PerfectScrollbar
  const ps = new PerfectScrollbar('.enable-scroll', {
    wheelSpeed: 1,
    wheelPropagation: false,
    swipeEasing: true,
    minScrollbarLength: 20
  });

  const upUrl = [
    'https://ns-1441.meowwow.name/server/img_upload.html',
    'https://ns-1441.meowwow.name/en/imgupload_guest/img_upload.html',
    'https://ns-1441.meowwow.name/ja/imgupload_guest/img_upload.html'
  ]

  const modUrl = [
    'https://ns-1441.meowwow.name/server/imginfo.php',
    'https://ns-1441.meowwow.name/en/imgupload_guest/imginfo.php',
    'https://ns-1441.meowwow.name/ja/imgupload_guest/imginfo.php'
  ]

  let url = window.location.href;
  let act = '';
  $('#confirm').hide();

// URL check to toggle showing #up-file-name, #up-image
  for (let i = 0; i < upUrl.length; i++){
    if(url.indexOf(upUrl[i]) !== -1){
      $('#up-file-name, #up-image').hide();
      act = $('#upload');
      break;
    }
    else if(url.indexOf(modUrl[i]) !== -1){
      $('#up-file-name, #up-image').show();
      act = $('#modify');
      break;
    }
  }

// show selected image preview thumbnail and file name when a user has selected an image file
  $('#upFile').change(function(){
    if(this.files.length > 0){
      let file = this.files[0];
      let fileName = this.files[0].name;
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function(){
        $('#up-file-name, #up-image').show();
        $('#up-file-name').html('<i class="fas fa-angle-double-right"></i>&nbsp;' + fileName);
        $('#up-image').attr({
          src: reader.result,
          alt: fileName
        });
        ps.update();
      }
    }
  });

// pop up confirmation dialog and its configuration
// submit the form on clicking 'OK' button
// cancel = actually do nothing on clicking 'Cancel' button

  $('#sbmtbtn').click(function(){
    $('#confirm').fadeIn(500);
  });

  $('#yes').click(function(){
    act.submit();
    $('#confirm').hide();
  });

  $('#no').click(function(){
    $('#confirm').fadeOut(500);
  });

});
