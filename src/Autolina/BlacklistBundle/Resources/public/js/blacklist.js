
  jQuery(document).ready(function() {
    
    $('#table1').DataTable();
    
    
   // var myTable = $('#table2').dataTable();

    var t = $('#table2').DataTable({
      "bRetrieve":  true,
      "sPaginationType": "full_numbers",
      "order": [[ 1, "asc" ]],
      "iDisplayLength": 5,
      "stateSave": true
    });
    
    // Chosen Select
    jQuery("select").chosen({
      'min-width': '100px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    });

    // Add row in a table
    jQuery('#addRow').click(function(){
      var mail= $('#addedmail').val();
      var newrow = null;
      $.ajax({
          type: "POST",
          url: $('#addURL').val(),
          data: {mail : mail},
          success: function(response){
            if(response == parseInt(response, 10)){
              newrow='<td class="table-action-hide"><a href="#" data-toggle="modal" data-target=".make-modal-lg" data-type="editRow" data-email="'+mail+'" data-id="'+response+'" style="opacity: 1;"><i class="fa fa-pencil"></i></a><a href="#" class="delete-row" data-type="delRow"  data-id="'+response+'" style="opacity: 1;"><i class="fa fa-trash-o"></i></a></td>';
              t.fnAddData( [
                mail,
                newrow
              ] );
              
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
    $('*[data-type="delRow"]').click(function(e){
      e.stopPropagation();
      var c = confirm("Continue delete?");
      var tr = $(this).closest('tr');
      var id =  $(this).attr('data-id');
      if(c){
        $.ajax({
          type: "POST",
          url: $('#delURL').val(),
          data: { id: id},
          success: function(response){
            //t.fnDeleteRow($row);
            tr.fadeOut().remove();
            $("#table2").dataTable().fnDraw();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });     
      }
      return false;
    });

    // Edit row in a table
      // send selected email to modal
    var toEditEmail=null;
    $('*[data-type="editRow"]').click(function(){
      //var $row =  $(this).parent().parent();
      var tr = $(this).closest('tr');
      console.log(tr);
      toEditEmail=[$(this).attr('data-email'),$(this).attr('data-id')];
      jQuery(function () {
        jQuery('.col-sm-6 input').val(toEditEmail[0]);
      });
      
    });

      // modal submission
    jQuery("button#submitEdit").click(function(e){
      e.preventDefault();
      var mail= $('#toEdit').val();
      var id = toEditEmail[1];
      var oldmail = $("td").filter(function() { 
        return $.text([this]) == toEditEmail[0]; });
      console.log("edit"+ oldmail);
      $.ajax({
          type: "POST",
          url: $('#editURL').val(),
          data: { id: id , mail:mail},
          success: function(response){
            if(response == parseInt(response, 10)){
              oldmail.text(mail);
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
    
    
    // Show aciton upon row hover
    //TODO not working
    /*$('body').on( 'hover','#table2 tbody tr', function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });
  */
  
  });

