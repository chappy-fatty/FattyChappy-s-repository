$(function(){
  $('select[name="language"]').change(function(){
    let lang = $('select[name="language"]').prop('value');
    let newStr = '/' + lang + '/';
    let url = location.href;
    let newUrl= url.replace(/\/(ja|en)\//gm, newStr);
    location.replace(newUrl);
  });
});
