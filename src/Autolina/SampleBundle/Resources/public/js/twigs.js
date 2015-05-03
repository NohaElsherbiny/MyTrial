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
    jQuery('.add-row').click(function(){
      var mail= $('#addedmail').val();
      $.ajax({
          type: "POST",
          url: $('#addURL').val(),
          data: {mail : mail},
          success: function(response){
            if(response == parseInt(response, 10)){
              var newRow = $("<tr><td>"+mail+"</td><td class=\"table-action-hide \"><a href=\"\" data-toggle=\"modal\" data-target=\".make-modal-lg\" id="+mail+" style=\"opacity: 0;\"><i class=\"fa fa-pencil\"></i></a><a href=\"\" class=\"delete-row \" id="+response+" style=\"opacity: 0;\"><i class=\"fa fa-trash-o\"></i></a></td></tr>");
              $("#table2").append(newRow);
              //newRow.appendTo("#table2");
              jQuery('#table2').dataTable.fnAddData(["<tr><td>"+mail+"</td><td class=\"table-action-hide \"><a href=\"\" data-toggle=\"modal\" data-target=\".make-modal-lg\" id="+mail+" style=\"opacity: 0;\"><i class=\"fa fa-pencil\"></i></a><a href=\"\" class=\"delete-row \" id="+response+" style=\"opacity: 0;\"><i class=\"fa fa-trash-o\"></i></a></td></tr>"]);
              jQuery('#table2').dataTable({
                "sPaginationType": "full_numbers",
                "bRetrieve": true
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
    jQuery('.delete-row').click(function(){
      var c = confirm("Continue delete?");
      var id =  $(this).attr('id');

      if(c){
        $.ajax({
          type: "POST",
          url: $('#TargetURL').val(),
          data: {id : id},
          success: function(){
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });
            jQuery(this).closest('tr').fadeOut(function(){
              jQuery(this).remove();
            });        
      }
      return false;
    });
    
    // Show aciton upon row hover
    jQuery('.table-hidaction tbody tr').hover(function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });
  
    jQuery('#table2').dataTable({
      "sPaginationType": "full_numbers",
      "bRetrieve": true
    });
  });
