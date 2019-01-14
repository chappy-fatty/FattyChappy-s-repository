$(function(){
  lazyload();
  const ps = new PerfectScrollbar('#table-container', {
    wheelSpeed: 1,
    wheelPropagation: false,
    swipeEasing: true,
    minScrollbarLength: 20
  });

  const galleryMain = $('#gallery-main');
  const showImg = $('#show-img');
  const sImgPath = '/uploaded/';
  const mImgPath = '/thumbs/';

  galleryMain.hide();
  $('#confirm').hide();
  ps.update();

  $('#page-selector').on('submit', function(){
    ps.update();
  });

  // Change the main image on clicking thumbnails
  $('.img-name').imagesLoaded()
    .done(function(){
      $('.img-name').on('click', 'img', function(){
        let thisSrc = ($(this).attr('src')).slice(7);
        let thisAlt = $(this).attr('alt');
        showImg.attr({
          src: mImgPath + thisSrc,
          srcset: sImgPath + thisSrc + ' 480w,' +
          mImgPath + thisSrc + ' 640w,',
          alt:thisAlt
        });
        galleryMain.imagesLoaded()
          .done(function(){
            galleryMain.fadeIn(500);
          });
      });

      galleryMain.on('click', function(){
        galleryMain.fadeOut(500);
      });
    });

  $('.delbtn').click(function(){
    let key = $(this).attr('data-key');
    let href = 'imgdel.php?key=' + key;
    let thisParent = $('#yes').closest('a');
    thisParent.attr('href', href);
    $('#confirm').show();
  });

  $('#no').click(function(){
    $('#confirm').hide();
  });
});
