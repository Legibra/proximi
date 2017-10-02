jQuery(document).ready(function($){
    $("#cf7-export-form").click(function(event) {
        event.preventDefault();
        var id = $("#cat").find(':selected').data("id");
        console.log(id);
        if(id==0){
            alert("Please select the form to export!");
        }else{
            window.location.href = id;
               
        }
    });
    $("#cf7-filter-form").click(function(event) {
        event.preventDefault();
        var ulr = $("#cat").val();
        window.location.href = ulr;
    });
})