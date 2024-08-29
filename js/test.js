$(document).ready(function(){
  //hides dropdown content
  $(".size_chart").hide();
  
  //unhides first option content
  $("#option1").show();
  
  //listen to dropdown for change
  $("#size_select").change(function(){
    //rehide content on change
    $('.size_chart').hide();
    //unhides current item
    $('#'+$(this).val()).show();
  });
  
});     