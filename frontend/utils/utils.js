let Utils = {
<<<<<<< HEAD
   datatable: function (table_id, columns, data) {
=======
   datatable: function (table_id, columns, data, pageLength=15) {
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
       if ($.fn.dataTable.isDataTable("#" + table_id)) {
         $("#" + table_id)
           .DataTable()
           .destroy();
       }
       $("#" + table_id).DataTable({
         data: data,
         columns: columns,
<<<<<<< HEAD
=======
         pageLength: pageLength,
         lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
       });
     },
     parseJwt: function(token) {
       if (!token) return null;
       try {
         const payload = token.split('.')[1];
         const decoded = atob(payload);
         return JSON.parse(decoded);
       } catch (e) {
         console.error("Invalid JWT token", e);
         return null;
       }
     }  
}
