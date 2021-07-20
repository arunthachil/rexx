
$(document).ready(function(){ 
    $('#searchByEventDate').on('click', function() {
      $('#searchByEventDate').val('');
    });
    function filterData() {
      $.ajax({
          type:"POST",
          url:'index.php',
          data:$('#filterForm').serialize(),
          success: function(response){
            $('#tableBody').empty();
            result = JSON.parse(response);
            if (result.length > 1) {
              $.each( result, function( key, value ) {
                detailRow = "<tr><td>"+value['employee_name']+"</td><td>"+value['employee_email']+"</td><td>"+value['event_name']+"</td><td>"+value['event_date']+"</td><td>"+value['version']+"</td><td>"+value['participation_fee']+"</td></tr>";
                $('#tableBody').append(detailRow);
              });
            } else {
              detailRow = "<tr><td colspan='6'>No Data Found</td></tr>";
              $('#tableBody').append(detailRow);
            }
            
          }
      });
    }
    $('#filterButton').on('click', function() {
      filterData();
    });
    filterData();
    
    
    $('#insertData').on('click', function() {
      $.ajax({
        type:"GET",
        url:'insert.php',
        success: function(response){
          filterData();     
          alert('Data Inserted Successfully');
        }
      });
    });

    $('#createTable').on('click', function() {
      $.ajax({
        type:"GET",
        url:'config/schema.php',
        success: function(response){
          filterData();     
          alert('Tables Created Successfully');
        }
      });
    });
  });