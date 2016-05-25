$(document).ready(function(){
    $('#renameModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var oldVolumeName = button.data('volume') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      $("#oldVolumeName")[0].value = oldVolumeName;
      $("#newVolumeName")[0].value = oldVolumeName;
      //modal.find('.modal-body input').val(recipient)
    })
    
    $(document).on('click', '.clone', function(e) {
        var volume = $(this).data('volume');
        zfs(volume, 'clone');
    });

    $(document).on('click', '.snapshot', function(e) {
        var volume = $(this).data('volume');
        zfs(volume, 'snapshot');
    });

    $(document).on('click', '.delete', function(e) {
        var volume = $(this).data('volume');
        zfs(volume, 'delete');
    });

    $(document).on('click', '.create', function(e) {
        var volume = $("#volumeName")[0].value;
        zfs(volume, 'create');
    });

    $(document).on('click', '.rename', function(e) {
        var newVolumeName = $("#newVolumeName")[0].value;
        var oldVolumeName = $("#oldVolumeName")[0].value;
        var obj = $(this);
        zfs(' ', 'rename', oldVolumeName, newVolumeName);
        //window.location.reload(true);
    });

    function zfs(volume, action, oldVolumeName, newVolumeName){
      jQuery.ajax({
              type: "POST",
              url: "functions.php",
              data: { volume: volume, action: action, oldVolumeName: oldVolumeName, newVolumeName: newVolumeName },

              success: function(data) { 
              alert(data);
              jQuery('#results').html(data);
              window.location.reload(true);
              
              setInterval(function() { 
                         jQuery('#results').html(data);
              },3000);    
              },
              error: function() {
              //    alert('fail');
              }                               
   });      
   }  

});
