
/*document.write('<script src="js/jquery-1.10.2.min.js"></script>');
document.write('<script src="js/jquery-migrate-1.2.1.min.js"></script>');
document.write('<script src="js/bootstrap.min.js"></script>');
document.write('<script src="js/modernizr.min.js"></script>');
document.write('<script src="js/jquery.sparkline.min.js"></script>');
document.write('<script src="js/toggles.min.js"></script>');
document.write('<script src="js/retina.min.js"></script>');
document.write('<script src="js/jquery.cookies.js"></script>');
document.write('<script type="text/javascript" src="wz_tooltip.js"></script>'); 
document.write('<script src="js/jquery.datatables.min.js"></script>');
document.write('<script src="js/chosen.jquery.min.js"></script>');
document.write('<script src="js/custom.js"></script>');
*/
  jQuery(document).ready(function() {
    
    jQuery('#table1').dataTable();
    
    
    
    // Chosen Select
    jQuery("select").chosen({
      'min-width': '100px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    });

    // Add row in a table
    jQuery('#addRow').click(function(){
      var mail= $('#addedmail').val();
      $.ajax({
          type: "POST",
          url: $('#addURL').val(),
          data: {mail : mail},
          success: function(response){
            if(response == parseInt(response, 10)){
              jQuery('#table2').dataTable({
                "sPaginationType": "full_numbers",
                "bDestroy":true,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": $('#updateURL').val()
              });

              alert("SUCCESS, id ="+response);
            }
            else{
              alert("INVALID : " + response);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });
    });

    // Delete row in a table
    $('body').delegate('#delRow', 'click', function(){
      var c = confirm("Continue delete?");
      var id =  $(this).attr('data-id');
      if(c){
        $.ajax({
          type: "POST",
          url: $('#TargetURL').val(),
          data: { id: id},
          success: function(response){
            jQuery('#table2').dataTable({
              "sPaginationType": "full_numbers",
              "bDestroy":true,
              "bProcessing": true,
              "bServerSide": true,
              "sAjaxSource": $('#updateURL').val()
            });
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });     
      }
      return false;
    });
  
    var myTable = jQuery('#table2').dataTable({
      "sPaginationType": "full_numbers",
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": $('#updateURL').val()
      });

     // TODO edit Show aciton upon row hover
    $('body').delegate('#table2.table-hidaction tbody tr', 'hover', function(){
      console.log("here!! hover");
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });
    
    // send selected email to modal
    var toEditEmail=null;
    $('body').delegate('#editRow', 'click', function(){
      console.log($(this).attr('data'));
      toEditEmail=[$(this).attr('data'),$(this).attr('data-id')];
      jQuery(function () {
        jQuery('.col-sm-6 input').val(toEditEmail[0]);
      });
    });

    // modal submission
    jQuery("button#submitEdit").click(function(e){
      e.preventDefault();
      var mail= $('#toEdit').val();
      var id = toEditEmail[1];
      $.ajax({
          type: "POST",
          url: $('#editURL').val(),
          data: { id: id , mail:mail},
          success: function(response){
            if(response == parseInt(response, 10)){
              jQuery('#table2').dataTable({
                "sPaginationType": "full_numbers",
                "bDestroy":true,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": $('#updateURL').val()
              });
              jQuery('#modal').modal('hide');
              alert("The email is successfully editted.");
            }
            else{
              alert("INVALID : " + response);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });
    });
    
    
  });