$(function(){
  const tooltip = $('.tooltip');

  tooltip.hide();
  $('.toolhover, .tooltip').on('mouseover', function(event){
    let posX = event.clientX;
    let posY = event.clientY;
    tooltip.css('left', posX + 10);
    tooltip.css('top', posY + 10);
    tooltip.show();
  });
  $('.toolhover, .tooltip').on('mouseout', function(){
    tooltip.hide();
  });
});
