if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

$(document).ready(function(){
    $('#amountExpense').on("keyup keydown change",function(){
        let expenseCategory = $('#expenseCategory').val();
        let amount = $(this).val();
        $.ajax({
            url:"/expense/check-limit",
            method:"POST",
            data:{
                expenseCategory: expenseCategory,
                amount: amount
            },
            success:function(data){
                $('#categoryLimit').html(data);
            }
        });
    });

    $('#categoryExpense').change(function(){
        let expenseCategory = $(this).val();
        let amount = $('#amount').val();
        $.ajax({
            url:"/expense/check-limit",
            method:"POST",
            data:{
                expenseCategory:expenseCategory,
                amount:amount
            },
            success:function(data){
                $('#categoryLimit').html(data);
            }
        });
    });

    $('#amountExpense').on("keyup keydown change",function(){
        let amount = $(this).val();
        let expenseCategory = $('#categoryExpense').val();
        $.ajax({
            url:"/expense/get-final-value",
            method:"POST",
            data:{
                expenseCategory:expenseCategory,
                amount:amount
            },
            success:function(data){
                if (!data) {
                    $('#categoryLimit').removeClass('alert-danger');
                    $('#categoryLimit').addClass('alert-success');

                } else {
                    $('#categoryLimit').removeClass('alert-success');
                    $('#categoryLimit').addClass('alert-danger');
                }
            }
        });
    });

    $('#categoryExpense').change(function(){
        let expenseCategory = $(this).val();
        let amount = $('#amount').val();
        $.ajax({
            url:"/expense/get-final-value",
            method:"POST",
            data:{
                expenseCategory:expenseCategory,
                amount:amount
            },
            success:function(data){
                if (!data) {
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