$(function() {
  $(document).on("click", "#showstats", function() {
    location.reload();
  });
  $('a[href="'+window.location.hash+'"]').tab('show');
});
var count = 0;
if($(document).hasClass('.child')){
  var number = $(document).find('.child').attr("class").split(' ');
  var count = parseInt(number[number.length-1]) + 1;
}
$(document).on("click", "#add_child", function() {
  var child_input =
  '<div class="child col-xs-12"><h3 class="status-title">Родственник</h3><div class="form-group"><label for="ch'+count+'_FIO">ФИО ребенка</label><input type="text" class="form-control" id="ch'+count+'_FIO" name="ch_FIO[]" placeholder="ФИО ребенка" required></div><div class="form-group"><label for="ch'+count+'_gender">Пол ребенка</label><div class="radio"><label><input type="radio" name="ch_gender['+count+']" value="Мужской" checked>Мужской</label></div><div class="radio"><label><input type="radio" name="ch_gender['+count+']" value="Женский">Женский</label></div></div><div class="form-group"><label for="ch'+count+'_birthday">Дата рождения</label><input type="date" class="form-control" id="ch'+count+'_birthday" name="ch_birthday[]" required></div><div class="form-group"><label for="ch'+count+'_workpoint">Место работы</label><input type="text" class="form-control" id="ch'+count+'_workpoint" name="ch_workpoint[]" placeholder="Место работы" required></div><a name="add_statement" class="btn btn-default btn-default remove_child">Удалить ребенка</a></div>&nbsp;';
  $('.man').after(child_input);
  count++;
});
$(document).on("click", ".remove_child", function() {
  $(this).closest('.child').remove();
});
// store the currently selected tab in the hash value
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  var id = $(e.target).attr("href").substr(1);
  window.location.hash = id;
});
$('#state_select').change(function() {
  var val = $('#state_select :selected').val();
  $('.st').hide();
  $('.st').find(':input').prop('disabled', true);
  $('.'+val).find(':input').prop('disabled', false);
  $('.'+val).show();
});
$('#filter_st').change(function() {
  var fil_val = $('#filter_st :selected').val();
  $('.fil_in').hide();
  $('.fil_in').prop('disabled', true);
  $('.fil_in'+fil_val).prop('disabled', false);
  $('.fil_in'+fil_val).show();
});
