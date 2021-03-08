if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

$(document).ready(function(){
    $('#amountExpense').on('keyup keydown change',function(){
        let categoryExpense = $('#categoryExpense').val();
        let amountExpense = $(this).val();
        $.ajax({
            url:"/expense/check-limit",
            method:"POST",
            data:{
                categoryExpense: categoryExpense,
                amountExpense: amountExpense
            },
            success:function(data){
                $('#categoryLimit').html(data);
            }
        });
    });

    $('#categoryExpense').change(function(){
        let categoryExpense = $(this).val();
        let amountExpense = $('#amountExpense').val();
        $.ajax({
            url:"/expense/check-limit",
            method:"POST",
            data:{
                categoryExpense: categoryExpense,
                amountExpense: amountExpense
            },
            success: function(data){
                $('#categoryLimit').html(data);
                console.log(data);
                if (data > 0) {
                    $('#categoryLimit').removeClass('alert-danger');
                    $('#categoryLimit').addClass('alert-success');

                } else {
                    $('#categoryLimit').removeClass('alert-success');
                    $('#categoryLimit').addClass('alert-danger');
                }

            }
        });
    });


});